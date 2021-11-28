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

class SegsCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'segs';

    /**
     * @var string
     */
    protected $description = 'segs command';

    /**
     * @var string
     */
    protected $usage = '/segs';

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
        return $this->replyToChat('Uwoogh... seggssss 😭');
    }
}
