<?php
// truck-admin-pure-php/pages/user_apostas_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$aposta = null; // Inicializa a variável $aposta

// Obter dados da aposta para edição
if (isset($_GET['id'])) {
    $aposta_id = intval($_GET['id']);
    $sql = "SELECT ua.*, u.name as user_name, r.nome as race_name, d.nome as driver_name, o.valor_odd 
            FROM user_apostas ua
            JOIN users u ON ua.user_id = u.id
            JOIN bets b ON ua.bet_id = b.id
            JOIN races r ON b.race_id = r.id
            JOIN race_participants rp ON b.race_participant_id = rp.id
            JOIN drivers d ON rp.driver_id = d.id
            JOIN odds o ON b.odd_id = o.id
            WHERE ua.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $aposta_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $aposta = $result->fetch_assoc();
    $stmt->close();

    if (!$aposta) {
        $message = "Aposta de usuário não encontrada.";
        $message_type = "error";
    }
} else {
    $message = "ID da aposta de usuário não fornecido.";
    $message_type = "error";
}

// Lógica para atualizar aposta
if ($_SERVER["REQUEST_METHOD"] == "POST" && $aposta) {
    $status = $_POST['status'] ?? 'pendente';
    $old_status = $aposta['status'];

    if (empty($status)) {
        $message = "Status é obrigatório.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("UPDATE user_apostas SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $aposta['id']);

        if ($stmt->execute()) {
            $message = "Aposta atualizada com sucesso!";
            $message_type = "success";
            // Atualiza os dados na tela
            $aposta['status'] = $status;

            // Lógica para ajustar saldo se o status mudar para 'ganha' ou 'perdida'
            // ATENÇÃO: Isso precisa de uma lógica mais robusta para evitar dupla contagem ou perdas em caso de reedição.
            // Idealmente, um sistema de transações mais complexo.
            if ($status == 'ganha' && $old_status != 'ganha') {
                $user_id = $aposta['user_id'];
                $retorno = $aposta['retorno_esperado'];
                $stmt_update_saldo = $conn->prepare("UPDATE users SET saldo = saldo + ? WHERE id = ?");
                $stmt_update_saldo->bind_param("di", $retorno, $user_id);
                if ($stmt_update_saldo->execute()) {
                    $message .= " Saldo do usuário atualizado (ganho).";
                } else {
                    $message .= " Erro ao atualizar saldo do usuário: " . $stmt_update_saldo->error;
                    $message_type = "error";
                }
                $stmt_update_saldo->close();
            } 
            // Se mudou de ganha para outro status, remover o ganho? (Complexo, não implementado aqui)
            // Se mudou para 'perdida', o saldo já foi subtraído na aposta.
            // Para 'cancelada', o saldo original da aposta deveria ser devolvido (complexo, não implementado aqui)


        } else {
            $message = "Erro ao atualizar aposta: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se a aposta foi encontrada
if ($aposta):
?>

<div class="content">
    <h1>Editar Aposta de Usuário: <?php echo htmlspecialchars($aposta['id'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="user_apostas_edit.php?id=<?php echo htmlspecialchars($aposta['id']); ?>">
        <div class="form-group">
            <label>Usuário:</label>
            <input type="text" value="<?php echo htmlspecialchars($aposta['user_name'] ?? 'N/A'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Corrida:</label>
            <input type="text" value="<?php echo htmlspecialchars($aposta['race_name'] ?? 'N/A'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Piloto Apostado:</label>
            <input type="text" value="<?php echo htmlspecialchars($aposta['driver_name'] ?? 'N/A'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Valor Apostado:</label>
            <input type="text" value="R$ <?php echo number_format($aposta['valor_apostado'] ?? 0, 2, ',', '.'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Odd Usada:</label>
            <input type="text" value="x<?php echo number_format($aposta['odd_usada'] ?? 0, 2, '.', ''); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Retorno Esperado:</label>
            <input type="text" value="R$ <?php echo number_format($aposta['retorno_esperado'] ?? 0, 2, ',', '.'); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="status">Status da Aposta:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pendente" <?php echo (($aposta['status'] ?? '') == 'pendente') ? 'selected' : ''; ?>>Pendente</option>
                <option value="ganha" <?php echo (($aposta['status'] ?? '') == 'ganha') ? 'selected' : ''; ?>>Ganha</option>
                <option value="perdida" <?php echo (($aposta['status'] ?? '') == 'perdida') ? 'selected' : ''; ?>>Perdida</option>
                <option value="cancelada" <?php echo (($aposta['status'] ?? '') == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Atualizar Aposta</button>
        <a href="user_apostas.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="user_apostas.php" class="btn-secondary">Voltar para Lista de Apostas de Usuários</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
