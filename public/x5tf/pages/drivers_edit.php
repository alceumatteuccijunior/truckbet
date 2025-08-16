<?php
// truck-admin-pure-php/pages/drivers_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$driver = null;

// Obter dados do piloto para edição
if (isset($_GET['id'])) {
    $driver_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM drivers WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $driver = $result->fetch_assoc();
    $stmt->close();

    if (!$driver) {
        $message = "Piloto não encontrado.";
        $message_type = "error";
    }
} else {
    $message = "ID do piloto não fornecido.";
    $message_type = "error";
}

// Lógica para atualizar piloto
if ($_SERVER["REQUEST_METHOD"] == "POST" && $driver) {
    $nome = $_POST['nome'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $numero_camiao = $_POST['numero_camiao'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $status = $_POST['status'] ?? 'ativo';

    if (empty($nome) || empty($categoria) || empty($marca) || empty($numero_camiao) || empty($cidade) || empty($status)) {
        $message = "Todos os campos são obrigatórios.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("UPDATE drivers SET nome = ?, categoria = ?, marca = ?, numero_camiao = ?, cidade = ?, status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssssssi", $nome, $categoria, $marca, $numero_camiao, $cidade, $status, $driver['id']);

        if ($stmt->execute()) {
            $message = "Piloto atualizado com sucesso!";
            $message_type = "success";
            // Atualiza os dados do piloto na tela
            $driver['nome'] = $nome;
            $driver['categoria'] = $categoria;
            $driver['marca'] = $marca;
            $driver['numero_camiao'] = $numero_camiao;
            $driver['cidade'] = $cidade;
            $driver['status'] = $status;
        } else {
            $message = "Erro ao atualizar piloto: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se o piloto foi encontrado
if ($driver):
?>

<div class="content">
    <h1>Editar Piloto: <?php echo htmlspecialchars($driver['nome'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="drivers_edit.php?id=<?php echo htmlspecialchars($driver['id']); ?>">
        <div class="form-group">
            <label for="nome">Nome do Piloto:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($driver['nome'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select name="categoria" id="categoria" class="form-control" required>
                <option value="FT" <?php echo (($driver['categoria'] ?? '') == 'FT') ? 'selected' : ''; ?>>FT</option>
                <option value="GT" <?php echo (($driver['categoria'] ?? '') == 'GT') ? 'selected' : ''; ?>>GT</option>
            </select>
        </div>
        <div class="form-group">
            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($driver['marca'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="numero_camiao">Número do Camião:</label>
            <input type="text" id="numero_camiao" name="numero_camiao" value="<?php echo htmlspecialchars($driver['numero_camiao'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($driver['cidade'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="ativo" <?php echo (($driver['status'] ?? '') == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                <option value="inativo" <?php echo (($driver['status'] ?? '') == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Atualizar Piloto</button>
        <a href="drivers.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="drivers.php" class="btn-secondary">Voltar para Lista de Pilotos</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
