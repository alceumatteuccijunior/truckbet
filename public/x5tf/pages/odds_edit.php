<?php
// truck-admin-pure-php/pages/odds_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$odd = null;

// Obter dados da odd para edição
if (isset($_GET['id'])) {
    $odd_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM odds WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $odd_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $odd = $result->fetch_assoc();
    $stmt->close();

    if (!$odd) {
        $message = "Odd não encontrada.";
        $message_type = "error";
    }
} else {
    $message = "ID da odd não fornecido.";
    $message_type = "error";
}

// Obter participantes de corrida e tipos de aposta para os dropdowns (mesma lógica do add)
$race_participants_query = $conn->query("
    SELECT rp.id, r.nome as race_name, d.nome as driver_name, d.categoria as driver_category
    FROM race_participants rp
    JOIN races r ON rp.race_id = r.id
    JOIN drivers d ON rp.driver_id = d.id
    ORDER BY r.nome ASC, d.nome ASC
");
$bet_types_query = $conn->query("SELECT id, nome FROM bet_types ORDER BY nome ASC");

// Lógica para atualizar odd
if ($_SERVER["REQUEST_METHOD"] == "POST" && $odd) {
    $race_participant_id = intval($_POST['race_participant_id'] ?? 0);
    $bet_type_id = intval($_POST['bet_type_id'] ?? 0);
    $valor_odd = floatval($_POST['valor_odd'] ?? 0);

    if (empty($race_participant_id) || empty($bet_type_id) || empty($valor_odd)) {
        $message = "Todos os campos são obrigatórios.";
        $message_type = "error";
    } elseif ($valor_odd <= 0) {
        $message = "Valor da Odd deve ser positivo.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("UPDATE odds SET race_participant_id = ?, bet_type_id = ?, valor_odd = ?, data_atualizacao = NOW(), updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("iidi", $race_participant_id, $bet_type_id, $valor_odd, $odd['id']);

        if ($stmt->execute()) {
            $message = "Odd atualizada com sucesso!";
            $message_type = "success";
            // Atualiza os dados na tela
            $odd['race_participant_id'] = $race_participant_id;
            $odd['bet_type_id'] = $bet_type_id;
            $odd['valor_odd'] = $valor_odd;
        } else {
            $message = "Erro ao atualizar odd: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se a odd foi encontrada
if ($odd):
?>

<div class="content">
    <h1>Editar Odd: <?php echo htmlspecialchars($odd['id'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="odds_edit.php?id=<?php echo htmlspecialchars($odd['id']); ?>">
        <div class="form-group">
            <label for="race_participant_id">Participante da Corrida:</label>
            <select name="race_participant_id" id="race_participant_id" class="form-control" required>
                <option value="">Selecione um Participante</option>
                <?php $race_participants_query->data_seek(0); ?>
                <?php while($rp = $race_participants_query->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($rp['id']); ?>" <?php echo ($rp['id'] == ($odd['race_participant_id'] ?? '')) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rp['race_name']); ?> - <?php echo htmlspecialchars($rp['driver_name']); ?> (<?php echo htmlspecialchars($rp['driver_category']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="bet_type_id">Tipo de Aposta:</label>
            <select name="bet_type_id" id="bet_type_id" class="form-control" required>
                <option value="">Selecione o Tipo de Aposta</option>
                <?php $bet_types_query->data_seek(0); ?>
                <?php while($bt = $bet_types_query->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($bt['id']); ?>" <?php echo ($bt['id'] == ($odd['bet_type_id'] ?? '')) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($bt['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="valor_odd">Valor da Odd:</label>
            <input type="number" id="valor_odd" name="valor_odd" step="0.01" min="0.01" value="<?php echo htmlspecialchars($odd['valor_odd'] ?? ''); ?>" required>
        </div>
        <button type="submit" class="btn-submit">Atualizar Odd</button>
        <a href="odds.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="odds.php" class="btn-secondary">Voltar para Lista de Odds</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
