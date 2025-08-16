<?php
// truck-admin-pure-php/pages/odds_add.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

$race_participant_id = '';
$bet_type_id = '';
$valor_odd = '';

// Obter participantes de corrida e tipos de aposta para os dropdowns
$race_participants = $conn->query("
    SELECT rp.id, r.nome as race_name, d.nome as driver_name, d.categoria as driver_category
    FROM race_participants rp
    JOIN races r ON rp.race_id = r.id
    JOIN drivers d ON rp.driver_id = d.id
    ORDER BY r.nome ASC, d.nome ASC
");
$bet_types = $conn->query("SELECT id, nome FROM bet_types ORDER BY nome ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $stmt = $conn->prepare("INSERT INTO odds (race_participant_id, bet_type_id, valor_odd, data_atualizacao, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW(), NOW())");
        $stmt->bind_param("iid", $race_participant_id, $bet_type_id, $valor_odd);

        if ($stmt->execute()) {
            $message = "Odd adicionada com sucesso!";
            $message_type = "success";
            $race_participant_id = $bet_type_id = $valor_odd = ''; // Limpa os campos
        } else {
            $message = "Erro ao adicionar odd: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div class="content">
    <h1>Adicionar Nova Odd</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="odds_add.php">
        <div class="form-group">
            <label for="race_participant_id">Participante da Corrida:</label>
            <select name="race_participant_id" id="race_participant_id" class="form-control" required>
                <option value="">Selecione um Participante</option>
                <?php while($rp = $race_participants->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($rp['id']); ?>" <?php echo ($rp['id'] == $race_participant_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rp['race_name']); ?> - <?php echo htmlspecialchars($rp['driver_name']); ?> (<?php echo htmlspecialchars($rp['driver_category']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="bet_type_id">Tipo de Aposta:</label>
            <select name="bet_type_id" id="bet_type_id" class="form-control" required>
                <option value="">Selecione o Tipo de Aposta</option>
                <?php while($bt = $bet_types->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($bt['id']); ?>" <?php echo ($bt['id'] == $bet_type_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($bt['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="valor_odd">Valor da Odd:</label>
            <input type="number" id="valor_odd" name="valor_odd" step="0.01" min="0.01" value="<?php echo htmlspecialchars($valor_odd); ?>" required>
        </div>
        <button type="submit" class="btn-submit">Adicionar Odd</button>
        <a href="odds.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
