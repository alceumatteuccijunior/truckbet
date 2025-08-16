<?php
// truck-admin-pure-php/pages/bets.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Lógica para exclusão de opção de aposta
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $bet_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM bets WHERE id = ?");
    $stmt->bind_param("i", $bet_id);
    if ($stmt->execute()) {
        $message = "Opção de aposta excluída com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir opção de aposta: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todas as opções de aposta com nomes relacionados
$sql = "SELECT b.id, r.nome as race_name, d.nome as driver_name, o.valor_odd, b.status
        FROM bets b
        JOIN races r ON b.race_id = r.id
        JOIN race_participants rp ON b.race_participant_id = rp.id
        JOIN drivers d ON rp.driver_id = d.id
        JOIN odds o ON b.odd_id = o.id
        ORDER BY b.id DESC";
$bets_result = $conn->query($sql);
?>

<div class="content">
    <h1>Gerenciar Opções de Aposta</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="bets_add.php" class="btn-add">Adicionar Nova Opção de Aposta</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Corrida</th>
                    <th>Piloto</th>
                    <th>Odd (x)</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bets_result->num_rows > 0): ?>
                    <?php while($bet = $bets_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bet['id']); ?></td>
                            <td><?php echo htmlspecialchars($bet['race_name']); ?></td>
                            <td><?php echo htmlspecialchars($bet['driver_name']); ?></td>
                            <td>x<?php echo number_format($bet['valor_odd'], 2, '.', ''); ?></td>
                            <td><?php echo htmlspecialchars($bet['status']); ?></td>
                            <td class="action-links">
                                <a href="bets_edit.php?id=<?php echo $bet['id']; ?>" class="action-buttons edit">Editar</a>
                                <a href="?action=delete&id=<?php echo $bet['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir esta opção de aposta?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhuma opção de aposta encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
