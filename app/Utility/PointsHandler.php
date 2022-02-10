<?php

namespace App\Utility;

use App\Models\Bapacks;
use App\Models\Points;
use App\Models\PointsTransactions;
use App\Protos\TelephantPointResult;
use App\Protos\TelephantPointResult\PointOperator;
use Exception;
use Longman\TelegramBot\Commands\UserCommand;

class PointsHandler
{
    public static function handle(string $msg): ?TelephantPointResult
    {
        $parsed = explode(' ', trim($msg));

        if (count($parsed) < 3) {
            if (count($parsed) < 2) {
                return null;
            }
            $tpr = new TelephantPointResult();
            $tpr?->setKey($parsed[1]);

            return $tpr;
        }

        $key = $parsed[1];
        $pts = $parsed[2];

        $tpr = new TelephantPointResult();

        if ($key != null) {
            $tpr?->setKey($key);
        }

        if ($pts != null) {
            $parsed_pts = (int) $pts;

            // Only number, default to plus
            if ($parsed_pts > 0) {
                $tpr->setOp(PointOperator::PLUS);
                $tpr->setCount($parsed_pts);
            } else {
                $opStr = substr($pts, 0, 1);

                if ($opStr == '+') {
                    $tpr->setOp(PointOperator::PLUS);
                } else if ($opStr == '-') {
                    $tpr->setOp(PointOperator::MINUS);
                }

                $tpr->setCount(abs((int) $pts));
            }
        }

        return $tpr;
    }

    public static function execute(?UserCommand $com, string $messageText)
    {
        try {
            $replyFrom = $com?->getMessage()?->getReplyToMessage()?->getFrom();

            /** @var ?TelephantPointResult */
            $parseResult = (function () use ($messageText) {
                try {
                    return PointsHandler::handle($messageText);
                } catch (Exception $e) {
                    return null;
                }
            })();

            // If parse result is null, just fail
            if ($parseResult == null) {
                return $com?->replyToChat('Usage: /points point_key +6');
            }


            // If reply from is null, just get leaderboard
            if ($replyFrom != null) {
                return $com?->replyToChat($parseResult?->getKey() . ' leaderboard:');
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
                return $com->replyToChat('Something went wrong.' . $e);
            }
            //  $com?->replyToChat('Update bapac info OK');


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
            //  $com?->replyToChat('Update key OK');

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
            //  $com?->replyToChat('Update transaction OK');

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
                        return $com->replyToChat(
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
                        return $com?->replyToChat('To add points, please reply to user first!');
                    }
                } else if ($parseResult?->getKey() != null) { // Just See user points
                    if ($replyFrom != null) {
                        return $com->replyToChat(
                            (string) $replyFrom?->getFirstName() .
                                ' ' .
                                (string) $replyFrom?->getLastName() .
                                ' ' .
                                $parseResult?->getKey() .
                                ' points: ' .
                                $totalPoints
                        );
                    } else {
                        return $com?->replyToChat('Please reply first to get info!');
                    }
                }
            }
        } catch (Exception $e) {
            return $com->replyToChat('Something went wrong.' . $e);
        }
    }
}
