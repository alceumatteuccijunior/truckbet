<?php
// truck-admin-pure-php/pages/races_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$race = null; // Inicializa a variável $race

// Obter dados da corrida para edição
if (isset($_GET['id'])) {
    $race_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM races WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $race_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $race = $result->fetch_assoc();
    $stmt->close();

    if (!$race) {
        $message = "Corrida não encontrada.";
        $message_type = "error";
    }
} else {
    $message = "ID da corrida não fornecido.";
    $message_type = "error";
}

// Lógica para atualizar corrida
if ($_SERVER["REQUEST_METHOD"] == "POST" && $race) {
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
        $stmt = $conn->prepare("UPDATE races SET nome = ?, circuito = ?, cidade = ?, estado = ?, data_hora = ?, status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssssssi", $nome, $circuito, $cidade, $estado, $data_hora, $status, $race['id']);

        if ($stmt->execute()) {
            $message = "Corrida atualizada com sucesso!";
            $message_type = "success";
            // Atualiza os dados da corrida na tela após sucesso
            $race['nome'] = $nome;
            $race['circuito'] = $circuito;
            $race['cidade'] = $cidade;
            $race['estado'] = $estado;
            $race['data_hora'] = $data_hora;
            $race['status'] = $status;
        } else {
            $message = "Erro ao atualizar corrida: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se a corrida foi encontrada
if ($race):
    // Formata a data/hora para o formato datetime-local para o input HTML
    $formatted_data_hora = date('Y-m-d\TH:i', strtotime($race['data_hora']));
?>

<div class="content">
    <h1>Editar Corrida: <?php echo htmlspecialchars($race['nome'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="races_edit.php?id=<?php echo htmlspecialchars($race['id']); ?>">
        <div class="form-group">
            <label for="nome">Nome da Corrida:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($race['nome'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="circuito">Circuito:</label>
            <input type="text" id="circuito" name="circuito" value="<?php echo htmlspecialchars($race['circuito'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($race['cidade'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="estado">Estado (UF):</label>
            <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($race['estado'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="data_hora">Data e Hora:</label>
            <input type="datetime-local" id="data_hora" name="data_hora" value="<?php echo htmlspecialchars($formatted_data_hora); ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="aberta" <?php echo (($race['status'] ?? '') == 'aberta') ? 'selected' : ''; ?>>Aberta</option>
                <option value="fechada" <?php echo (($race['status'] ?? '') == 'fechada') ? 'selected' : ''; ?>>Fechada</option>
                <option value="cancelada" <?php echo (($race['status'] ?? '') == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Atualizar Corrida</button>
        <a href="races.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="races.php" class="btn-secondary">Voltar para Lista de Corridas</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
