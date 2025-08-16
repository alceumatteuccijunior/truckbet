<!-- Arquivo: truck-front-simples/templates/available-bets.php -->
<div class="max-w-4xl mx-auto p-6 bg-[#2D2D2D] rounded-xl shadow-xl mt-8 border border-gray-700">
    <h2 class="text-3xl font-bold text-white mb-6 text-center">Apostas Disponíveis</h2>
    <p class="text-truck-light-gray text-center mb-8">Escolha uma corrida e um piloto para fazer sua aposta.</p>

    <div id="racesAndBetsContainer" class="space-y-8">
        <!-- Corridas e apostas serão carregadas aqui via JavaScript -->
        <p class="text-truck-light-gray text-center">Carregando apostas...</p>
    </div>

    <p id="betsErrorMessage" class="text-center mt-8 text-red-500 font-semibold hidden">Não foi possível carregar as apostas no momento.</p>
</div>

<!-- Inclui o Modal de Realizar Aposta (ele ficará oculto por padrão e será mostrado via JS) -->
<?php include 'place-bet-modal.php'; ?>
