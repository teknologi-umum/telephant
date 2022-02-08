<?php

namespace App\Helpers;

use App\Commands\BapacCommand;
use App\DataObject\TelegramMessageSenderJson;
use App\Helpers\Json\BapackJson;
use App\Models\Bapacks;
use JsonMapper\Cache\NullCache;
use JsonMapper\Handler\PropertyMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\JsonMapperInterface;
use JsonMapper\Middleware\Attributes\Attributes;
use JsonMapper\Middleware\Attributes\MapFrom;
use JsonMapper\Middleware\TypedProperties;
use Longman\TelegramBot\Entities\User;

class Helper
{
    public static function bapac(
        ?int $userId,
        ?string $message,
        ?BapacCommand $command,
        ?User $replyFrom
    ) {
        var_dump($message);
        var_dump($userId);

        $foundBapac = Bapacks::where('user_id', $userId)->first();

        if ($foundBapac) {
            var_dump('Found bapac');
            var_dump(json_encode(($foundBapac)));

            $bapac = new BapackJson;

            (new JsonMapperFactory())->create(
                new PropertyMapper(),
                new Attributes(),
                new TypedProperties(new NullCache)
            )->mapObjectFromString(json_encode($replyFrom), $bapac);

            var_dump($bapac);

            $command?->replyToChat('Found bapac:' . (string) $bapac?->id . ' ' . $bapac?->firstName);
        } else {
            var_dump('Bapac' . $userId . ' not found. Creating...');

            $bapac = new TelegramMessageSenderJson;

            (new JsonMapperFactory())->create(
                new PropertyMapper(),
                new Attributes(),
                new TypedProperties(new NullCache)
            )->mapObjectFromString(json_encode($replyFrom), $bapac);

            $bapac = Bapacks::updateOrCreate(['user_id' => $userId], (array) [
                'user_id' => $bapac?->id,
                'first_name' => $bapac?->firstName,
                'last_name' => $bapac?->lastName,

            ]);
            $command?->replyToChat('Bapac ' . $foundBapac?->user_id . ' not found. Creating...');
        }
    }

    
    public static function createJsonMapper(): JsonMapperInterface {
        return (new JsonMapperFactory())->create(
            new PropertyMapper(),
            new Attributes(),
            new TypedProperties(new NullCache)
        );
    }
}
