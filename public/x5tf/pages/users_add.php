<?php
// truck-admin-pure-php/pages/users_add.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $cpf = $_POST['cpf'] ?? NULL;
    $saldo = floatval($_POST['saldo'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0; // Checkbox
    $role = $_POST['role'] ?? 'user';

    // Validação básica
    if (empty($name) || empty($email) || empty($password)) {
        $message = "Nome, Email e Senha são obrigatórios.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email inválido.";
        $message_type = "error";
    } else {
        // Hash da senha (compatível com bcrypt do Laravel)
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, cpf, saldo, status, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("sssdiss", $name, $email, $hashed_password, $cpf, $saldo, $status, $role);

        if ($stmt->execute()) {
            $message = "Usuário adicionado com sucesso!";
            $message_type = "success";
            // Limpa os campos do formulário após o sucesso
            $name = $email = $password = $cpf = '';
            $saldo = 0;
            $status = 1;
            $role = 'user';
        } else {
            $message = "Erro ao adicionar usuário: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div class="content">
    <h1>Adicionar Novo Usuário</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="users_add.php">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="cpf">CPF (apenas números):</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf ?? ''); ?>" maxlength="11">
        </div>
        <div class="form-group">
            <label for="saldo">Saldo Inicial:</label>
            <input type="number" id="saldo" name="saldo" step="0.01" min="0" value="<?php echo htmlspecialchars($saldo ?? 0); ?>">
        </div>
        <div class="form-group">
            <input type="checkbox" id="status" name="status" <?php echo ($status ?? 1) ? 'checked' : ''; ?>>
            <label for="status" style="display: inline-block; margin-left: 10px;">Ativo</label>
        </div>
        <div class="form-group">
            <label for="role">Função (Role):</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" <?php echo (($role ?? 'user') == 'user') ? 'selected' : ''; ?>>Usuário</option>
                <option value="admin" <?php echo (($role ?? 'user') == 'admin') ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Adicionar Usuário</button>
        <a href="users.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
