<?php
// truck-admin-pure-php/pages/drivers_add.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

$nome = '';
$categoria = '';
$marca = '';
$numero_camiao = '';
$cidade = '';
$status = 'ativo';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $stmt = $conn->prepare("INSERT INTO drivers (nome, categoria, marca, numero_camiao, cidade, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssssss", $nome, $categoria, $marca, $numero_camiao, $cidade, $status);

        if ($stmt->execute()) {
            $message = "Piloto adicionado com sucesso!";
            $message_type = "success";
            $nome = $categoria = $marca = $numero_camiao = $cidade = '';
            $status = 'ativo';
        } else {
            $message = "Erro ao adicionar piloto: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div class="content">
    <h1>Adicionar Novo Piloto</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="drivers_add.php">
        <div class="form-group">
            <label for="nome">Nome do Piloto:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select name="categoria" id="categoria" class="form-control" required>
                <option value="FT" <?php echo ($categoria == 'FT') ? 'selected' : ''; ?>>FT</option>
                <option value="GT" <?php echo ($categoria == 'GT') ? 'selected' : ''; ?>>GT</option>
            </select>
        </div>
        <div class="form-group">
            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($marca); ?>" required>
        </div>
        <div class="form-group">
            <label for="numero_camiao">Número do Camião:</label>
            <input type="text" id="numero_camiao" name="numero_camiao" value="<?php echo htmlspecialchars($numero_camiao); ?>" required>
        </div>
        <div class="form-group">
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="ativo" <?php echo ($status == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                <option value="inativo" <?php echo ($status == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Adicionar Piloto</button>
        <a href="drivers.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
