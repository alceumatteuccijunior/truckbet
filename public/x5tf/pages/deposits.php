<?php
// truck-admin-pure-php/pages/deposits.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Lógica para exclusão de depósito (se for permitido, mas geralmente não é para depósitos financeiros)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $deposit_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM deposits WHERE id = ?");
    $stmt->bind_param("i", $deposit_id);
    if ($stmt->execute()) {
        $message = "Depósito excluído com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir depósito: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todos os depósitos com nome do usuário
$sql = "SELECT d.*, u.name as user_name 
        FROM deposits d
        JOIN users u ON d.user_id = u.id
        ORDER BY d.id DESC";
$deposits_result = $conn->query($sql);
?>

<div class="content">
    <h1>Gerenciar Depósitos</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Não há botão 'Adicionar' pois depósitos são feitos pelo frontend ou webhook -->

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Transação PushinPay ID</th>
                    <th>Valor (R$)</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($deposits_result->num_rows > 0): ?>
                    <?php while($deposit = $deposits_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($deposit['id']); ?></td>
                            <td><?php echo htmlspecialchars($deposit['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($deposit['pushinpay_transaction_id']); ?></td>
                            <td>R$ <?php echo number_format($deposit['amount'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($deposit['status']); ?></td>
                            <td><?php echo htmlspecialchars($deposit['created_at']); ?></td>
                            <td class="action-links">
                                <!-- Edição de depósito geralmente não é permitida ou é muito restrita para evitar fraudes -->
                                <!-- Aqui, apenas a opção de excluir é mantida, mas pode ser removida se não for desejável -->
                                <a href="?action=delete&id=<?php echo $deposit['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir este depósito? Esta ação não pode ser desfeita e não reverte o saldo do usuário.');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Nenhum depósito encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
