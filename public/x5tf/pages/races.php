<?php
// truck-admin-pure-php/pages/races.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Lógica para exclusão de corrida
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $race_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM races WHERE id = ?");
    $stmt->bind_param("i", $race_id);
    if ($stmt->execute()) {
        $message = "Corrida excluída com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir corrida: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todas as corridas
$races_result = $conn->query("SELECT * FROM races ORDER BY id DESC");
?>

<div class="content">
    <h1>Gerenciar Corridas</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="races_add.php" class="btn-add">Adicionar Nova Corrida</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Circuito</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($races_result->num_rows > 0): ?>
                    <?php while($race = $races_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($race['id']); ?></td>
                            <td><?php echo htmlspecialchars($race['nome']); ?></td>
                            <td><?php echo htmlspecialchars($race['circuito']); ?></td>
                            <td><?php echo htmlspecialchars($race['cidade']); ?></td>
                            <td><?php echo htmlspecialchars($race['estado']); ?></td>
                            <td><?php echo htmlspecialchars($race['data_hora']); ?></td>
                            <td><?php echo htmlspecialchars($race['status']); ?></td>
                            <td class="action-links">
                                <a href="races_edit.php?id=<?php echo $race['id']; ?>" class="action-buttons edit">Editar</a>
                                <a href="?action=delete&id=<?php echo $race['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir esta corrida?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhuma corrida encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
