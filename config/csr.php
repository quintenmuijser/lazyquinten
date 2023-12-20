<?php

return [
    'entities' => [
        [
            'crud' => true,
            'name' => 'Product',
            'database' => [
                'model' => [
                    'title' => [
                        'type' => 'string',
                        'nullable' => false,
                        'unique' => false,
                    ],
                    'description' => [
                        'type' => 'text',
                        'nullable' => true,
                        'unique' => false,
                    ],
                ],
                'relationships' => [
                    ['type' => 'belongsTo', 'model' => 'User'],
                    ['type' => 'hasMany', 'model' => 'Comment']
                ],
            ],
            'controller' => [
                'methods' => [
                    'store' => [
                        'policy' => 'admin-only',
                        'request' => 'store',
                    ]
                ]
            ],
        ],
    ]
];