<?php
// truck-admin-pure-php/pages/bet_types_add.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

$nome = '';
$descricao = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? NULL;

    if (empty($nome)) {
        $message = "Nome é obrigatório.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO bet_types (nome, descricao, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->bind_param("ss", $nome, $descricao);

        if ($stmt->execute()) {
            $message = "Tipo de aposta adicionado com sucesso!";
            $message_type = "success";
            $nome = $descricao = ''; // Limpa os campos
        } else {
            $message = "Erro ao adicionar tipo de aposta: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div class="content">
    <h1>Adicionar Novo Tipo de Aposta</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="bet_types_add.php">
        <div class="form-group">
            <label for="nome">Nome do Tipo de Aposta:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição (opcional):</label>
            <textarea id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($descricao); ?></textarea>
        </div>
        <button type="submit" class="btn-submit">Adicionar Tipo de Aposta</button>
        <a href="bet_types.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
