<!-- Arquivo: truck-front-simples/templates/user-profile.php -->
<div class="max-w-xl mx-auto p-6 bg-[#2D2D2D] rounded-xl shadow-xl mt-8 border border-gray-700">
    <h2 class="text-3xl font-bold text-white mb-6 text-center">Meu Perfil</h2>
    <p class="text-truck-light-gray text-center mb-8">Visualize e atualize suas informações pessoais.</p>

    <!-- Dados da Conta (Visível) -->
    <div id="userProfileData" class="bg-[#3D3D3D] p-5 rounded-lg mb-6 shadow-md border border-gray-700">
        <p class="text-white text-xl font-semibold mb-3">Dados da Conta:</p>
        <p class="text-truck-light-gray">Carregando dados do perfil...</p>
    </div>

    <!-- Formulário para Atualizar CPF -->
    <div class="bg-[#3D3D3D] p-5 rounded-lg shadow-md border border-gray-700 mt-6">
        <h3 class="text-xl font-bold text-white mb-4 text-center">Atualizar CPF (Chave PIX)</h3>
        <p class="text-truck-light-gray text-sm text-center mb-6">Seu CPF é essencial para solicitar saques via PIX.</p>
        
        <form id="updateCpfForm">
            <div class="mb-5">
                <label for="cpfInput" class="block text-truck-light-gray text-sm font-semibold mb-2">CPF (apenas números):</label>
                <input type="text" id="cpfInput" name="cpf" maxlength="11" pattern="\d{11}" placeholder="00011122233" required 
                       class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
            </div>
            
            <button type="submit" id="updateCpfButton"
                    class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-full w-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95">
                Salvar CPF
            </button>
            <p id="updateCpfMessage" class="message-area text-center mt-4 hidden"></p>
        </form>
    </div>

    <p id="userProfileErrorMessage" class="message-area message-error text-center mt-4 hidden">Não foi possível carregar os dados do seu perfil.</p>
</div>