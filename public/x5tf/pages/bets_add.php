<?php
// truck-admin-pure-php/pages/bets_add.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

$race_id = '';
$race_participant_id = '';
$odd_id = '';
$status = 'aberta';

// Obter dados para dropdowns
$races = $conn->query("SELECT id, nome FROM races ORDER BY nome ASC");
$race_participants = $conn->query("
    SELECT rp.id, r.nome as race_name, d.nome as driver_name, d.categoria as driver_category
    FROM race_participants rp
    JOIN races r ON rp.race_id = r.id
    JOIN drivers d ON rp.driver_id = d.id
    ORDER BY r.nome ASC, d.nome ASC
");
$odds = $conn->query("
    SELECT o.id, o.valor_odd, bt.nome as bet_type_name, d.nome as driver_name, r.nome as race_name
    FROM odds o
    JOIN bet_types bt ON o.bet_type_id = bt.id
    JOIN race_participants rp ON o.race_participant_id = rp.id
    JOIN drivers d ON rp.driver_id = d.id
    JOIN races r ON rp.race_id = r.id
    ORDER BY r.nome ASC, d.nome ASC, o.valor_odd ASC
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $race_id = intval($_POST['race_id'] ?? 0);
    $race_participant_id = intval($_POST['race_participant_id'] ?? 0);
    $odd_id = intval($_POST['odd_id'] ?? 0);
    $status = $_POST['status'] ?? 'aberta';

    if (empty($race_id) || empty($race_participant_id) || empty($odd_id) || empty($status)) {
        $message = "Todos os campos são obrigatórios.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO bets (race_id, race_participant_id, odd_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("iiis", $race_id, $race_participant_id, $odd_id, $status);

        if ($stmt->execute()) {
            $message = "Opção de aposta adicionada com sucesso!";
            $message_type = "success";
            $race_id = $race_participant_id = $odd_id = ''; // Limpa campos
            $status = 'aberta';
        } else {
            $message = "Erro ao adicionar opção de aposta: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div class="content">
    <h1>Adicionar Nova Opção de Aposta</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="bets_add.php">
        <div class="form-group">
            <label for="race_id">Corrida:</label>
            <select name="race_id" id="race_id" class="form-control" required>
                <option value="">Selecione a Corrida</option>
                <?php $races->data_seek(0); ?>
                <?php while($race = $races->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($race['id']); ?>" <?php echo ($race['id'] == $race_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($race['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="race_participant_id">Participante da Corrida (Piloto):</label>
            <select name="race_participant_id" id="race_participant_id" class="form-control" required>
                <option value="">Selecione um Participante</option>
                <?php $race_participants->data_seek(0); ?>
                <?php while($rp = $race_participants->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($rp['id']); ?>" <?php echo ($rp['id'] == $race_participant_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rp['race_name']); ?> - <?php echo htmlspecialchars($rp['driver_name']); ?> (<?php echo htmlspecialchars($rp['driver_category']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="odd_id">Odd:</label>
            <select name="odd_id" id="odd_id" class="form-control" required>
                <option value="">Selecione a Odd</option>
                <?php $odds->data_seek(0); ?>
                <?php while($odd = $odds->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($odd['id']); ?>" <?php echo ($odd['id'] == $odd_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($odd['race_name']); ?> - <?php echo htmlspecialchars($odd['driver_name']); ?> (Tipo: <?php echo htmlspecialchars($odd['bet_type_name']); ?> - x<?php echo number_format($odd['valor_odd'], 2, '.', ''); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="aberta" <?php echo ($status == 'aberta') ? 'selected' : ''; ?>>Aberta</option>
                <option value="fechada" <?php echo ($status == 'fechada') ? 'selected' : ''; ?>>Fechada</option>
                <option value="cancelada" <?php echo ($status == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Adicionar Opção de Aposta</button>
        <a href="bets.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
