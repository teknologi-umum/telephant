<?php

namespace App\Commands;

use App\DataObject\TelegramMessageSenderJson;
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

class EchoMessageCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'echo';

    /**
     * @var string
     */
    protected $description = 'echo command';

    /**
     * @var string
     */
    protected $usage = '/echo';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        try {
            // return $this->replyToChat('echo');

            $replyFrom = $this->getMessage()?->getReplyToMessage()?->getFrom();

            if ($replyFrom) {
                $user = new TelegramMessageSenderJson;

                (new JsonMapperFactory())->create(
                    new PropertyMapper(),
                    new Attributes(),
                    new TypedProperties(new NullCache)
                )->mapObjectFromString(json_encode($replyFrom), $user);

                return $this->replyToChat(json_encode($user));
            }

            return $this->replyToChat('Please reply first!');
        } catch (Exception $e) {
            return $this->replyToChat('Something went wrong.' . $e);
        }
    }
}
