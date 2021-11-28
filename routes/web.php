<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Commands\BapacCommand;
use App\Commands\EchoMessageCommand;
use App\Commands\SegsCommand;
use App\DataObject\TelegramMessageSenderJson;
use App\Helpers\Helper;
use App\Helpers\Json\BapackJson;
use JsonMapper\Cache\NullCache;
use JsonMapper\Handler\PropertyMapper;
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

    $st = $st.'h';

    var_dump('st');
    var_dump($st);
    Helper::bapac(123, '/bapac +10', null, null); 
});