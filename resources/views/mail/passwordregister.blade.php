<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #72415e;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        h1 {
            color: #333;
            font-size: 22px;
            margin: 0;
            text-align: center;
        }

        .content {
            color: #555;
            font-size: 16px;
        }

        .password-box {
            background-color: #f8f9fa;
            border: 2px solid #843c8c;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }

        .password-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            font-weight: bold;
            margin-top: 20px;
        }

        .user-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .password-value {
            font-size: 24px;
            font-weight: bold;
            color: #843c8c;
            font-family: 'Courier New', monospace;
            background-color: white;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            letter-spacing: 2px;
        }

        .user-value {
            font-size: 24px;
            font-weight: bold;
            color: #843c8c;
            font-family: 'Courier New', monospace;
            background-color: white;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            letter-spacing: 2px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }

        .buttondiv {
            text-align: center;
        }

        .button {
            display: inline-block; 
            padding: 15px 30px; 
            background-color: #843c8c; 
            color: white!important; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: bold;
            align-self: center;
        }

        .warning {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            color: #721c24;
        }

        .logo-header {
            width: 400px;
            height: auto;
            text-align: center;
        }

        .logo-footer {
            width: 300px;
            height: auto;
        }

        .logos {
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        @media (max-width: 600px) {
            .email-container {
                padding: 20px;
                margin: 10px;
            }
            
            .password-value {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img class="logo-header"
            src="https://educaar.ceavi.udesc.br/images/LOGO_HORIZONTAL.png" alt="GameLAB">
        </div>

        <div class="content">
            <h1>Conta criada com sucesso!</h1>
            <p>Olá {{$user}},</p>
            <p>Sua senha foi gerada com sucesso. Use as informações abaixo para acessar sua conta:</p>

            <div class="password-box">
                <div class="user-label">SEU USUÁRIO:</div>
                <div class="user-value">{{$user}}</div>

                <div class="password-label">SUA SENHA:</div>
                <div class="password-value">{{$password}}</div>
            </div>

            <div class="buttondiv">
                <a href="https://educaar.ceavi.udesc.br" class="button">
                    Acessar o EducaAR
                </a>
            </div>

            <div class="warning">
                <strong>Importante:</strong> Por motivos de segurança, não compartilhe essa senha com ninguém.
            </div>

            <p>Atenciosamente,<br>
            <strong>GameLAB</strong></p>
        </div>

        <div class="footer">
            <p>Este é um email automático, não responda a esta mensagem.</p>
            <p>© 2025 GameLAB. Todos os direitos reservados.</p>
        
            <div class="logos">
                <img class="logo-footer"
                src="https://educaar.ceavi.udesc.br/images/GameLAB.png" alt="GameLAB">
                <img class="logo-footer"
                src="https://educaar.ceavi.udesc.br/images/Fapesc.png" alt="GameLAB">
            </div>

        </div>
        
    </div>
</body>
</html>