<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Return Lines
    |--------------------------------------------------------------------------
    |
    | The following lines contain the translations of controllers returns, being
    | they return success or errors. They are separated into categorized groups
    | for their proper functions and views.
    |
    */

    'error' => "Error",
    'success' => 'Éxito',
    'try_again' => "Intentar otra vez",
    'login' => [
        'invalid_credentials' => "Usuario y contraseña incorrectos."
    ],
    'register' => [
        'send_email' => 'Error al enviar correo electrónico',
        'account_created' => "Cuenta creada. Tus credenciales han sido enviadas a tu correo electrónico. También revisa tu carpeta de spam.",
        'error' => "Error al crear cuenta."
    ],
    'reset' => [
        'email_not_found' => 'Correo electrónico no encontrado.',
        'reset_success' => 'Contraseña restabelecida. Se ha enviado una nueva contraseña a su correo electrónico.',
        'fail' => "El intento de reinicio falló."
    ]
];