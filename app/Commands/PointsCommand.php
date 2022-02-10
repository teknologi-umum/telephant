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
        return PointsHandler::execute($this, $this->getMessage()->getText());
    
    }
}
