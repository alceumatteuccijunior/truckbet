<?php
// truck-front-simples/index.php
// Este é o arquivo principal do frontend que roteia as páginas.

// Inclui o cabeçalho (HTML inicial, meta tags, Tailwind CDN, estilos, etc.)
include 'includes/header.php';

// Lógica de roteamento PHP simples
$page = $_GET['page'] ?? 'login';

// Inclui o template correspondente com base no valor de $page
if ($page === 'login') {
    include 'templates/login.php';
} elseif ($page === 'register') {
    include 'templates/register.php';
} elseif ($page === 'home') {
    include 'templates/home.php';
} elseif ($page === 'dashboard') {
    include 'templates/dashboard.php';
} elseif ($page === 'available-bets') {
    include 'templates/available-bets.php';
} elseif ($page === 'bet-history') {
    include 'templates/bet-history.php';
} elseif ($page === 'deposit') {
    include 'templates/deposit.php';
} elseif ($page === 'user-profile') {
    include 'templates/user-profile.php';
} elseif ($page === 'forgot-password') {
    include 'templates/forgot-password.php';
} elseif ($page === 'reset-password') {
    include 'templates/reset-password.php';
} elseif ($page === 'wallet') { // <-- Condição para a página "Carteira"
    include 'templates/wallet.php';
} elseif ($page === 'withdraw-request') { // <-- Condição para a página de Solicitação de Saque
    include 'templates/withdraw-request.php';
} elseif ($page === 'not-mobile') { // <-- NOVA CONDIÇÃO PARA A PÁGINA DE AVISO
    include 'templates/not-mobile.php';
} elseif ($page === 'terms-of-use') { // <-- NOVA CONDIÇÃO PARA A PÁGINA DE TERMOS DE USO
    include 'templates/terms-of-use.php';
} elseif ($page === 'no-available-bets') { // <-- NOVA CONDIÇÃO PARA A PÁGINA DE AVISO
    include 'templates/no-available-bets.php';
}else {
    // Se a página solicitada não estiver no escopo atual ou não existir
    echo '<div class="text-center p-8 text-white"><h1 class="text-4xl font-bold">404</h1><p class="text-lg">Página Não Encontrada ou Fora do Escopo.</p><a href="?page=login" class="text-truck-yellow hover:underline mt-4 inline-block">Voltar para o Login</a></div>';
}

// Inclui o rodapé (fechamento de tags HTML, link para script JS, etc.)
include 'includes/footer.php';
?>