<!-- Container principal da página de login -->
<div class="max-w-md mx-auto p-6 bg-[#2D2D2D] shadow-xl border border-gray-700" style="height: 100vh !important;top: 0 !important;position: fixed !important;z-index: 999999 !important;width: 100% !important;margin-top: 0px !importantsss;padding-top: 15vh !important;">
    <img src="logo.svg" alt="TruckBet Logo Grande" class="h-10 md:h-28 mx-auto mb-6">
    <h2 class="text-3xl font-bold text-white mb-8 text-center">Acesse sua Conta</h2>
    <form id="loginForm">
        <div class="mb-5">
            <label for="email" class="block text-truck-light-gray text-sm font-semibold mb-2">Email:</label>
            <input type="email" id="email" name="email" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        <div class="mb-6">
            <label for="password" class="block text-truck-light-gray text-sm font-semibold mb-2">Senha:</label>
            <input type="password" id="password" name="password" required 
                   class="shadow-inner appearance-none border border-gray-600 rounded-lg w-full py-3 px-4 text-white mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-truck-yellow bg-[#3D3D3D] placeholder-gray-500">
        </div>
        <div class="flex items-center justify-between mb-6">
            <!-- Botão de login -->
            <button type="submit" 
                    class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-full focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 active:scale-95">
                Entrar
            </button>
            <!-- Link para recuperação de senha (fora do escopo funcional para esta fase) -->
            <a class="inline-block align-baseline font-bold text-sm text-truck-yellow hover:text-yellow-300 hover:underline transition duration-300" href="index.php?page=forgot-password">
                Esqueceu a senha?
            </a>
        </div>
        <!-- Parágrafo para exibir mensagens de login (sucesso/erro) -->
        <p id="loginMessage" class="text-center mt-4 text-sm font-semibold"></p>
        <div class="text-center mt-4">
            <a href="?page=register" class="text-truck-green hover:underline font-bold text-sm">Ainda não tem uma conta? Crie aqui!</a>
        </div>
    </form>
</div>