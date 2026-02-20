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

    'error' => 'Error',
    'success' => 'Success',
    'try_again' => 'Try again later',
    'login' => [
        'invalid_credentials' => 'Invalid username and password',
    ],
    'register' => [
        'send_email' => 'Error sending email',
        'account_created' => 'Created account. Your credentials have been sent to your email. Also check your spam folder.',
        'error' => 'Error creating account.'
    ],
    'reset' => [
        'email_not_found' => 'E-mail not found',
        'reset_success' => "Password changed. A new password was send to your e-mail address",
        'fail' => "Attempt of change password failed."
    ]
];