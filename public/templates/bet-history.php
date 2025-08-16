<!-- Arquivo: truck-front-simples/templates/bet-history.php -->
<div class="max-w-4xl mx-auto p-6 bg-[#2D2D2D] rounded-xl shadow-xl mt-8 border border-gray-700">
    <h2 class="text-3xl font-bold text-white mb-6 text-center">Meu Histórico de Apostas</h2>
    <p class="text-truck-light-gray text-center mb-8">Todas as suas apostas passadas e seus resultados.</p>

    <div id="betHistoryContainer" class="space-y-6">
        <!-- Histórico de apostas será carregado aqui via JavaScript -->
        <p class="text-truck-light-gray text-center">Carregando histórico de apostas...</p>
    </div>

    <p id="betHistoryErrorMessage" class="text-center mt-8 text-red-500 font-semibold hidden">Não foi possível carregar seu histórico de apostas.</p>
</div>
