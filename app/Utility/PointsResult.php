<?php

namespace App\Utility;

use JsonMapper\Middleware\Attributes\MapFrom;

class PointsResult
{
    #[MapFrom("user_id")]
    public ?int $userId = null;
    public ?string $key = null;
    public ?int $op = null; // 0 = plus, 1 = minus\
    public ?int $count = null;
}
