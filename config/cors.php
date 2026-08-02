<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de CORS
    |--------------------------------------------------------------------------
    |
    | Solo se acepta el dominio del frontend (Vue/Vite). Se define vía
    | FRONTEND_URL en el .env, nunca con '*' cuando supports_credentials es true.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
