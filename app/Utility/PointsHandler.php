<?php

namespace App\Utility;

use App\Protos\TelephantPointResult;
use App\Protos\TelephantPointResult\PointOperator;

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
}
