<?php
// truck-admin-pure-php/pages/races_add.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

// Valores padrão para o formulário (para limpar após o sucesso)
$nome = '';
$circuito = '';
$cidade = '';
$estado = '';
$data_hora = '';
$status = 'aberta';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $circuito = $_POST['circuito'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $data_hora = $_POST['data_hora'] ?? '';
    $status = $_POST['status'] ?? 'aberta';

    // Validação básica
    if (empty($nome) || empty($circuito) || empty($cidade) || empty($estado) || empty($data_hora)) {
        $message = "Todos os campos são obrigatórios.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO races (nome, circuito, cidade, estado, data_hora, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssssss", $nome, $circuito, $cidade, $estado, $data_hora, $status);

        if ($stmt->execute()) {
            $message = "Corrida adicionada com sucesso!";
            $message_type = "success";
            // Limpa os campos do formulário após o sucesso
            $nome = $circuito = $cidade = $estado = $data_hora = '';
            $status = 'aberta';
        } else {
            $message = "Erro ao adicionar corrida: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div class="content">
    <h1>Adicionar Nova Corrida</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="races_add.php">
        <div class="form-group">
            <label for="nome">Nome da Corrida:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="form-group">
            <label for="circuito">Circuito:</label>
            <input type="text" id="circuito" name="circuito" value="<?php echo htmlspecialchars($circuito); ?>" required>
        </div>
        <div class="form-group">
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>" required>
        </div>
        <div class="form-group">
            <label for="estado">Estado (UF):</label>
            <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($estado); ?>" required>
        </div>
        <div class="form-group">
            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" id="data_hora" name="data_hora" value="<?php echo htmlspecialchars($data_hora); ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="aberta" <?php echo ($status == 'aberta') ? 'selected' : ''; ?>>Aberta</option>
                <option value="fechada" <?php echo ($status == 'fechada') ? 'selected' : ''; ?>>Fechada</option>
                <option value="cancelada" <?php echo ($status == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Adicionar Corrida</button>
        <a href="races.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
