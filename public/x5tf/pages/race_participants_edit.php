<?php
// truck-admin-pure-php/pages/race_participants_edit.php
require_once __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$message_type = '';
$participant = null;

// Obter dados do participante para edição
if (isset($_GET['id'])) {
    $participant_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM race_participants WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $participant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $participant = $result->fetch_assoc();
    $stmt->close();

    if (!$participant) {
        $message = "Participante não encontrado.";
        $message_type = "error";
    }
} else {
    $message = "ID do participante não fornecido.";
    $message_type = "error";
}

// Obter corridas e pilotos para os dropdowns (mesma lógica do add)
$races_query = $conn->query("SELECT id, nome FROM races ORDER BY nome ASC");
$drivers_query = $conn->query("SELECT id, nome, categoria FROM drivers ORDER BY nome ASC");

// Lógica para atualizar participante
if ($_SERVER["REQUEST_METHOD"] == "POST" && $participant) {
    $race_id = intval($_POST['race_id'] ?? 0);
    $driver_id = intval($_POST['driver_id'] ?? 0);
    $posicao_final = $_POST['posicao_final'] ?? NULL;
    $tempo_total = $_POST['tempo_total'] ?? NULL;

    if (empty($race_id) || empty($driver_id)) {
        $message = "Corrida e Piloto são obrigatórios.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("UPDATE race_participants SET race_id = ?, driver_id = ?, posicao_final = ?, tempo_total = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("iiisi", $race_id, $driver_id, $posicao_final, $tempo_total, $participant['id']);

        if ($stmt->execute()) {
            $message = "Participante atualizado com sucesso!";
            $message_type = "success";
            // Atualiza os dados do participante na tela
            $participant['race_id'] = $race_id;
            $participant['driver_id'] = $driver_id;
            $participant['posicao_final'] = $posicao_final;
            $participant['tempo_total'] = $tempo_total;
        } else {
            $message = "Erro ao atualizar participante: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

// Exibir o formulário somente se o participante foi encontrado
if ($participant):
?>

<div class="content">
    <h1>Editar Participante: <?php echo htmlspecialchars($participant['id'] ?? ''); ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="race_participants_edit.php?id=<?php echo htmlspecialchars($participant['id']); ?>">
        <div class="form-group">
            <label for="race_id">Corrida:</label>
            <select name="race_id" id="race_id" class="form-control" required>
                <option value="">Selecione a Corrida</option>
                <?php $races_query->data_seek(0); // Reseta o ponteiro para re-iterar ?>
                <?php while($race = $races_query->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($race['id']); ?>" <?php echo ($race['id'] == ($participant['race_id'] ?? '')) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($race['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="driver_id">Piloto:</label>
            <select name="driver_id" id="driver_id" class="form-control" required>
                <option value="">Selecione o Piloto</option>
                <?php $drivers_query->data_seek(0); // Reseta o ponteiro para re-iterar ?>
                <?php while($driver = $drivers_query->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($driver['id']); ?>" <?php echo ($driver['id'] == ($participant['driver_id'] ?? '')) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($driver['nome']); ?> (<?php echo htmlspecialchars($driver['categoria']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="posicao_final">Posição Final (opcional):</label>
            <input type="number" id="posicao_final" name="posicao_final" value="<?php echo htmlspecialchars($participant['posicao_final'] ?? ''); ?>" min="1">
        </div>
        <div class="form-group">
            <label for="tempo_total">Tempo Total (opcional):</label>
            <input type="text" id="tempo_total" name="tempo_total" value="<?php echo htmlspecialchars($participant['tempo_total'] ?? ''); ?>" placeholder="Ex: 01:23:45.678">
        </div>
        <button type="submit" class="btn-submit">Atualizar Participante</button>
        <a href="race_participants.php" class="btn-secondary">Voltar para Lista</a>
    </form>
</div>

<?php else: ?>
    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <a href="race_participants.php" class="btn-secondary">Voltar para Lista de Participantes</a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
