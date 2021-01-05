<?php

namespace Militar;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface {

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                Model\MilitarTable::class => function($container) {
                    $tableGateway = $container->get(Model\MilitarTableGateway::class);
                    return new Model\MilitarTable($tableGateway);
                },
                Model\MilitarTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Militar());
                    return new TableGateway('militares', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                Controller\MilitarController::class => function($container) {
                    return new Controller\MilitarController(
                    $container->get(Model\MilitarTable::class)
                    );
                },
            ],
        ];
    }

}
