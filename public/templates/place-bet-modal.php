<!-- Arquivo: truck-front-simples/templates/place-bet-modal.php -->
<!-- Modal de Realizar Aposta - Oculto por padrão -->
<div id="placeBetModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-[#2D2D2D] rounded-xl shadow-2xl w-full max-w-md p-6 border border-gray-700 relative">
        <!-- Botão para fechar o modal -->
        <button id="closeBetModalButton" class="absolute top-3 right-3 text-white text-3xl font-bold hover:text-truck-yellow transition duration-300">&times;</button>
        
        <h3 class="text-2xl font-bold text-white mb-6 text-center">Realizar Aposta</h3>

        <div class="mb-4">
            <p class="text-truck-light-gray text-sm mb-1">Corrida: <span id="modalRaceInfo" class="font-semibold text-white"></span></p>
            <p class="text-truck-light-gray text-sm mb-1">Aposta: <span id="modalBetDescription" class="font-semibold text-white"></span></p>
            <p class="text-truck-light-gray text-sm mb-1">Odd: <span id="modalBetOdd" class="font-semibold text-truck-yellow"></span></p>
        </div>

        <form id="placeBetForm">
            <input type="hidden" id="modalBetId" name="bet_id">
            <input type="hidden" id="modalRaceId" name="race_id">

            <div class="mb-5">
                <label for="betAmount" class="block text-truck-light-gray text-sm font-semibold mb-2">Valor da Aposta (R$):</label>
                <input type="number" id="betAmount" name="amount" min="1" step="0.01" required 
                       class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
            </div>
            
            <button type="submit" 
                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-full w-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95">
                Confirmar Aposta
            </button>
            <p id="betMessage" class="text-center mt-4 text-sm font-semibold"></p>
        </form>
    </div>
</div>
