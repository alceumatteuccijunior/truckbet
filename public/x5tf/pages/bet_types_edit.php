<?php
// truck-admin-pure-php/pages/bet_types_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$bet_type = null;

// Obter dados do tipo de aposta para edição
if (isset($_GET['id'])) {
    $bet_type_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM bet_types WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $bet_type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bet_type = $result->fetch_assoc();
    $stmt->close();

    if (!$bet_type) {
        $message = "Tipo de aposta não encontrado.";
        $message_type = "error";
    }
} else {
    $message = "ID do tipo de aposta não fornecido.";
    $message_type = "error";
}

// Lógica para atualizar tipo de aposta
if ($_SERVER["REQUEST_METHOD"] == "POST" && $bet_type) {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? NULL;

    if (empty($nome)) {
        $message = "Nome é obrigatório.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("UPDATE bet_types SET nome = ?, descricao = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $descricao, $bet_type['id']);

        if ($stmt->execute()) {
            $message = "Tipo de aposta atualizado com sucesso!";
            $message_type = "success";
            // Atualiza os dados na tela após sucesso
            $bet_type['nome'] = $nome;
            $bet_type['descricao'] = $descricao;
        } else {
            $message = "Erro ao atualizar tipo de aposta: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se o tipo de aposta foi encontrado
if ($bet_type):
?>

<div class="content">
    <h1>Editar Tipo de Aposta: <?php echo htmlspecialchars($bet_type['nome'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="bet_types_edit.php?id=<?php echo htmlspecialchars($bet_type['id']); ?>">
        <div class="form-group">
            <label for="nome">Nome do Tipo de Aposta:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($bet_type['nome'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição (opcional):</label>
            <textarea id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($bet_type['descricao'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn-submit">Atualizar Tipo de Aposta</button>
        <a href="bet_types.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="bet_types.php" class="btn-secondary">Voltar para Lista de Tipos de Aposta</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
