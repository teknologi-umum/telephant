<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Commands\BapacCommand;
use App\Commands\EchoMessageCommand;
use App\Commands\SegsCommand;
use App\DataObject\TelegramMessageSenderJson;
use App\Helpers\Helper;
use App\Helpers\Json\BapackJson;
use App\Models\Points;
use App\Models\PointsTransactions;
use App\Protos\TelephantPoint;
use App\Protos\TelephantPointData;
use App\Protos\TelephantPointResult;
use App\Protos\TelephantPointResults;
use App\Utility\PointsHandler;
use Illuminate\Http\Request;
use JsonMapper\Cache\NullCache;
use JsonMapper\Handler\PropertyMapper;
use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\Attributes\Attributes;
use JsonMapper\Middleware\TypedProperties;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/init', function () use ($router) {
    try {
        // Create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram(env('TELEGRAM_BOT_API_KEY'), env('TELEGRAM_BOT_USERNAME'));

        // Set webhook
        $result = $telegram->setWebhook(env('TELEGRAM_BOT_WEBHOOK_URL'));
        if ($result->isOk()) {
            echo $result->getDescription();
        }
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        // log telegram errors
        echo $e->getMessage();
    }
});

$router->post('/hook', function () use ($router) {
    try {
        // Create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram(env('TELEGRAM_BOT_API_KEY'), env('TELEGRAM_BOT_USERNAME'));

        $telegram->addCommandClass(EchoMessageCommand::class);
        $telegram->addCommandClass(BapacCommand::class);
        $telegram->addCommandClass(SegsCommand::class);


        // Handle telegram webhook request
        $telegram->handle();
    } catch (Exception $e) {
        // Silence is golden!
        // log telegram errors
        echo $e->getMessage();
    }
});

$router->get('/send-test', function () use ($router) {
    $user = new TelegramMessageSenderJson;

    // dd(json_encode([
    //     'first_name' => 'hello',
    //     'last_name' => 'hey'
    // ]));

    (new JsonMapperFactory)->create(
        new PropertyMapper(),
        new Attributes(),
        new TypedProperties(new NullCache)
    )->mapObjectFromString(json_encode([
        'first_name' => 'hello',
        'last_name' => 'hey'
    ]), $user);


    return json_encode($user);
});

$router->post('/bapack-test', function () use ($router) {
    new BapackJson;
    $st = '';

    $st = $st . 'h';

    var_dump('st');
    var_dump($st);
    Helper::bapac(123, '/bapac +10', null, null);
});

$router->get('/testparsepoints', function () use ($router) {
    $msgTests = [
        '/points bapac 1',
        '/points hello 200',
        '/points pizza 100',
        '/points bruh -330',
        '/points bruh -Z33x',
        '/points bruh -33x',
    ];

    $tpData = new TelephantPointData();

    $tpData?->setResults(
        (function () use ($msgTests) {
            $tprs = new TelephantPointResults();
            $tprs->setResults(
                array_map(
                    function (string $msg) {
                        $pts = PointsHandler::handle($msg);

                        $foundPoint = (function () use ($pts): Points {
                            $fp = Points::where('key', '=', $pts?->getKey())->first();

                            if ($fp != null) {
                                return $fp;
                            } else {
                                return Points::updateOrCreate(['id' => null], [
                                    'key' => $pts?->getKey()
                                ]);
                            }
                        })();

                        PointsTransactions::updateOrCreate(['id' => null], [
                            'points_id' => $foundPoint?->id,
                            'points' => $pts?->getCount(),
                            'op' => $pts?->getOp(),
                        ]);

                        return $pts;
                    },
                    $msgTests,
                )
            );

            return $tprs;
        })(),
    );

    $tpData?->setPoints(
        array_map(
            function (string $m): TelephantPoint {
                $res = PointsHandler::handle($m);

                $tp = new TelephantPoint;

                $foundPoint = (function () use ($res): Points {
                    $fp = Points::where('key', '=', $res?->getKey())->first();

                    if ($fp != null) {
                        return $fp;
                    } else {
                        return Points::updateOrCreate(['id' => null], [
                            'key' => $res?->getKey()
                        ]);
                    }
                })();

                $tp?->setId($foundPoint?->id);
                $tp?->setKey($foundPoint?->key);

                return $tp;
            },
            $msgTests
        )

    );


    return response($tpData->serializeToJsonString())
        ->header('content-type', 'application/json');
});

$router->get('/points-view', function () use ($router) {
    return Points::all()->map(function (Points $p) {
        $p->pointsTransactions;

        return $p;
    });
});

$router->get('/points-leaderboard', function (Request $request) use ($router) {
    $fPoint = Points::where("key", '=',  $request->query('key'))->first();

    if ($fPoint != null) {
        $fPoint->pointsTransactions;
        return $fPoint;
    }
});

$router->get('/points-transactions', function () use ($router) {
    return PointsTransactions::all();
});
