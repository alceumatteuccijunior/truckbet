<?php
// truck-admin-pure-php/pages/user_apostas.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Lógica para exclusão de aposta de usuário
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $aposta_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM user_apostas WHERE id = ?");
    $stmt->bind_param("i", $aposta_id);
    if ($stmt->execute()) {
        $message = "Aposta de usuário excluída com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir aposta de usuário: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todas as apostas de usuários com nomes relacionados
$sql = "SELECT ua.id, u.name as user_name, r.nome as race_name, d.nome as driver_name, 
               ua.valor_apostado, ua.odd_usada, ua.retorno_esperado, ua.status, ua.created_at
        FROM user_apostas ua
        JOIN users u ON ua.user_id = u.id
        JOIN bets b ON ua.bet_id = b.id
        JOIN races r ON b.race_id = r.id
        JOIN race_participants rp ON b.race_participant_id = rp.id
        JOIN drivers d ON rp.driver_id = d.id
        ORDER BY ua.id DESC";
$user_apostas_result = $conn->query($sql);
?>

<div class="content">
    <h1>Gerenciar Apostas de Usuários</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Não há botão 'Adicionar' pois apostas são feitas pelo frontend -->

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Corrida</th>
                    <th>Piloto Apostado</th>
                    <th>Valor</th>
                    <th>Odd Usada</th>
                    <th>Retorno Esperado</th>
                    <th>Status</th>
                    <th>Data Aposta</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($user_apostas_result->num_rows > 0): ?>
                    <?php while($aposta = $user_apostas_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aposta['id']); ?></td>
                            <td><?php echo htmlspecialchars($aposta['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($aposta['race_name']); ?></td>
                            <td><?php echo htmlspecialchars($aposta['driver_name']); ?></td>
                            <td>R$ <?php echo number_format($aposta['valor_apostado'], 2, ',', '.'); ?></td>
                            <td>x<?php echo number_format($aposta['odd_usada'], 2, '.', ''); ?></td>
                            <td>R$ <?php echo number_format($aposta['retorno_esperado'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($aposta['status']); ?></td>
                            <td><?php echo htmlspecialchars($aposta['created_at']); ?></td>
                            <td class="action-links">
                                <a href="user_apostas_edit.php?id=<?php echo $aposta['id']; ?>" class="action-buttons edit">Editar Status</a>
                                <a href="?action=delete&id=<?php echo $aposta['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir esta aposta?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">Nenhuma aposta de usuário encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
