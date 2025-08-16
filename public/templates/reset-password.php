    <!-- Arquivo: truck-front-simples/templates/reset-password.php -->
    <div class="max-w-md mx-auto p-6 bg-[#2D2D2D] shadow-xl border border-gray-700" style="height: 100vh !important;top: 0 !important;position: fixed !important;z-index: 999999 !important;width: 100% !important;margin-top: 0px !importantsss;">
        <h2 class="text-3xl font-bold text-white mb-8 text-center">Redefinir Senha</h2>
        <p class="text-truck-light-gray text-center mb-8">Informe o código que você recebeu e sua nova senha.</p>
        <form id="resetPasswordForm">
            <div class="mb-5">
                <label for="resetEmail" class="block text-truck-light-gray text-sm font-semibold mb-2">Email:</label>
                <input type="email" id="resetEmail" name="email" required readonly
                       class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
            </div>
            <div class="mb-5">
                <label for="recoveryCode" class="block text-truck-light-gray text-sm font-semibold mb-2">Código de Recuperação:</label>
                <input type="text" id="recoveryCode" name="code" required 
                       class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
            </div>
            <div class="mb-5">
                <label for="newPassword" class="block text-truck-light-gray text-sm font-semibold mb-2">Nova Senha:</label>
                <input type="password" id="newPassword" name="password" required 
                       class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
            </div>
            <div class="mb-6">
                <label for="newPasswordConfirmation" class="block text-truck-light-gray text-sm font-semibold mb-2">Confirmar Nova Senha:</label>
                <input type="password" id="newPasswordConfirmation" name="password_confirmation" required 
                       class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
            </div>
            
            <button type="submit" id="resetPasswordButton"
                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-full w-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95">
                Redefinir Senha
            </button>
            <p id="resetPasswordMessage" class="message-area text-center mt-4 hidden"></p>
        </form>
    </div>
    