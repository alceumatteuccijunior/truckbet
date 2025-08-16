<!-- Arquivo: truck-front-simples/templates/wallet.php -->
<div class="max-w-4xl mx-auto p-6 bg-[#2D2D2D] rounded-xl shadow-xl mt-8 border border-gray-700">
    <h2 class="text-3xl font-bold text-white mb-6 text-center">Minha Carteira</h2>
    <p class="text-truck-light-gray text-center mb-8">Gerencie seus fundos e visualize seu histórico de transações.</p>

    <!-- Saldo Atual -->
    <div class="bg-[#3D3D3D] p-5 rounded-lg mb-6 shadow-md border border-gray-700 text-center">
        <p class="text-truck-light-gray text-xl mb-2">Saldo Atual:</p>
        <p class="text-truck-green font-bold text-4xl" id="walletCurrentBalance">R$ 0.00</p>
    </div>

    <!-- Botões de Ação -->
    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 mb-8">
        <a href="?page=deposit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-full shadow-md text-center transition duration-300 transform hover:scale-105">Depositar</a>
        <a href="?page=withdraw-request" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-full shadow-md text-center transition duration-300 transform hover:scale-105">Sacar</a>
    </div>

    <!-- Histórico de Depósitos -->
    <h3 class="text-2xl font-bold text-white mb-4">Histórico de Depósitos</h3>
    <div id="depositHistoryContainer" class="space-y-4 mb-8">
        <p class="text-truck-light-gray text-center">Carregando histórico de depósitos...</p>
    </div>
    <p id="depositHistoryErrorMessage" class="message-area message-error text-center hidden">Não foi possível carregar o histórico de depósitos.</p>

    <!-- Histórico de Saques -->
    <h3 class="text-2xl font-bold text-white mb-4">Histórico de Saques</h3>
    <div id="withdrawalHistoryContainer" class="space-y-4">
        <p class="text-truck-light-gray text-center">Carregando histórico de saques...</p>
    </div>
    <p id="withdrawalHistoryErrorMessage" class="message-area message-error text-center hidden">Não foi possível carregar o histórico de saques.</p>

</div>
