<?php
// truck-admin-pure-php/pages/users_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$user = null; // Inicializa a variável $user

// Obter dados do usuário para edição
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        $message = "Usuário não encontrado.";
        $message_type = "error";
    }
} else {
    $message = "ID do usuário não fornecido.";
    $message_type = "error";
}

// Lógica para atualizar usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && $user) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $cpf = $_POST['cpf'] ?? NULL;
    $saldo = floatval($_POST['saldo'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    $role = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? ''; // Senha opcional
    $update_password = false;

    // Validação básica
    if (empty($name) || empty($email)) {
        $message = "Nome e Email são obrigatórios.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email inválido.";
        $message_type = "error";
    } else {
        $sql = "UPDATE users SET name = ?, email = ?, cpf = ?, saldo = ?, status = ?, role = ?, updated_at = NOW()";
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql .= ", password = ?";
            $update_password = true;
        }
        $sql .= " WHERE id = ?";
        
        $stmt = $conn->prepare($sql);

        if ($update_password) {
            $stmt->bind_param("ssdisssi", $name, $email, $cpf, $saldo, $status, $role, $hashed_password, $user['id']);
        } else {
            $stmt->bind_param("ssdissi", $name, $email, $cpf, $saldo, $status, $role, $user['id']);
        }

        if ($stmt->execute()) {
            $message = "Usuário atualizado com sucesso!";
            $message_type = "success";
            // Atualiza os dados do usuário na tela após sucesso
            $user['name'] = $name;
            $user['email'] = $email;
            $user['cpf'] = $cpf;
            $user['saldo'] = $saldo;
            $user['status'] = $status;
            $user['role'] = $role;
        } else {
            $message = "Erro ao atualizar usuário: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se o usuário foi encontrado
if ($user):
?>

<div class="content">
    <h1>Editar Usuário: <?php echo htmlspecialchars($user['name'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="users_edit.php?id=<?php echo htmlspecialchars($user['id']); ?>">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Nova Senha (deixe em branco para não alterar):</label>
            <input type="password" id="password" name="password">
            <small style="color: #A0A0A0;">Deixe em branco para não alterar a senha.</small>
        </div>
        <div class="form-group">
            <label for="cpf">CPF (apenas números):</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($user['cpf'] ?? ''); ?>" maxlength="11">
        </div>
        <div class="form-group">
            <label for="saldo">Saldo:</label>
            <input type="number" id="saldo" name="saldo" step="0.01" value="<?php echo htmlspecialchars($user['saldo'] ?? 0); ?>">
        </div>
        <div class="form-group">
            <input type="checkbox" id="status" name="status" <?php echo ($user['status'] ?? 0) == 1 ? 'checked' : ''; ?>>
            <label for="status" style="display: inline-block; margin-left: 10px;">Ativo</label>
        </div>
        <div class="form-group">
            <label for="role">Função (Role):</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" <?php echo (($user['role'] ?? '') == 'user') ? 'selected' : ''; ?>>Usuário</option>
                <option value="admin" <?php echo (($user['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Atualizar Usuário</button>
        <a href="users.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="users.php" class="btn-secondary">Voltar para Lista de Usuários</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
