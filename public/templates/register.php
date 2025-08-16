<div class="max-w-md mx-auto p-6 bg-[#2D2D2D] shadow-xl border border-gray-700" style="height: 100vh !important;top: 0 !important;position: fixed !important;z-index: 999999 !important;width: 100% !important;margin-top: 0px !importantsss;padding-top: 5vh !important;">
    <h2 class="text-3xl font-bold text-white mb-8 text-center">Criar Nova Conta</h2>
    <form id="registerForm">
        <div class="mb-5">
            <label for="registerName" class="block text-truck-light-gray text-sm font-semibold mb-2">Nome Completo:</label>
            <input type="text" id="registerName" name="name" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        <div class="mb-5">
            <label for="registerEmail" class="block text-truck-light-gray text-sm font-semibold mb-2">Email:</label>
            <input type="email" id="registerEmail" name="email" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        <div class="mb-5">
            <label for="registerPassword" class="block text-truck-light-gray text-sm font-semibold mb-2">Senha:</label>
            <input type="password" id="registerPassword" name="password" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        <div class="mb-6">
            <label for="registerPasswordConfirmation" class="block text-truck-light-gray text-sm font-semibold mb-2">Confirmar Senha:</label>
            <input type="password" id="registerPasswordConfirmation" name="password_confirmation" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        
        <!-- Checkbox de Termos de Uso -->
        <div class="mb-6 flex items-center">
            <input type="checkbox" id="termsAccepted" name="terms_accepted" class="form-checkbox h-5 w-5 text-truck-yellow rounded border-gray-600 focus:ring-truck-yellow">
            <label for="termsAccepted" class="ml-2 text-truck-light-gray text-sm">
                Eu concordo com os <a href="?page=terms-of-use" class="text-truck-yellow hover:underline" target="_blank">Termos de Uso</a>.
            </label>
        </div>

        <div class="flex items-center justify-between mb-6">
            <button type="submit" 
                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95">
                Registrar
            </button>
            <a class="inline-block align-baseline font-bold text-sm text-truck-yellow hover:text-yellow-300 hover:underline transition duration-300" href="?page=login">
                Já tem uma conta? Faça Login
            </a>
        </div>
        <p id="registerMessage" class="message-area text-center mt-4 hidden"></p>
    </form>
</div>