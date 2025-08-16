<?php
// truck-admin-pure-php/index.php
// Não há verificação de login, o painel é acessível diretamente (ATENÇÃO: INSEGURO).
require_once 'config/db_connect.php'; // Garante a conexão com o banco de dados

// --- Obtenção dos Dados para as Métricas ---

// Total de Usuários
$total_users_result = $conn->query("SELECT COUNT(*) as count FROM users");
$total_users = $total_users_result->fetch_assoc()['count'];

// Total Depositado e Confirmado (status = 'paid')
$total_deposits_result = $conn->query("SELECT SUM(amount) as total_amount FROM deposits WHERE status = 'paid'");
$row_deposited = $total_deposits_result->fetch_assoc(); // Busca a linha do resultado
$total_deposited = (float)($row_deposited['total_amount'] ?? 0); // Garante que seja float ou 0

// Total já Apostado (da tabela user_apostas)
$total_bet_amount_result = $conn->query("SELECT SUM(valor_apostado) as total_bet FROM user_apostas");
$row_bet = $total_bet_amount_result->fetch_assoc(); // Busca a linha do resultado
$total_bet = (float)($row_bet['total_bet'] ?? 0); // Garante que seja float ou 0

// Depósitos Pendentes
$pending_deposits_result = $conn->query("SELECT COUNT(*) as count FROM deposits WHERE status = 'pending'");
$pending_deposits_count = $pending_deposits_result->fetch_assoc()['count'];

// Saques Pendentes
$pending_withdrawals_result = $conn->query("SELECT COUNT(*) as count FROM withdrawals WHERE status = 'pending'");
$pending_withdrawals_count = $pending_withdrawals_result->fetch_assoc()['count'];

// --- Fim da Obtenção dos Dados ---

// Inclui o header do painel
include 'includes/header.php';
?>

<div class="content">
    <h1>Bem-vindo ao Painel de Administração!</h1>
    <p>Use o menu para gerenciar as tabelas do seu sistema TruckBet.</p>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total de Usuários</h3>
            <p><?php echo htmlspecialchars($total_users); ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Depositado (Confirmado)</h3>
            <p>R$ <?php echo number_format($total_deposited, 2, ',', '.'); ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Apostado</h3>
            <p>R$ <?php echo number_format($total_bet, 2, ',', '.'); ?></p>
        </div>
        <div class="stat-card">
            <h3>Depósitos Pendentes</h3>
            <p><?php echo htmlspecialchars($pending_deposits_count); ?></p>
        </div>
        <div class="stat-card">
            <h3>Saques Pendentes</h3>
            <p><?php echo htmlspecialchars($pending_withdrawals_count); ?></p>
        </div>
    </div>
</div>

<?php
// Inclui o footer do painel
include 'includes/footer.php';
?>
