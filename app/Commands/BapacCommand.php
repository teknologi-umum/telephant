<?php

namespace App\Commands;

use App\DataObject\TelegramMessageSenderJson;
use App\Helpers\Helper;
use App\Helpers\Json\BapackJson;
use App\Models\Bapacks;
use App\Models\BapacksTransaction;
use Exception;
use JsonMapper\Cache\NullCache;
use JsonMapper\Handler\PropertyMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\Attributes\Attributes;
use JsonMapper\Middleware\TypedProperties;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class BapacCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'bapac';

    /**
     * @var string
     */
    protected $description = 'bapac command';

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

            if ($replyFrom) {
                $user = new TelegramMessageSenderJson;

                (new JsonMapperFactory())->create(
                    new PropertyMapper(),
                    new Attributes(),
                    new TypedProperties(new NullCache)
                )->mapObjectFromString(json_encode($replyFrom), $user);

                Helper::bapac($user?->id, substr($messageText, strlen('/bapac ')), $this, $replyFrom);

                return $this->replyToChat('Bapac points ' . $user?->firstName . ' ' . $user?->lastName . ': 0');
            }

            $leaderboardTemplate = "Bapac leaderboard: Siapa paling bapac di sini?\n";
            $leaderboardStr = '';

            $ind = 0;
            foreach (Bapacks::all() as $b) {
                $ind++;
                $bapac = new BapackJson;

                (new JsonMapperFactory())->create(
                    new PropertyMapper(),
                    new Attributes(),
                    new TypedProperties(new NullCache)
                )->mapObjectFromString(json_encode($b), $bapac);

                // $this->replyToChat(json_encode($bapac));

                $leaderboardStr = $leaderboardStr . (string) $ind . '. ' . $bapac->firstName . ' ' . $bapac->lastName . "\n";
            }

            return $this->replyToChat($leaderboardTemplate . $leaderboardStr);
        } catch (Exception $e) {
            return $this->replyToChat('Something went wrong.' . $e);
        }
    }
}
