<?php
// truck-admin-pure-php/pages/odds.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Lógica para exclusão de odd
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $odd_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM odds WHERE id = ?");
    $stmt->bind_param("i", $odd_id);
    if ($stmt->execute()) {
        $message = "Odd excluída com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir odd: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todas as odds com nomes de piloto, corrida e tipo de aposta
$sql = "SELECT o.id, d.nome as driver_name, r.nome as race_name, bt.nome as bet_type_name, o.valor_odd, o.data_atualizacao 
        FROM odds o
        JOIN race_participants rp ON o.race_participant_id = rp.id
        JOIN drivers d ON rp.driver_id = d.id
        JOIN races r ON rp.race_id = r.id
        LEFT JOIN bet_types bt ON o.bet_type_id = bt.id
        ORDER BY o.id DESC";
$odds_result = $conn->query($sql);
?>

<div class="content">
    <h1>Gerenciar Odds</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="odds_add.php" class="btn-add">Adicionar Nova Odd</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Piloto</th>
                    <th>Corrida</th>
                    <th>Tipo Aposta</th>
                    <th>Valor Odd</th>
                    <th>Última Atualização</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($odds_result->num_rows > 0): ?>
                    <?php while($odd = $odds_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($odd['id']); ?></td>
                            <td><?php echo htmlspecialchars($odd['driver_name']); ?></td>
                            <td><?php echo htmlspecialchars($odd['race_name']); ?></td>
                            <td><?php echo htmlspecialchars($odd['bet_type_name'] ?? 'N/A'); ?></td>
                            <td>x<?php echo number_format($odd['valor_odd'], 2, '.', ''); ?></td>
                            <td><?php echo htmlspecialchars($odd['data_atualizacao'] ?? 'N/A'); ?></td>
                            <td class="action-links">
                                <a href="odds_edit.php?id=<?php echo $odd['id']; ?>" class="action-buttons edit">Editar</a>
                                <a href="?action=delete&id=<?php echo $odd['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir esta odd?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Nenhuma odd encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
