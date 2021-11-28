<?php

namespace App\DataObject; 

use JsonMapper\Middleware\Attributes\MapFrom;

class TelegramMessageSenderJson
{
    public ?string $id = null;
    #[MapFrom("first_name")]
    public ?string $firstName = null;
    #[MapFrom("last_name")]
    public ?string $lastName = null;
}
