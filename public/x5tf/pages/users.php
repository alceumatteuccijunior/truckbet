<?php
// truck-admin-pure-php/pages/users.php
require_once __DIR__ . '/../config/db_connect.php'; // Inclui a conexão com o banco
include __DIR__ . '/../includes/header.php'; // Inclui o cabeçalho do painel

$message = '';
$message_type = '';

// Lógica para exclusão de usuário
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $message = "Usuário excluído com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir usuário: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todos os usuários
$users_result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="content">
    <h1>Gerenciar Usuários</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="users_add.php" class="btn-add">Adicionar Novo Usuário</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>CPF</th>
                    <th>Saldo</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users_result->num_rows > 0): ?>
                    <?php while($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['cpf'] ?? 'N/A'); ?></td>
                            <td>R$ <?php echo number_format($user['saldo'] ?? 0, 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($user['status'] == 1 ? 'Ativo' : 'Inativo'); ?></td>
                            <td><?php echo htmlspecialchars($user['role'] ?? 'N/A'); ?></td>
                            <td class="action-links">
                                <a href="users_edit.php?id=<?php echo $user['id']; ?>" class="action-buttons edit">Editar</a>
                                <a href="?action=delete&id=<?php echo $user['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; // Inclui o rodapé do painel ?>
