<?php

use Attla\Support\Envir;

return [
    // This value is used to encrypt and decrypt the data
    'secret' => Envir::get('JOTH_SECRET', ''),

    // This are the attributes that will be encrypted or decrypted
    'attributes' => [
        'routes' => [
            '/auth' => [
                'email',
                'password',
            ],

            '/user/*' => [
                'email',
                'name',
            ],
        ],

        'globals' => [
            'email',
            'password',
            'remember_token',
            'current_password',
            'password',
            'password_confirmation',
        ],
    ],
];
