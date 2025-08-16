<?php
// truck-admin-pure-php/pages/race_participants.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Lógica para exclusão de participante
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $participant_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM race_participants WHERE id = ?");
    $stmt->bind_param("i", $participant_id);
    if ($stmt->execute()) {
        $message = "Participante excluído com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir participante: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todos os participantes com seus nomes de corrida e piloto
$sql = "SELECT rp.id, r.nome as race_name, d.nome as driver_name, rp.posicao_final, rp.tempo_total 
        FROM race_participants rp
        JOIN races r ON rp.race_id = r.id
        JOIN drivers d ON rp.driver_id = d.id
        ORDER BY rp.id DESC";
$participants_result = $conn->query($sql);
?>

<div class="content">
    <h1>Gerenciar Participantes de Corridas</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="race_participants_add.php" class="btn-add">Adicionar Novo Participante</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Corrida</th>
                    <th>Piloto</th>
                    <th>Posição Final</th>
                    <th>Tempo Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($participants_result->num_rows > 0): ?>
                    <?php while($participant = $participants_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($participant['id']); ?></td>
                            <td><?php echo htmlspecialchars($participant['race_name']); ?></td>
                            <td><?php echo htmlspecialchars($participant['driver_name']); ?></td>
                            <td><?php echo htmlspecialchars($participant['posicao_final'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($participant['tempo_total'] ?? 'N/A'); ?></td>
                            <td class="action-links">
                                <a href="race_participants_edit.php?id=<?php echo $participant['id']; ?>" class="action-buttons edit">Editar</a>
                                <a href="?action=delete&id=<?php echo $participant['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir este participante?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum participante encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
