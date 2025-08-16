<?php
// truck-admin-pure-php/pages/withdrawals_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$withdrawal = null; // Inicializa a variável $withdrawal

// Obter dados da solicitação de saque para edição
if (isset($_GET['id'])) {
    $withdrawal_id = intval($_GET['id']);
    $sql = "SELECT w.*, u.name as user_name, u.email as user_email 
            FROM withdrawals w
            JOIN users u ON w.user_id = u.id
            WHERE w.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $withdrawal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $withdrawal = $result->fetch_assoc();
    $stmt->close();

    if (!$withdrawal) {
        $message = "Solicitação de saque não encontrada.";
        $message_type = "error";
    }
} else {
    $message = "ID da solicitação de saque não fornecido.";
    $message_type = "error";
}

// Lógica para atualizar solicitação de saque
if ($_SERVER["REQUEST_METHOD"] == "POST" && $withdrawal) {
    $new_status = $_POST['status'] ?? 'pending';
    $old_status = $withdrawal['status'];
    $processed_at = ($new_status == 'approved' || $new_status == 'rejected') ? date('Y-m-d H:i:s') : NULL;

    if (empty($new_status)) {
        $message = "Status é obrigatório.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("UPDATE withdrawals SET status = ?, processed_at = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $new_status, $processed_at, $withdrawal['id']);

        if ($stmt->execute()) {
            $message = "Solicitação de saque atualizada com sucesso!";
            $message_type = "success";
            // Atualiza os dados na tela
            $withdrawal['status'] = $new_status;
            $withdrawal['processed_at'] = $processed_at;

            // ATENÇÃO: O saldo do usuário JÁ FOI DEDUZIDO no momento da solicitação no frontend.
            // Aqui, apenas atualizamos o status. Não mexemos no saldo novamente para evitar dupla dedução.
            // Se o saque for rejeitado/cancelado, você precisaria de uma lógica para REEMBOLSAR o saldo.
            // Esta lógica de reembolso NÃO está implementada aqui por simplicidade e complexidade.

        } else {
            $message = "Erro ao atualizar solicitação de saque: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se a solicitação foi encontrada
if ($withdrawal):
    $payment_details = json_decode($withdrawal['payment_details'] ?? '{}', true);
    $amount_received_by_user = $payment_details['amount_received_by_user'] ?? 'N/A';
    $fee_applied = $payment_details['fee_applied'] ?? 'N/A';
    $pix_key_type = $payment_details['pix_key_type'] ?? 'N/A';
    $pix_key = $payment_details['pix_key'] ?? 'N/A';
?>

<div class="content">
    <h1>Editar Solicitação de Saque: <?php echo htmlspecialchars($withdrawal['id'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="withdrawals_edit.php?id=<?php echo htmlspecialchars($withdrawal['id']); ?>">
        <div class="form-group">
            <label>Usuário:</label>
            <input type="text" value="<?php echo htmlspecialchars($withdrawal['user_name'] ?? 'N/A'); ?> (<?php echo htmlspecialchars($withdrawal['user_email'] ?? 'N/A'); ?>)" readonly>
        </div>
        <div class="form-group">
            <label>Valor Solicitado:</label>
            <input type="text" value="R$ <?php echo number_format($withdrawal['amount'] ?? 0, 2, ',', '.'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Valor a Receber (Líquido):</label>
            <input type="text" value="R$ <?php echo number_format($amount_received_by_user, 2, ',', '.'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Taxa Aplicada:</label>
            <input type="text" value="R$ <?php echo number_format($fee_applied, 2, ',', '.'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Chave PIX (Tipo: <?php echo htmlspecialchars($pix_key_type); ?>):</label>
            <input type="text" value="<?php echo htmlspecialchars($pix_key); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Data da Solicitação:</label>
            <input type="text" value="<?php echo htmlspecialchars($withdrawal['created_at'] ?? 'N/A'); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="status">Status da Solicitação:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" <?php echo (($withdrawal['status'] ?? '') == 'pending') ? 'selected' : ''; ?>>Pendente</option>
                <option value="approved" <?php echo (($withdrawal['status'] ?? '') == 'approved') ? 'selected' : ''; ?>>Aprovado</option>
                <option value="rejected" <?php echo (($withdrawal['status'] ?? '') == 'rejected') ? 'selected' : ''; ?>>Rejeitado</option>
                <option value="cancelled" <?php echo (($withdrawal['status'] ?? '') == 'cancelled') ? 'selected' : ''; ?>>Cancelado</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Atualizar Solicitação</button>
        <a href="withdrawals.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="withdrawals.php" class="btn-secondary">Voltar para Lista de Solicitações de Saque</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
