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

    'error' => "Erro",
    'success' => "Sucesso",
    'try_again' => 'Tente novamente',
    'login' => [
        'invalid_credentials' => 'Usuário ou senha inválidos.',
    ],
    'register' => [
        'send_email' => "Erro ao enviar email",
        'account_created' => "Conta criada. Suas credencias foram enviadas ao seu email. Confira também a caixa de spam.",
        'error' => 'Erro ao criar conta.'
    ],
    'reset' => [
        'email_not_found' => 'E-mail não encontrado',
        'reset_success' => "Senha redefinida. Uma nova senha foi enviada ao seu e-mail",
        'fail' => "A tentativa de redefinição falhou."
    ]
];