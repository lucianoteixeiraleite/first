<?php

namespace Militar;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'militar' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/militar[/:action[/:nip]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'nip' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\MilitarController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'militar' => __DIR__ . '/../view',
        ],
    ],
];
