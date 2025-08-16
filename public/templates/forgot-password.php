<!-- Arquivo: truck-front-simples/templates/forgot-password.php -->
<div class="max-w-md mx-auto p-6 bg-[#2D2D2D] shadow-xl border border-gray-700" style="height: 100vh !important;top: 0 !important;position: fixed !important;z-index: 999999 !important;width: 100% !important;margin-top: 0px !importantsss;padding-top: 15vh !important;">
    <h2 class="text-3xl font-bold text-white mb-8 text-center">Recuperar Senha</h2>
    <p class="text-truck-light-gray text-center mb-8">Informe seu email para receber o código de recuperação.</p>
    <form id="forgotPasswordForm">
        <div class="mb-5">
            <label for="recoveryEmail" class="block text-truck-light-gray text-sm font-semibold mb-2">Email:</label>
            <input type="email" id="recoveryEmail" name="email" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        
        <button type="submit" id="sendRecoveryCodeButton"
                class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-full w-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95">
            Enviar Código
        </button>
        <p id="forgotPasswordMessage" class="message-area text-center mt-4 hidden"></p>
    </form>
    <div class="text-center mt-4">
        <a href="?page=login" class="text-truck-green hover:underline font-bold text-sm">Lembrou a senha? Voltar ao Login</a>
    </div>
</div>
