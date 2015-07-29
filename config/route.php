<?php

return [
    'permissions' => [
        'api.v1.agenda.store' => [
            'roles' => [
                'officer',
            ],
        ],
        'api.v1.agenda.destroy' => [
            'roles' => [
                'admin',
            ],
        ],
    ],
];
