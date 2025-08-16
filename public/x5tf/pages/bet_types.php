<?php
// truck-admin-pure-php/pages/bet_types.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Lógica para exclusão de tipo de aposta
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $bet_type_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM bet_types WHERE id = ?");
    $stmt->bind_param("i", $bet_type_id);
    if ($stmt->execute()) {
        $message = "Tipo de aposta excluído com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir tipo de aposta: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todos os tipos de aposta
$bet_types_result = $conn->query("SELECT * FROM bet_types ORDER BY id DESC");
?>

<div class="content">
    <h1>Gerenciar Tipos de Aposta</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="bet_types_add.php" class="btn-add">Adicionar Novo Tipo de Aposta</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bet_types_result->num_rows > 0): ?>
                    <?php while($bet_type = $bet_types_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bet_type['id']); ?></td>
                            <td><?php echo htmlspecialchars($bet_type['nome']); ?></td>
                            <td><?php echo htmlspecialchars($bet_type['descricao'] ?? 'N/A'); ?></td>
                            <td class="action-links">
                                <a href="bet_types_edit.php?id=<?php echo $bet_type['id']; ?>" class="action-buttons edit">Editar</a>
                                <a href="?action=delete&id=<?php echo $bet_type['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir este tipo de aposta?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Nenhum tipo de aposta encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
