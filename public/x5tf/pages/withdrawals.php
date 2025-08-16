<?php
// truck-admin-pure-php/pages/withdrawals.php
require_once __DIR__ . '/../config/db_connect.php'; // Inclui a conexão com o banco
include __DIR__ . '/../includes/header.php'; // Inclui o cabeçalho do painel

$message = '';
$message_type = '';

// Lógica para exclusão de solicitação de saque (se for permitido)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $withdrawal_id = intval($_GET['id']);
    // ATENÇÃO: Excluir um saque pode ter implicações financeiras.
    // Em um sistema real, você deve ter um processo de estorno de saldo e auditoria para isso.
    $stmt = $conn->prepare("DELETE FROM withdrawals WHERE id = ?");
    $stmt->bind_param("i", $withdrawal_id);
    if ($stmt->execute()) {
        $message = "Solicitação de saque excluída com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir solicitação de saque: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todas as solicitações de saque com nome do usuário
$sql = "SELECT w.*, u.name as user_name 
        FROM withdrawals w
        JOIN users u ON w.user_id = u.id
        ORDER BY w.id DESC";
$withdrawals_result = $conn->query($sql);
?>

<div class="content">
    <h1>Gerenciar Solicitações de Saque</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Não há botão 'Adicionar Novo' pois saques são solicitados pelo usuário no frontend -->

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Valor Solicitado (R$)</th>
                    <th>Valor a Receber (R$)</th>
                    <th>Taxa (R$)</th>
                    <th>Chave PIX</th>
                    <th>Status</th>
                    <th>Data Solicitação</th>
                    <th>Data Processamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($withdrawals_result->num_rows > 0): ?>
                    <?php while($withdrawal = $withdrawals_result->fetch_assoc()): 
                        $payment_details = json_decode($withdrawal['payment_details'] ?? '{}', true);
                        $amount_received = $payment_details['amount_received_by_user'] ?? 'N/A';
                        $fee_applied = $payment_details['fee_applied'] ?? 'N/A';
                        $pix_key = $payment_details['pix_key'] ?? 'N/A';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($withdrawal['id']); ?></td>
                            <td><?php echo htmlspecialchars($withdrawal['user_name']); ?></td>
                            <td>R$ <?php echo number_format($withdrawal['amount'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($amount_received, 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($fee_applied, 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($pix_key); ?></td>
                            <td><?php echo htmlspecialchars($withdrawal['status']); ?></td>
                            <td><?php echo htmlspecialchars($withdrawal['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($withdrawal['processed_at'] ?? 'Pendente'); ?></td>
                            <td class="action-links">
                                <a href="withdrawals_edit.php?id=<?php echo $withdrawal['id']; ?>" class="action-buttons edit">Editar Status</a>
                                <a href="?action=delete&id=<?php echo $withdrawal['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir esta solicitação?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">Nenhuma solicitação de saque encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
