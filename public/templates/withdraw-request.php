<!-- Arquivo: truck-front-simples/templates/withdraw-request.php -->
<div class="max-w-md mx-auto p-6 bg-[#2D2D2D] rounded-xl shadow-xl mt-8 border border-gray-700">
    <h2 class="text-3xl font-bold text-white mb-6 text-center">Solicitar Saque</h2>
    <p class="text-truck-light-gray text-center mb-8">
        Insira o valor que deseja sacar para o seu PIX (CPF).
    </p>

    <!-- Exibição do CPF do usuário -->
    <div class="bg-gray-700 p-4 rounded-lg mb-6 text-center border border-gray-600">
        <p class="text-truck-light-gray text-base mb-1">Seu CPF Cadastrado (Chave PIX):</p>
        <p class="text-white font-bold text-lg" id="userCpfDisplay">Carregando CPF...</p>
        <p class="text-sm text-truck-light-gray mt-2">Certifique-se de que este é o seu CPF correto para o saque.</p>
    </div>

    <form id="withdrawRequestForm" class="mb-8">
        <div class="mb-5">
            <label for="withdrawAmount" class="block text-truck-light-gray text-sm font-semibold mb-2">Valor do Saque (R$):</label>
            <input type="number" id="withdrawAmount" name="amount" min="1" step="0.01" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        
        <!-- Observações sobre taxa, limite e tempo de processamento -->
        <div class="message-area message-info text-left mt-4 text-sm bg-gray-700 border-gray-600">
            <p class="mb-2"><strong>Taxa de R$ 1,00</strong> por operação será deduzida do valor solicitado.</p>
            <p class="mb-2">Ex: Se solicitar R$ 100,00, você receberá R$ 99,00.</p>
            <p class="mb-2"><strong>Apenas 1 saque permitido a cada 24 horas.</strong></p>
            <p>O saque pode levar até **120 minutos (Duas horas)** após a aprovação para ser creditado, devido ao fluxo de pedidos.</p>
        </div>

        <button type="submit" id="requestWithdrawalButton"
                class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-full w-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95 mt-6">
            Solicitar Saque
        </button>
        <p id="withdrawMessage" class="message-area text-center mt-4 hidden"></p>
    </form>

    <div class="text-center mt-4">
        <a href="?page=wallet" class="text-truck-green hover:underline font-bold text-sm">Voltar para Carteira</a>
    </div>
</div>
