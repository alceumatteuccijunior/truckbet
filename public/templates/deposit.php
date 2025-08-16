<!-- Arquivo: truck-front-simples/templates/deposit.php -->
<div class="max-w-xl mx-auto p-6 bg-[#2D2D2D] rounded-xl shadow-xl mt-8 border border-gray-700">
    <h2 class="text-3xl font-bold text-white mb-6 text-center">Depositar Saldo</h2>
    <p class="text-truck-light-gray text-center mb-8">Insira o valor que deseja depositar via PIX. <br><br> Informamos que para esta etapa da Fórmula Truck cada depósito via pix pode ser de no máximo 150 reais.<br><br>Caso queira um valor maior faça mais depósitos.<br><br>Vale destacar que isso foi implantado para validações e para sua e nossa segurança.</p>

    <form id="depositForm" class="mb-8">
        <div class="mb-5">
            <label for="depositAmount" class="block text-truck-light-gray text-sm font-semibold mb-2">Valor do Depósito (R$):</label>
            <input type="number" id="depositAmount" name="amount" min="1" step="0.01" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        
        <button type="submit" id="generatePixButton"
                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-full w-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95">
            Gerar PIX
        </button>
        <p id="depositMessage" class="message-area text-center mt-4 hidden"></p>
    </form>

    <!-- Área para exibir o QR Code e PIX Copia e Cola -->
    <div id="pixDetails" class="hidden bg-[#3D3D3D] p-6 rounded-lg border border-gray-700 shadow-inner">
        <h3 class="text-xl font-bold text-white mb-4 text-center">Escaneie para Pagar</h3>
        <div class="flex justify-center mb-4">
            <img id="pixQrCodeImage" src="" alt="QR Code PIX" class="max-w-full h-auto rounded-lg border border-gray-600 p-2 bg-white">
        </div>
        <p class="text-truck-light-gray text-center mb-2">Ou copie e cole:</p>
        <textarea id="pixCopyPasteTextarea" readonly class="w-full h-24 bg-gray-700 text-white text-sm p-3 rounded-lg border border-gray-600 resize-none font-mono"></textarea>
        <button id="copyPixButton" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full w-full mt-4 transition duration-300">Copiar PIX</button>
        <p class="text-center text-truck-light-gray text-sm mt-4">Aguardando pagamento... O saldo será atualizado automaticamente.</p>
    </div>
</div>