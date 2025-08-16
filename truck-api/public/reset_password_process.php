    <?php
    // truck-api/public/reset_password_process.php
    // ESTE ARQUIVO É UMA ABORDAGEM SIMPLIFICADA E INSEGURA PARA RECUPERAÇÃO DE SENHA.
    // NUNCA UTILIZE EM PRODUÇÃO SEM AJUSTES DE SEGURANÇA ADEQUADOS (CSRF, LIMITES DE TENTATIVAS).

    require_once 'db_config.php'; // Inclui as configurações de conexão com o banco de dados

    header('Content-Type: application/json'); // Retorna JSON
    header('Access-Control-Allow-Origin: *'); // Permite requisições de qualquer origem (AJUSTAR PARA PRODUÇÃO)
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $code = $data['code'] ?? '';
        $password = $data['password'] ?? '';
        $password_confirmation = $data['password_confirmation'] ?? '';

        if (!$email || !$code || !$password || !$password_confirmation) {
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        if ($password !== $password_confirmation) {
            echo json_encode(['success' => false, 'message' => 'A confirmação de senha não corresponde.']);
            exit;
        }

        if (strlen($password) < 8) { // Exemplo de validação de senha mínima
            echo json_encode(['success' => false, 'message' => 'A senha deve ter pelo menos 8 caracteres.']);
            exit;
        }

        try {
            // 1. Verificar se o código de recuperação é válido e não expirou
            $stmt_check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND password_reset_code = ? AND password_reset_expires_at > NOW()");
            $stmt_check->execute([$email, $code]);
            $user = $stmt_check->fetch();

            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'Código de recuperação inválido ou expirado.']);
                exit;
            }

            // 2. Redefinir a senha do usuário
            // IMPORTANTE: Laravel usa bcrypt(). PHP puro usa password_hash() para segurança.
            // Se as senhas existentes foram geradas por bcrypt(), use password_hash() aqui.
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Usando BCRYPT para compatibilidade

            $stmt_update = $pdo->prepare("UPDATE users SET password = ?, password_reset_code = NULL, password_reset_expires_at = NULL WHERE id = ?");
            $stmt_update->execute([$hashed_password, $user['id']]);

            echo json_encode(['success' => true, 'message' => 'Senha redefinida com sucesso! Você já pode fazer login.']);

        } catch (PDOException $e) {
            error_log("Erro PDO em reset_password_process.php: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ocorreu um erro no servidor.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
    }
    ?>
    