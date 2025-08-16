<?php
// truck-api/public/send_recovery_code.php
// ESTE ARQUIVO É UMA ABORDAGEM SIMPLIFICADA E INSEGURA PARA RECUPERAÇÃO DE SENHA.
// NUNCA UTILIZE EM PRODUÇÃO SEM AJUSTES DE SEGURANÇA ADEQUADOS (CSRF, LIMITES DE TENTATIVAS).
// O USO DA FUNÇÃO mail() DO PHP DEPENDE DA CONFIGURAÇÃO DO SEU SERVIDOR.

// Definir o fuso horário padrão (ajuste para o seu fuso horário real se for diferente)
date_default_timezone_set('America/Sao_Paulo'); // Exemplo para São Paulo, Brasil

require_once 'db_config.php'; // Inclui as configurações de conexão com o banco de dados

header('Content-Type: application/json'); // Retorna JSON
header('Access-Control-Allow-Origin: *'); // Permite requisições de qualquer origem (AJUSTAR PARA PRODUÇÃO)
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Por favor, insira um email válido.']);
        exit;
    }

    try {
        // 1. Verificar se o email existe na base de dados
        $stmt_check = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt_check->execute([$email]);
        $user = $stmt_check->fetch();

        if (!$user) {
            // Resposta genérica por segurança
            echo json_encode(['success' => true, 'message' => 'Se o email estiver registado, receberá um código de recuperação.']);
            exit;
        }

        // 2. Gerar o código e guardá-lo no banco
        $codigo_recuperacao = random_int(100000, 999999);
        
        // Define a expiração para 1 hora a partir de agora usando objetos DateTime (mais robusto)
        $now = new DateTime(); // Data e hora atual
        $now->add(new DateInterval('PT1H')); // Adiciona 1 hora
        $expires_at = $now->format('Y-m-d H:i:s'); // Formata para o formato do banco de dados

        // --- LOGS DE DEPURAÇÃO ---
        error_log("SEND RECOVERY: Current server time (PHP): " . date('Y-m-d H:i:s'));
        error_log("SEND RECOVERY: Calculated expires_at: " . $expires_at);
        // --- FIM DOS LOGS DE DEPURAÇÃO ---

        $stmt_update = $pdo->prepare("UPDATE users SET password_reset_code = ?, password_reset_expires_at = ? WHERE id = ?");
        $stmt_update->execute([$codigo_recuperacao, $expires_at, $user['id']]);

        // 3. LÓGICA DE ENVIO DE EMAIL (utilizando a função mail() do PHP)
        // Lembre-se: mail() pode ser pouco confiável e e-mails podem ir para SPAM.
        $destinatario = $email;
        $assunto = 'Recuperação de senha no aplicativo TruckBet';
        
        $mensagem = "
        <html>
        <head>
          <title>{$assunto}</title>
        </head>
        <body>
          <p>Olá, " . htmlspecialchars($user['name']) . "!</p>
          <p>Recebemos um pedido para redefinir a sua senha. Utilize o código abaixo para continuar:</p>
          <h1 style='font-size: 36px; letter-spacing: 5px; text-align: center; color: #FFC107;'>{$codigo_recuperacao}</h1>
          <p>Este código é válido por 1 hora.</p>
          <p>Se não solicitou esta alteração, pode ignorar este email.</p>
          <br>
          <p>Atenciosamente,<br>Equipa TruckBet</p>
        </body>
        </html>
        ";

        $cabecalhos  = 'MIME-Version: 1.0' . "\r\n";
        $cabecalhos .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $cabecalhos .= 'From: TruckBet <noreply@truckbet.vip>' . "\r\n" . // SUBSTITUA COM UM EMAIL REAL
                       'Reply-To: noreply@truckbet.vip' . "\r\n" . // SUBSTITUA COM UM EMAIL REAL
                       'X-Mailer: PHP/' . phpversion();

        $envio_sucesso = mail($destinatario, $assunto, $mensagem, $cabecalhos);
        // ----------------------------------------------------

        if ($envio_sucesso) {
            echo json_encode(['success' => true, 'message' => 'Se o email estiver registado, receberá um código de recuperação.']);
        } else {
            error_log("Falha no envio de email de recuperação para: " . $email);
            echo json_encode(['success' => false, 'message' => 'Não foi possível enviar o email de recuperação. Contacte o suporte.']);
        }

    } catch (PDOException $e) {
        error_log("Erro PDO em send_recovery_code.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Ocorreu um erro no servidor.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}
?>
