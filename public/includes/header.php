<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TruckBet App</title>
    <!-- Tailwind CSS CDN - Importado diretamente para evitar processo de build local -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter para texto geral e Bebas Neue para títulos marcantes -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        /* Configurações de fonte padrão para o corpo do documento */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1A1A1A; /* Fundo principal escuro, de acordo com o design */
            color: #E0E0E0; /* Cor de texto padrão clara */
        }
        /* Definição de fontes para todos os títulos */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
        }
        /* Classes CSS customizadas para cores e fundos que não são facilmente expressas com classes Tailwind padrão sem custom config */
        .bg-truck-dark-gray { background-color: #2D2D2D; }
        .bg-truck-super-dark { background-color: #121212; }
        .text-truck-yellow { color: #FFC107; }
        .text-truck-green { color: #8BC34A; }
        .text-truck-light-gray { color: #A0A0A0; }

        /* Estilo específico para a fonte "Bebas Neue" para títulos como "APOSTE PESADO" */
        .font-bebas {
            font-family: 'Bebas Neue', cursive; /* 'cursive' é um fallback genérico */
            letter-spacing: 0.05em; /* Aumenta o espaçamento entre letras para o estilo */
        }

        /* Classe para o background com imagem de dinheiro caindo */
        .bg-money-falling {
            background-image: url('/images/money-falling-bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Adicione aqui qualquer CSS customizado ou overrides que o Tailwind não cobrir */
        /* Estilo para a sobreposição do menu em dispositivos móveis */
        #mobileMenu.show-menu {
            transform: translateX(0); /* Faz o menu deslizar para dentro */
        }
    </style>
    <!-- Seu CSS local, se houver, para overrides específicos -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<!-- Body com classes Tailwind para o layout flexbox e cores base -->
<body class="bg-[#1A1A1A] text-truck-light-gray min-h-screen flex flex-col">
    <!-- Componente Header - Barra Superior do Aplicativo -->
    <header class="bg-[#1A1A1A] text-white p-4 flex justify-between items-center shadow-lg relative z-20">
        <div class="flex items-center space-x-3">
            <!-- Logo TruckBet no topo -->
            <img src="logo-icon.svg" alt="TruckBet Logo" class="h-10 w-10">
            
            <!-- Balanço do usuário e ícone (visível apenas após login, ou com dado placeholder) -->
            <div class="flex items-center bg-[#2D2D2D] rounded-full px-4 py-2 text-sm font-medium">
                R$<span id="headerBalance" class="font-bold text-white text-lg mr-1">0.00</span> 
                <svg class="w-5 h-5 text-truck-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
            
            <!-- Botão Carteira (agora é um link para depositar) -->
            <a href="?page=deposit" class="bg-[#3D3D3D] text-white rounded-full px-4 py-2 text-sm font-semibold shadow-md hover:bg-[#4A4A4A] transition duration-300">Depositar</a>
        </div>
        <!-- Menu Hamburguer (agora com ID para funcionalidade JS) -->
        <button id="menuButton" class="text-white text-3xl focus:outline-none">☰</button>
    </header>

    <!-- Menu de Navegação Lateral/Overlay (Inicialmente escondido) -->
    <div id="mobileMenu" class="fixed inset-0 bg-[#1A1A1A] z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex justify-end p-4">
            <button id="closeMenuButton" class="text-white text-3xl focus:outline-none">&times;</button>
        </div>
        <nav class="flex flex-col items-center justify-center space-y-8 text-2xl font-bold">
            <a href="?page=home" class="text-white hover:text-truck-yellow transition duration-300">Home</a>
            <a href="?page=available-bets" class="text-white hover:text-truck-yellow transition duration-300">Apostar</a>
            <a href="?page=bet-history" class="text-white hover:text-truck-yellow transition duration-300">Minhas Apostas</a>
            <a href="?page=wallet" class="text-white hover:text-truck-yellow transition duration-300">Carteira</a>
            <a href="?page=user-profile" class="text-white hover:text-truck-yellow transition duration-300">Meu Perfil</a>
            <a href="?page=terms-of-use" class="text-white hover:text-truck-yellow transition duration-300">Termos de Uso</a>
            <a href="#" id="mobileMenuLogoutLink" class="text-red-500 hover:text-red-400 transition duration-300 hidden">Sair</a> <!-- NOVO LINK DE SAIR -->
        </nav>
    </div>

    <!-- Área principal de conteúdo, que será preenchida dinamicamente pelos templates PHP -->
    <main class="flex-grow">