<?php

namespace App\Commands;

use App\DataObject\TelegramMessageSenderJson;
use App\Helpers\Helper;
use App\Helpers\Json\BapackJson;
use App\Models\Bapacks;
use App\Models\BapacksTransaction;
use App\Models\Points;
use App\Models\PointsTransactions;
use App\Protos\TelephantPointResult;
use App\Protos\TelephantPointResult\PointOperator;
use App\Utility\PointsHandler;
use Exception;
use Illuminate\Database\Eloquent\Model;
use JsonMapper\Cache\NullCache;
use JsonMapper\Handler\PropertyMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\Attributes\Attributes;
use JsonMapper\Middleware\TypedProperties;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class PointsCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'points';

    /**
     * @var string
     */
    protected $description = 'points command';

    /**
     * @var string
     */
    protected $usage = '/bapac';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = false;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        try {
            $messageText = $this->getMessage()->getText();
            $replyFrom = $this->getMessage()?->getReplyToMessage()?->getFrom();

            /** @var ?TelephantPointResult */
            $parseResult = (function () use ($messageText) {
                try {
                    return PointsHandler::handle($messageText);
                } catch (Exception $e) {
                    return null;
                }
            })();

            // If reply from is null, just get leaderboard
            if ($replyFrom != null) {
                return $this?->replyToChat($parseResult?->getKey() . ' leaderboard:');
            }

            // Update bapac information

            /** @var ?Bapacks */
            $foundBapac = null;

            try {
                if ($replyFrom != null) {
                    $foundBapac = Bapacks::where('user_id', '=',  $replyFrom?->getId())?->first();

                    if ($foundBapac == null) {
                        $foundBapac = Bapacks::updateOrCreate(['id' => null], (array) [
                            'user_id' => $replyFrom?->getId()
                        ]);
                    }

                    $foundBapac->first_name = $replyFrom?->getFirstName();
                    $foundBapac->last_name = $replyFrom?->getLastName();

                    $foundBapac = Bapacks::updateOrCreate(['id' =>  $foundBapac?->id], $foundBapac?->toArray());
                }
            } catch (Exception $e) {
                // pass
                return $this->replyToChat('Something went wrong.' . $e);
            }
            //  $this?->replyToChat('Update bapac info OK');


            // Update key

            /** @var ?Points */
            $foundPoint = null;

            if ($parseResult?->getKey() != null) {
                $foundPoint = Points::query()->where('key', '=', $parseResult?->getKey())->first();

                if ($foundPoint == null) {
                    $foundPoint = Points::query()->updateOrCreate(['id' => null], [
                        'key' =>   $parseResult?->getKey()
                    ]);
                }
            }
            //  $this?->replyToChat('Update key OK');

            // Add transaction
            if ($foundPoint != null && $parseResult?->getCount() != null && $replyFrom != null) {
                PointsTransactions::query()->updateOrCreate(
                    ['id' => null],
                    [
                        'points_id' => $foundPoint?->id,
                        'bapacks_id' => $replyFrom?->getId(),
                        'op' => $parseResult?->getOp(),
                        'points' =>  $parseResult?->getCount()
                    ]
                );
            }
            //  $this?->replyToChat('Update transaction OK');

            // Point transaction
            if ($replyFrom != null && $foundPoint != null) {
                // Get transactions
                $totalPoints = 0;

                $pts = PointsTransactions::query()
                    ->where('bapacks_id', '=', $replyFrom?->getId())
                    ->where('points_id', '=', $foundPoint?->id)
                    ->get();

                foreach ($pts as $p) {
                    if ($p?->points != null) {
                        if ($p?->op == PointOperator::PLUS) {
                            $totalPoints += $p->points;
                        } else if ($p?->op == PointOperator::MINUS) {
                            $totalPoints -= $p->points;
                        }
                    }
                }

                if ($parseResult?->getCount() != null && $parseResult?->getCount() > 0) { // See user incremented points
                    if ($replyFrom != null) {
                        return $this->replyToChat(
                            (string) $replyFrom?->getFirstName() .
                                ' ' .
                                (string) $replyFrom?->getLastName() .
                                ' ' .
                                $parseResult?->getKey() .
                                ' points: ' .
                                (function () use ($parseResult) {
                                    if ($parseResult?->getOp() == PointOperator::PLUS) {
                                        return '+';
                                    } else if ($parseResult?->getOp() == PointOperator::MINUS) {
                                        return '-';
                                    } else {
                                        return 'UNKNOWN';
                                    }
                                })() .
                                (string) $parseResult?->getCount() .
                                '. Now: ' .
                                $totalPoints
                        );
                    } else {
                        return $this?->replyToChat('To add points, please reply to user first!');
                    }
                } else if ($parseResult?->getKey() != null) { // Just See user points
                    if ($replyFrom != null) {
                        return $this->replyToChat(
                            (string) $replyFrom?->getFirstName() .
                                ' ' .
                                (string) $replyFrom?->getLastName() .
                                ' ' .
                                $parseResult?->getKey() .
                                ' points: ' .
                                $totalPoints
                        );
                    } else {
                        return $this?->replyToChat('Please reply first to get info!');
                    }
                }
            }
        } catch (Exception $e) {
            return $this->replyToChat('Something went wrong.' . $e);
        }
    }
}
