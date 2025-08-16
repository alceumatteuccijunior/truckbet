<?php
// truck-admin-pure-php/pages/drivers.php
require_once __DIR__ . '/../config/db_connect.php'; // Inclui a conexão com o banco
include __DIR__ . '/../includes/header.php'; // Inclui o cabeçalho do painel

$message = '';
$message_type = '';

// Lógica para exclusão de piloto
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $driver_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM drivers WHERE id = ?");
    $stmt->bind_param("i", $driver_id);
    if ($stmt->execute()) {
        $message = "Piloto excluído com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro ao excluir piloto: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Obter todos os pilotos
$drivers_result = $conn->query("SELECT * FROM drivers ORDER BY id DESC");
?>

<div class="content">
    <h1>Gerenciar Pilotos</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <a href="drivers_add.php" class="btn-add">Adicionar Novo Piloto</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Nº Camião</th>
                    <th>Cidade</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($drivers_result->num_rows > 0): ?>
                    <?php while($driver = $drivers_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($driver['id']); ?></td>
                            <td><?php echo htmlspecialchars($driver['nome']); ?></td>
                            <td><?php echo htmlspecialchars($driver['categoria']); ?></td>
                            <td><?php echo htmlspecialchars($driver['marca']); ?></td>
                            <td><?php echo htmlspecialchars($driver['numero_camiao']); ?></td>
                            <td><?php echo htmlspecialchars($driver['cidade']); ?></td>
                            <td><?php echo htmlspecialchars($driver['status']); ?></td>
                            <td class="action-links">
                                <a href="drivers_edit.php?id=<?php echo $driver['id']; ?>" class="action-buttons edit">Editar</a>
                                <a href="?action=delete&id=<?php echo $driver['id']; ?>" class="action-buttons delete" onclick="return confirm('Tem certeza que deseja excluir este piloto?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhum piloto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
