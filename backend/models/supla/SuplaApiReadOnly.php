<?php

namespace suplascripts\models\supla;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use suplascripts\app\Application;
use suplascripts\models\User;

class SuplaApiReadOnly extends SuplaApi
{
    /** @var Logger */
    private $logger;

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->logger = new Logger('app_logger');
        $file_handler = new StreamHandler(Application::VAR_PATH . "/logs/app.log");
        $this->logger->pushHandler($file_handler);
    }

    public function turnOn(int $channelId)
    {
        $this->logger->info('READ-ONLY: Turn on: ' . $channelId);
        return true;
    }

    public function turnOff(int $channelId)
    {
        $this->logger->info('READ-ONLY: Turn off: ' . $channelId);
        return true;
    }
}
