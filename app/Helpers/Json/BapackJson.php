<?php

namespace App\Helpers\Json;

use JsonMapper\Middleware\Attributes\MapFrom;

class BapackJson
{
    public ?int $id = null;
    #[MapFrom("user_id")]
    public ?int $userId = null;
    #[MapFrom("first_name")]
    public ?string $firstName = null;
    #[MapFrom("last_name")]
    public ?string $lastName = null;
}
