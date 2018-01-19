<?php

namespace suplascripts\app\commands;

use suplascripts\app\Application;
use suplascripts\models\scene\PendingScene;
use suplascripts\models\scene\Scene;
use suplascripts\models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMetricsGaugesCommand extends Command {

    protected function configure() {
        $this
            ->setName('metrics:gauges')
            ->setDescription('Sends all gauges to the metrics collector.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $metricsConfig = Application::getInstance()->getSetting('metrics', []);
        $metricsEnabled = $metricsConfig['enabled'] ?? false;
        if ($metricsEnabled) {
            Application::getInstance()->metrics->gauge('users', User::count());
            Application::getInstance()->metrics->gauge('pending_scenes', PendingScene::count());
            Application::getInstance()->metrics->gauge('scenes', Scene::count());
            Application::getInstance()->metrics->send();
        }
    }
}