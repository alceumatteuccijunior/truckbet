<!-- Seção Principal da Homepage com o design "APOSTE PESADO" -->
<div class="relative bg-truck-dark-gray overflow-hidden pb-10">
    <!-- Efeito de dinheiro caindo (simulado com imagem de fundo) -->
    <div class="absolute inset-0 bg-money-falling opacity-20"></div>
    
    <div class="relative z-10 text-center py-16 px-4 md:py-24" style="background-image: url(banner.png);background-size: cover;">
        <!-- Logo TruckBet grande na página inicial -->
        <img src="logo.svg"     alt="TruckBet Logo Grande" class="h-10 md:h-28 mx-auto mb-6">
        <img src="topo.png" alt="Aposte pesado" class="h-20 md:h-28 mx-auto mb-12">
        <!-- Botão "EU QUERO" que leva para a página de login -->
        <a href="?page=available-bets" class="inline-block bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:from-yellow-600 hover:to-orange-600 transition duration-300 text-lg md:text-xl transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:ring-opacity-50">EU QUERO</a>
    </div>
</div>

<!-- Seção de Busca e Botões de Ação -->
<div class="bg-truck-dark-gray p-4 rounded-b-lg shadow-inner-xl z-20 relative -mt-4 pb-8">
    <!-- Botões de ação (Fórmula Truck e Nova Aposta) -->
    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 px-4">
        <a href="#" class="bg-[#3D3D3D] text-white px-6 py-3 rounded-full font-semibold shadow-md hover:bg-[#4A4A4A] transition duration-300 text-center transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-600 focus:ring-opacity-50">Fórmula Truck</a>
        <a href="index.php?page=available-bets" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold shadow-md hover:from-yellow-600 hover:to-orange-600 transition duration-300 text-center transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-yellow-300 focus:ring-opacity-50">Nova Aposta</a>
    </div>
</div>

<!-- Seção de Odds Atualizadas -->
<section class="max-w-4xl mx-auto px-4 py-8">
    <h3 class="text-xl md:text-2xl font-bold text-white mb-4 text-center sm:text-left">Odds Categoria GT - CAMPO GRANDE</h3>
    <div class="bg-[#2D2D2D] rounded-xl p-4 shadow-xl border border-gray-700">
        <div class="grid grid-cols-2 gap-4 text-white text-center font-bold text-sm md:text-base mb-4">
            <div class="bg-[#3D3D3D] p-3 rounded-lg shadow-inner">Piloto</div>
            <div class="bg-[#3D3D3D] p-3 rounded-lg shadow-inner">Odd</div>
        </div>
        <!-- Exemplo de entries (pode ser populado dinamicamente por JS no futuro) -->
        <div class="grid grid-cols-2 gap-4 text-truck-light-gray text-center text-sm md:text-base">
            <div class="p-3 bg-truck-super-dark rounded-lg border border-gray-800">ZETTI</div>
            <div class="p-3 bg-truck-super-dark rounded-lg text-truck-yellow border border-gray-800">x10.62</div>
            <div class="p-3 bg-truck-super-dark rounded-lg border border-gray-800">TÚLIO BENDO</div>
            <div class="p-3 bg-truck-super-dark rounded-lg text-truck-yellow border border-gray-800">x1.15</div>
            <div class="p-3 bg-truck-super-dark rounded-lg border border-gray-800">THIAGO MARCO</div>
            <div class="p-3 bg-truck-super-dark rounded-lg text-truck-yellow border border-gray-800">x7.77</div>
        </div>
        <!-- Mais entries podem ser carregadas aqui pelo JS -->
    </div>
    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 px-4">
        <a href="index.php?page=available-bets" style="margin-top:2vh" class="bg-[#3D3D3D] text-white px-6 py-3 rounded-full font-semibold shadow-md hover:bg-[#4A4A4A] transition duration-300 text-center transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-600 focus:ring-opacity-50">Ver todas</a>
    </div>
</section>
<!-- Seção de Odds Atualizadas -->
<section class="max-w-4xl mx-auto px-4 py-8">
    <h3 class="text-xl md:text-2xl font-bold text-white mb-4 text-center sm:text-left">Odds Categoria FT - CAMPO GRANDE</h3>
    <div class="bg-[#2D2D2D] rounded-xl p-4 shadow-xl border border-gray-700">
        <div class="grid grid-cols-2 gap-4 text-white text-center font-bold text-sm md:text-base mb-4">
            <div class="bg-[#3D3D3D] p-3 rounded-lg shadow-inner">Piloto</div>
            <div class="bg-[#3D3D3D] p-3 rounded-lg shadow-inner">Odd</div>
        </div>
        <!-- Exemplo de entries (pode ser populado dinamicamente por JS no futuro) -->
        <div class="grid grid-cols-2 gap-4 text-truck-light-gray text-center text-sm md:text-base">
            <div class="p-3 bg-truck-super-dark rounded-lg border border-gray-800">DANIEL LOVATO</div>
            <div class="p-3 bg-truck-super-dark rounded-lg text-truck-yellow border border-gray-800">x1.95</div>
            <div class="p-3 bg-truck-super-dark rounded-lg border border-gray-800">DOUGLAS TORRES</div>
            <div class="p-3 bg-truck-super-dark rounded-lg text-truck-yellow border border-gray-800">x6.10</div>
            <div class="p-3 bg-truck-super-dark rounded-lg border border-gray-800">FABIO CLAUDINO</div>
            <div class="p-3 bg-truck-super-dark rounded-lg text-truck-yellow border border-gray-800">x1.58</div>
        </div>
        <!-- Mais entries podem ser carregadas aqui pelo JS -->
    </div>
    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 px-4">
        <a href="index.php?page=available-bets" style="margin-top:2vh" class="bg-[#3D3D3D] text-white px-6 py-3 rounded-full font-semibold shadow-md hover:bg-[#4A4A4A] transition duration-300 text-center transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-600 focus:ring-opacity-50">Ver todas</a>
    </div>
</section>