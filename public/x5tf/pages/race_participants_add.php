<?php
// truck-admin-pure-php/pages/race_participants_add.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';

$race_id = '';
$driver_id = '';
$posicao_final = '';
$tempo_total = '';

// Obter corridas e pilotos para os dropdowns
$races = $conn->query("SELECT id, nome FROM races ORDER BY nome ASC");
$drivers = $conn->query("SELECT id, nome, categoria FROM drivers ORDER BY nome ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $race_id = intval($_POST['race_id'] ?? 0);
    $driver_id = intval($_POST['driver_id'] ?? 0);
    $posicao_final = $_POST['posicao_final'] ?? NULL;
    $tempo_total = $_POST['tempo_total'] ?? NULL;

    if (empty($race_id) || empty($driver_id)) {
        $message = "Corrida e Piloto são obrigatórios.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO race_participants (race_id, driver_id, posicao_final, tempo_total, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("iiss", $race_id, $driver_id, $posicao_final, $tempo_total);

        if ($stmt->execute()) {
            $message = "Participante adicionado com sucesso!";
            $message_type = "success";
            $race_id = $driver_id = '';
            $posicao_final = $tempo_total = '';
        } else {
            $message = "Erro ao adicionar participante: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}
?>

<div class="content">
    <h1>Adicionar Novo Participante da Corrida</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="race_participants_add.php">
        <div class="form-group">
            <label for="race_id">Corrida:</label>
            <select name="race_id" id="race_id" class="form-control" required>
                <option value="">Selecione a Corrida</option>
                <?php while($race = $races->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($race['id']); ?>" <?php echo ($race['id'] == $race_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($race['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="driver_id">Piloto:</label>
            <select name="driver_id" id="driver_id" class="form-control" required>
                <option value="">Selecione o Piloto</option>
                <?php while($driver = $drivers->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($driver['id']); ?>" <?php echo ($driver['id'] == $driver_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($driver['nome']); ?> (<?php echo htmlspecialchars($driver['categoria']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="posicao_final">Posição Final (opcional):</label>
            <input type="number" id="posicao_final" name="posicao_final" value="<?php echo htmlspecialchars($posicao_final ?? ''); ?>" min="1">
        </div>
        <div class="form-group">
            <label for="tempo_total">Tempo Total (opcional):</label>
            <input type="text" id="tempo_total" name="tempo_total" value="<?php echo htmlspecialchars($tempo_total ?? ''); ?>" placeholder="Ex: 01:23:45.678">
        </div>
        <button type="submit" class="btn-submit">Adicionar Participante</button>
        <a href="race_participants.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
