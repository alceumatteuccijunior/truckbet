// Arquivo: truck-front-simples/js/script.js
document.addEventListener('DOMContentLoaded', () => {
    // URL base da sua API Laravel
    const API_BASE_URL = 'https://truckbet.vip/truck-api/public';
    // URL base dos scripts PHP puro para recuperação de senha
    const PHP_RECOVERY_BASE_URL = 'https://truckbet.vip/truck-api/public';

    // --- Validação de Dispositivo Móvel ---
    function isMobileDevice() {
        const mobileRegex = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|rim)|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i;
        const tabletRegex = /android|ipad|playbook|silk/i;

        const userAgent = navigator.userAgent || navigator.vendor || window.opera;
        return (mobileRegex.test(userAgent) || tabletRegex.test(userAgent)) || (window.innerWidth <= 768);
    }

    const currentPage = new URLSearchParams(window.location.search).get('page');
    if (currentPage !== 'not-mobile') {
        if (!isMobileDevice()) {
            window.location.href = '?page=not-mobile';
            return;
        }
    }
    // --- Fim da Validação de Dispositivo Móvel ---


    // Elementos do Menu Hamburguer
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMenuButton = document.getElementById('closeMenuButton');
    const mobileMenuLogoutLink = document.getElementById('mobileMenuLogoutLink');

    // Elementos do Header
    const headerBalanceContainer = document.getElementById('headerBalanceContainer');
    const headerBalanceSpan = document.getElementById('headerBalance');
    const headerDepositButton = document.getElementById('headerDepositButton');

    // Elementos do Modal de Aposta
    const placeBetModal = document.getElementById('placeBetModal');
    const closeBetModalButton = document.getElementById('closeBetModalButton');
    const placeBetForm = document.getElementById('placeBetForm');
    const modalRaceInfo = document.getElementById('modalRaceInfo');
    const modalBetDescription = document.getElementById('modalBetDescription');
    const modalBetOdd = document.getElementById('modalBetOdd');
    const modalBetId = document.getElementById('modalBetId');
    const modalRaceId = document.getElementById('modalRaceId');
    const betMessage = document.getElementById('betMessage');

    // Elementos da Página de Depósito
    const depositForm = document.getElementById('depositForm');
    const depositAmountInput = document.getElementById('depositAmount');
    const generatePixButton = document.getElementById('generatePixButton');
    const depositMessageArea = document.getElementById('depositMessage');
    const pixDetailsDiv = document.getElementById('pixDetails');
    const pixQrCodeImage = document.getElementById('pixQrCodeImage');
    const pixCopyPasteTextarea = document.getElementById('pixCopyPasteTextarea');
    const copyPixButton = document.getElementById('copyPixButton');

    // Elementos da Página de Perfil do Usuário
    const userProfileData = document.getElementById('userProfileData');
    const userProfileErrorMessage = document.getElementById('userProfileErrorMessage');
    const updateCpfForm = document.getElementById('updateCpfForm');
    const cpfInput = document.getElementById('cpfInput');
    const updateCpfButton = document.getElementById('updateCpfButton');
    const updateCpfMessage = document.getElementById('updateCpfMessage');

    // Elementos da Página de Recuperação de Senha (Passo 1: Enviar Email)
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const recoveryEmailInput = document.getElementById('recoveryEmail');
    const forgotPasswordMessage = document.getElementById('forgotPasswordMessage');
    const sendRecoveryCodeButton = document.getElementById('sendRecoveryCodeButton');

    // Elementos da Página de Redefinição de Senha (Passo 2: Código e Nova Senha)
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const resetEmailInput = document.getElementById('resetEmail');
    const recoveryCodeInput = document.getElementById('recoveryCode');
    const newPasswordInput = document.getElementById('newPassword');
    const newPasswordConfirmationInput = document.getElementById('newPasswordConfirmation');
    const resetPasswordMessage = document.getElementById('resetPasswordMessage');
    const resetPasswordButton = document.getElementById('resetPasswordButton');

    // Elementos da Página da Carteira (Wallet)
    const walletCurrentBalanceSpan = document.getElementById('walletCurrentBalance');
    const depositHistoryContainer = document.getElementById('depositHistoryContainer');
    const depositHistoryErrorMessage = document.getElementById('depositHistoryErrorMessage');
    const withdrawalHistoryContainer = document.getElementById('withdrawalHistoryContainer');
    const withdrawalHistoryErrorMessage = document.getElementById('withdrawalHistoryErrorMessage');

    // Elementos da Página de Solicitação de Saque
    const withdrawRequestForm = document.getElementById('withdrawRequestForm');
    const withdrawAmountInput = document.getElementById('withdrawAmount');
    const userCpfDisplay = document.getElementById('userCpfDisplay');
    const requestWithdrawalButton = document.getElementById('requestWithdrawalButton');
    const withdrawMessage = document.getElementById('withdrawMessage');

    // Elementos da Homepage (para as Odds Dinâmicas)
    const homepageOddsTitle = document.getElementById('homepageOddsTitle');
    const homepageOddsContainer = document.getElementById('homepageOddsContainer');
    const homepageOddsErrorMessage = document.getElementById('homepageOddsErrorMessage');


    // --- Função auxiliar para fazer requisições à API (Laravel) ---
    async function apiRequest(method, endpoint, data = null, requiresAuth = false) {
        const url = `${API_BASE_URL}${endpoint}`;
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        };

        if (data) {
            options.body = JSON.stringify(data);
        }

        if (requiresAuth) {
            const token = localStorage.getItem('auth_token');
            if (token) {
                options.headers['Authorization'] = `Bearer ${token}`;
            } else {
                console.error('Token de autenticação não encontrado. Redirecionando para login.');
                window.location.href = '?page=login';
                return null;
            }
        }

        try {
            const response = await fetch(url, options);
            const responseData = await response.json();

            if (!response.ok) {
                console.error(`Erro na API (${response.status}):`, responseData);
                if (response.status === 401) { 
                     localStorage.removeItem('auth_token');
                     setTimeout(() => { window.location.href = '?page=login'; }, 1500);
                     throw new Error('Sessão expirada ou não autorizada. Faça login novamente.');
                }
                if (responseData.error) { 
                    throw new Error(responseData.error);
                }
                if (responseData.errors) { 
                    const validationErrors = Object.values(responseData.errors).flat().join('<br>');
                    throw new Error(validationErrors);
                }
                throw new Error(responseData.message || `Erro do servidor: ${response.status}`);
            }
            return responseData;
        } catch (error) {
            console.error('Erro de rede ou na comunicação com a API:', error);
            throw error;
        }
    }

    // --- Função auxiliar para fazer requisições aos scripts PHP puro (Recuperação de Senha) ---
    async function phpRecoveryRequest(endpoint, data = null) {
        const url = `${PHP_RECOVERY_BASE_URL}/${endpoint}`;
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        };

        try {
            const response = await fetch(url, options);
            const responseData = await response.json();

            if (!response.ok || !responseData.success) {
                throw new Error(responseData.message || `Erro do servidor: ${response.status}`);
            }
            return responseData;
        } catch (error) {
            console.error('Erro na requisição PHP Recovery:', error);
            throw error;
        }
    }

    // --- Função para exibir mensagens na área de formulários (depósito, saque, recuperação, perfil) ---
    function showFormMessage(element, type, text) {
        element.textContent = text;
        element.className = `message-area mt-4 ${'message-' + type}`;
        element.classList.remove('hidden');
    }

    // --- Função CENTRALIZADA para realizar Logout ---
    async function performLogout() {
        try {
            await apiRequest('POST', '/api/logout', null, true); 
            localStorage.removeItem('auth_token');
            alert('Logout realizado com sucesso!');
            window.location.href = '?page=login';
        } catch (error) {
            console.error('Erro ao fazer logout:', error);
            alert('Erro ao fazer logout. Tente novamente.');
            localStorage.removeItem('auth_token');
            window.location.href = '?page=login';
        }
    }

    // --- Lógica para Atualizar Saldo no Header e Visibilidade de Elementos Logados ---
    async function updateHeaderElementsVisibility() {
        const isLoggedIn = localStorage.getItem('auth_token');

        if (isLoggedIn) {
            if (headerBalanceContainer) headerBalanceContainer.classList.remove('hidden');
            if (headerDepositButton) headerDepositButton.classList.remove('hidden');
            if (mobileMenuLogoutLink) mobileMenuLogoutLink.classList.remove('hidden');

            try {
                const user = await apiRequest('GET', '/api/me', null, true);
                if (user) { 
                    headerBalanceSpan.textContent = parseFloat(Number(user.saldo) || 0).toFixed(2);
                    if (walletCurrentBalanceSpan) {
                        walletCurrentBalanceSpan.textContent = `R$ ${parseFloat(Number(user.saldo) || 0).toFixed(2)}`;
                    }
                    if (userCpfDisplay && user.cpf) {
                        userCpfDisplay.textContent = user.cpf;
                    } else if (userCpfDisplay) {
                        userCpfDisplay.textContent = 'CPF não cadastrado';
                        userCpfDisplay.style.color = 'red';
                        if (withdrawRequestForm) {
                            requestWithdrawalButton.disabled = true;
                            requestWithdrawalButton.textContent = 'Cadastre seu CPF no Perfil';
                        }
                    }
                    if (cpfInput) {
                        cpfInput.value = user.cpf || ''; 
                    }
                }
            } catch (error) {
                console.error('Falha ao atualizar saldo/CPF no header/wallet:', error);
            }
        } else {
            if (headerBalanceContainer) headerBalanceContainer.classList.add('hidden');
            if (headerDepositButton) headerDepositButton.classList.add('hidden');
            if (mobileMenuLogoutLink) mobileMenuLogoutLink.classList.add('hidden');
            headerBalanceSpan.textContent = '0.00';
            if (walletCurrentBalanceSpan) {
                walletCurrentBalanceSpan.textContent = 'R$ 0.00';
            }
            if (userCpfDisplay) {
                userCpfDisplay.textContent = 'Não autenticado';
            }
            if (cpfInput) {
                cpfInput.value = '';
            }
        }
    }

    // --- Lógica do Menu Hamburguer ---
    if (menuButton && mobileMenu && closeMenuButton) {
        menuButton.addEventListener('click', () => {
            mobileMenu.classList.add('show-menu');
        });

        closeMenuButton.addEventListener('click', () => {
            mobileMenu.classList.remove('show-menu');
        });

        mobileMenu.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('show-menu');
            });
        });
    }

    // --- Conecta o link de logout no menu à função de logout ---
    if (mobileMenuLogoutLink) {
        mobileMenuLogoutLink.addEventListener('click', (event) => {
            event.preventDefault();
            performLogout();
        });
    }

    // --- Lógica da Página de Login ---
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const email = loginForm.email.value;
            const password = loginForm.password.value;
            const loginMessage = document.getElementById('loginMessage');

            loginMessage.textContent = 'Autenticando...';
            loginMessage.style.color = '#FFC107';

            try {
                const data = await apiRequest('POST', '/api/login', { email, password });
                
                if (data && data.token) {
                    localStorage.setItem('auth_token', data.token);
                    loginMessage.style.color = 'green';
                    loginMessage.textContent = 'Login bem-sucedido! Redirecionando para o Dashboard...';
                    setTimeout(() => {
                        window.location.href = '?page=dashboard';
                    }, 1000);
                } else {
                    loginMessage.style.color = 'red';
                    loginMessage.textContent = data.message || 'Erro desconhecido no login.';
                }
            } catch (error) {
                loginMessage.style.color = 'red';
                if (error.message && error.message.includes('{')) {
                    try {
                        const errorData = JSON.parse(error.message);
                        if (errorData.errors) {
                            let errorMessages = Object.values(errorData.errors).flat().join('<br>');
                            loginMessage.innerHTML = errorMessages;
                        } else {
                            loginMessage.textContent = error.message;
                        }
                    } catch (e) {
                        loginMessage.textContent = error.message;
                    }
                } else {
                    loginMessage.textContent = error.message || 'Erro de conexão ou credenciais inválidas.';
                }
            }
        });
    }

    // --- Lógica da Página de Registro ---
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const name = registerForm.name.value;
            const email = registerForm.email.value;
            const password = registerForm.password.value;
            const passwordConfirmation = registerForm.password_confirmation.value;
            const termsAccepted = document.getElementById('termsAccepted').checked; // Captura o estado do checkbox
            const registerMessage = document.getElementById('registerMessage');

            // Validação do checkbox dos Termos de Uso
            if (!termsAccepted) {
                showFormMessage(registerMessage, 'error', 'Você deve aceitar os Termos de Uso para se registrar.');
                return;
            }


            registerMessage.textContent = 'Registrando...';
            registerMessage.style.color = '#FFC107';

            try {
                const data = await apiRequest('POST', '/api/register', { 
                    name, 
                    email, 
                    password, 
                    password_confirmation: passwordConfirmation 
                });

                if (data && data.token) {
                    localStorage.setItem('auth_token', data.token);
                    registerMessage.style.color = 'green';
                    registerMessage.textContent = 'Registro bem-sucedido! Redirecionando para o Dashboard...';
                    setTimeout(() => {
                        window.location.href = '?page=dashboard';
                    }, 1000);
                } else {
                    registerMessage.style.color = 'red';
                    registerMessage.textContent = data.message || 'Erro desconhecido no registro.';
                }
            } catch (error) {
                registerMessage.style.color = 'red';
                if (error.message && error.message.includes('{')) {
                    try {
                        const errorData = JSON.parse(error.message);
                        if (errorData.errors) {
                            let errorMessages = Object.values(errorData.errors).flat().join('<br>');
                            registerMessage.innerHTML = errorMessages;
                        } else {
                            registerMessage.textContent = error.message;
                        }
                    } catch (e) {
                        registerMessage.textContent = error.message;
                    }
                } else {
                    registerMessage.textContent = error.message || 'Erro de conexão ou dados inválidos.';
                }
            }
        });
    }

    // --- Lógica da Página Dashboard ---
    if (window.location.search.includes('page=dashboard')) {
        const logoutButtonInstance = document.getElementById('logoutButton');
        const userDataDiv = document.getElementById('userData');
        const racesList = document.getElementById('racesList');

        async function loadUserData() {
            try {
                const user = await apiRequest('GET', '/api/me', null, true);
                if (user) {
                    updateHeaderElementsVisibility(); 
                    
                    userDataDiv.innerHTML = `
                        <p class="text-truck-light-gray text-lg mb-1"><strong>ID:</strong> ${user.id}</p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Nome:</strong> ${user.name}</p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Email:</strong> ${user.email}</p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>CPF:</strong> <span id="dashboardCpf">${user.cpf || 'Não informado'}</span></p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Saldo:</strong> R$ ${parseFloat(user.saldo || 0).toFixed(2)}</p>
                    `;
                }
            }
            catch (error) {
                userDataDiv.innerHTML = '<p style="color: red;">Não foi possível carregar dados do usuário.</p>';
                console.error('Erro ao carregar dados do usuário:', error);
            }
        }

        async function loadOpenRacesDashboard() {
            try {
                const races = await apiRequest('GET', '/api/races/open');
                if (races && races.length > 0) {
                    racesList.innerHTML = races.map(race => `
                        <li class="bg-[#3D3D3D] p-4 rounded-lg shadow-sm border border-gray-700 flex flex-col md:flex-row md:justify-between md:items-center">
                            <div>
                                <strong class="text-white">Corrida ID:</strong> <span class="text-truck-light-gray">${race.id}</span><br>
                                <strong class="text-truck-light-gray">Data/Hora:</strong> <span class="text-truck-light-gray">${new Date(race.data_hora).toLocaleString()}</span>
                            </div>
                            <strong class="text-white mt-2 md:mt-0">Status:</strong> <span class="text-truck-green">${race.status === 'aberta' ? 'Aberta' : 'Fechada'}</span>
                        </li>
                    `).join('');
                } else {
                    racesList.innerHTML = '<li class="text-truck-light-gray bg-[#3D3D3D] p-4 rounded-lg shadow-sm border border-gray-700">Nenhuma corrida aberta no momento.</li>';
                }
            } catch (error) {
                racesList.innerHTML = '<li style="color: red;">Não foi possível carregar as corridas.</li>';
                console.error('Erro ao carregar corridas:', error);
            }
        }

        if (localStorage.getItem('auth_token')) {
            loadUserData();
            loadOpenRacesDashboard();
        } else {
            window.location.href = '?page=login';
        }

        if (logoutButtonInstance) {
            logoutButtonInstance.addEventListener('click', (event) => {
                event.preventDefault();
                performLogout();
            });
        }
    }

    // --- Lógica da Página de Apostas Disponíveis ---
    if (window.location.search.includes('page=available-bets')) {
        const racesAndBetsContainer = document.getElementById('racesAndBetsContainer');
        const betsErrorMessage = document.getElementById('betsErrorMessage');

        function showPlaceBetModal(betDetails) {
            modalRaceInfo.textContent = betDetails.raceInfo;
            modalBetDescription.textContent = betDetails.description; 
            modalBetOdd.textContent = `x${betDetails.odd.toFixed(2)}`;
            modalBetId.value = betDetails.betId;
            modalRaceId.value = betDetails.raceId;

            betMessage.textContent = '';
            document.getElementById('betAmount').value = '';
            placeBetModal.classList.remove('hidden');
        }

        function hidePlaceBetModal() {
            placeBetModal.classList.add('hidden');
        }

        if (closeBetModalButton) {
            closeBetModalButton.addEventListener('click', hidePlaceBetModal);
        }

        if (placeBetForm) {
            placeBetForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const amount = parseFloat(document.getElementById('betAmount').value);
                const betId = modalBetId.value;
                const raceId = modalRaceId.value;

                if (isNaN(amount) || amount <= 0) {
                    betMessage.style.color = 'red';
                    betMessage.textContent = 'Por favor, insira um valor de aposta válido.';
                    return;
                }

                betMessage.textContent = 'Realizando aposta...';
                betMessage.style.color = '#FFC107';

                try {
                    const result = await apiRequest('POST', '/api/apostar', {
                        bet_id: betId,
                        race_id: raceId,
                        valor: amount
                    }, true);

                    betMessage.style.color = 'green';
                    betMessage.textContent = result.message || 'Aposta realizada com sucesso!';
                    
                    updateHeaderElementsVisibility(); 

                    setTimeout(() => { hidePlaceBetModal(); }, 2000);

                } catch (error) {
                    betMessage.style.color = 'red';
                    betMessage.textContent = error.message; 
                    console.error('Erro ao apostar:', error);
                }
            });
        }

        async function loadAvailableBets() {
            try {
                const races = await apiRequest('GET', '/api/races/open');
                const bets = await apiRequest('GET', '/api/bets');

                if ((!races || races.length === 0) && (!bets || bets.length === 0)) {
                    // Redireciona para a página de aviso se não houver apostas
                    window.location.href = '?page=no-available-bets'; // <-- MUDANÇA AQUI
                    return;
                }
                
                const racesWithParticipantsAndOdds = races.map(race => {
                    const participants = bets
                        .filter(bet => bet.race_id == race.id)
                        .map(bet => ({
                            id: bet.race_participant.id,
                            driver_name: bet.race_participant.driver.nome,
                            driver_category: bet.race_participant.driver.categoria,
                            odd_value: parseFloat(bet.odd.valor_odd),
                            bet_id: bet.id 
                        }));
                    return { ...race, participants: participants };
                });

                const categorizedRaces = {
                    'FT': [],
                    'GT': [],
                    'Outra Categoria': []
                };

                racesWithParticipantsAndOdds.forEach(race => {
                    if (race.id === 5) { 
                        categorizedRaces['FT'].push(race);
                    } else if (race.id === 4) { 
                        categorizedRaces['GT'].push(race);
                    } else {
                        categorizedRaces['Outra Categoria'].push(race);
                    }
                });

                let htmlContent = '';
                for (const categoryName in categorizedRaces) {
                    const racesInCategory = categorizedRaces[categoryName];
                    if (racesInCategory.length > 0) {
                        const categoryContentId = `category-content-${categoryName.replace(/\s/g, '-')}`;
                        
                        htmlContent += `
                            <div class="mb-8">
                                <button class="category-toggle bg-[#3D3D3D] text-white p-4 rounded-lg shadow-md w-full text-left flex justify-between items-center text-xl font-bold hover:bg-[#4A4A4A] transition duration-300" data-target="${categoryContentId}">
                                    <span>${categoryName}</span>
                                    <span class="toggle-icon">+</span>
                                </button>
                                <div id="${categoryContentId}" class="category-content hidden mt-4 space-y-6">
                                    ${racesInCategory.map(race => `
                                        <div class="bg-[#2D2D2D] p-5 rounded-lg shadow-md border border-gray-700">
                                            <h3 class="text-xl font-bold text-white mb-3">
                                                Corrida: ${race.nome || `ID: ${race.id}`} 
                                                <span class="text-truck-yellow">(${race.id === 5 ? 'Categoria FT' : (race.id === 4 ? 'Categoria GT' : 'Outra Categoria')})</span>
                                            </h3>
                                            <p class="text-truck-light-gray mb-4">Circuito: ${race.circuito || 'N/A'}, ${race.cidade || 'N/A'} - ${race.estado || 'N/A'}</p>
                                            <p class="text-truck-light-gray mb-4">Data/Hora: ${new Date(race.data_hora).toLocaleString()}</p>
                                            <p class="text-truck-light-gray mb-4">Status: <span class="text-truck-green">${race.status === 'aberta' ? 'Aberta' : 'Fechada'}</span></p>
                                            
                                            <h4 class="text-lg font-semibold text-white mb-3">Pilotos e Odds:</h4>
                                            ${race.participants && race.participants.length > 0 ? `
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    ${race.participants.map(participant => `
                                                        <div class="bg-[#2D2D2D] p-4 rounded-lg border border-gray-600 flex flex-col items-center justify-center space-y-2">
                                                            <p class="text-white font-semibold text-lg">${participant.driver_name}</p>
                                                            <p class="text-truck-light-gray text-sm">Categoria: ${participant.driver_category || 'N/A'}</p>
                                                            <p class="text-truck-light-gray text-sm">Odd:</p>
                                                            <span class="text-truck-yellow font-bold text-2xl">x${participant.odd_value.toFixed(2)}</span>
                                                            <button 
                                                                class="place-bet-button bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-2 px-4 rounded-full shadow-md transition duration-300 transform hover:scale-105 active:scale-95 w-full mt-2"
                                                                data-bet-id="${participant.bet_id}"
                                                                data-race-id="${race.id}"
                                                                data-bet-description="${participant.driver_name} (${participant.driver_category || 'N/A'}) - Vencedor"
                                                                data-bet-odd="${participant.odd_value}"
                                                                data-race-info="${race.nome} (ID: ${race.id}) - ${new Date(race.data_hora).toLocaleString()}">
                                                                Apostar
                                                            </button>
                                                        </div>
                                                    `).join('')}
                                                </div>
                                            ` : '<p class="text-truck-light-gray">Nenhum piloto/aposta disponível para esta corrida.</p>'}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }
                }

                racesAndBetsContainer.innerHTML = htmlContent;

                document.querySelectorAll('.category-toggle').forEach(button => {
                    button.addEventListener('click', () => {
                        const targetId = button.dataset.target;
                        const targetContent = document.getElementById(targetId);
                        const toggleIcon = button.querySelector('.toggle-icon');
                        
                        if (targetContent) {
                            targetContent.classList.toggle('hidden');
                            if (targetContent.classList.contains('hidden')) {
                                toggleIcon.textContent = '+';
                            } else {
                                toggleIcon.textContent = '-';
                            }
                        }
                    });
                });

                document.querySelectorAll('.place-bet-button').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const betId = event.target.dataset.betId;
                        const raceId = event.target.dataset.raceId;
                        const description = event.target.dataset.betDescription;
                        const odd = parseFloat(event.target.dataset.betOdd);
                        const raceInfo = event.target.dataset.raceInfo;

                        showPlaceBetModal({ betId, raceId, description, odd, raceInfo });
                    });
                });

                betsErrorMessage.classList.add('hidden');
            } catch (error) {
                console.error('Erro ao carregar apostas disponíveis:', error);
                racesAndBetsContainer.innerHTML = '';
                betsErrorMessage.classList.remove('hidden');
            }
        }

        loadAvailableBets();
    }

    // --- Lógica do Histórico de Apostas ---
    if (window.location.search.includes('page=bet-history')) {
        const betHistoryContainer = document.getElementById('betHistoryContainer');
        const betHistoryErrorMessage = document.getElementById('betHistoryErrorMessage');

        async function loadBetHistory() {
            try {
                const userBets = await apiRequest('GET', '/api/user-bets', null, true);

                if (!userBets || userBets.length === 0) {
                    betHistoryContainer.innerHTML = '<p class="text-truck-light-gray text-center">Você ainda não tem apostas registradas.</p>';
                    return;
                }

                betHistoryContainer.innerHTML = userBets.map(bet => `
                    <div class="bg-[#3D3D3D] p-5 rounded-lg border border-gray-700">
                        <p class="text-white font-bold text-lg mb-2">Aposta ID: ${bet.id}</p>
                        <p class="text-truck-light-gray">Corrida: ${bet.bet.race.nome || 'N/A'} (ID: ${bet.bet.race.id})</p>
                        <p class="text-truck-light-gray">Piloto: ${bet.bet.race_participant.driver.nome || 'N/A'}</p>
                        <p class="text-truck-light-gray">Valor Apostado: R$ ${parseFloat(bet.valor_apostado).toFixed(2)}</p>
                        <p class="text-truck-light-gray">Odd Usada: x${parseFloat(bet.odd_usada).toFixed(2)}</p>
                        <p class="text-truck-light-gray">Retorno Esperado: R$ ${parseFloat(bet.retorno_esperado).toFixed(2)}</p>
                        <p class="text-truck-light-gray">Status: 
                            <span class="${bet.status === 'pendente' ? 'text-truck-yellow' : (bet.status === 'ganha' ? 'text-truck-green' : 'text-red-500')} font-semibold">
                                ${bet.status ? bet.status.charAt(0).toUpperCase() + bet.status.slice(1) : 'N/A'}
                            </span>
                        </p>
                        <p class="text-truck-light-gray text-sm mt-2">Data da Aposta: ${new Date(bet.created_at).toLocaleString()}</p>
                    </div>
                `).join('');

                betHistoryErrorMessage.classList.add('hidden');
            } catch (error) {
                console.error('Erro ao carregar histórico de apostas:', error);
                betHistoryContainer.innerHTML = '';
                betHistoryErrorMessage.classList.remove('hidden');
            }
        }

        if (localStorage.getItem('auth_token')) {
            loadBetHistory();
        } else {
            window.location.href = '?page=login';
        }
    }

    // --- Lógica da Página de Depósito ---
    if (window.location.search.includes('page=deposit')) {
        const MAX_DEPOSIT_AMOUNT = 150.00; // Valor máximo de depósito

        if (depositForm) {
            depositForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const amount = parseFloat(depositAmountInput.value);

                if (isNaN(amount) || amount < 1) { // Minimo de R$1.00
                    showFormMessage(depositMessageArea, 'error', 'Por favor, insira um valor de depósito válido (mínimo R$1,00).');
                    return;
                }
                // NOVA VALIDAÇÃO: Valor máximo de depósito
                if (amount > MAX_DEPOSIT_AMOUNT) {
                    showFormMessage(depositMessageArea, 'error', `Informamos que para esta etapa da Fórmula Truck cada depósito via pix pode ser de no máximo R$ ${MAX_DEPOSIT_AMOUNT.toFixed(2).replace('.', ',')}. Caso queira um valor maior, faça mais depósitos. Vale destacar que isso foi implantado para validações e para sua e nossa segurança.`);
                    return;
                }


                showFormMessage(depositMessageArea, 'info', 'Gerando PIX, aguarde...');
                generatePixButton.disabled = true;
                generatePixButton.textContent = 'Processando...';

                try {
                    const response = await apiRequest('POST', '/api/deposit-pix', { amount: amount }, true);

                    showFormMessage(depositMessageArea, 'success', 'PIX gerado com sucesso! Escaneie ou copie para pagar. O saldo será atualizado automaticamente após o pagamento. Por favor, atualize a página ou vá para sua carteira para ver o saldo atualizado.');
                    pixDetailsDiv.classList.remove('hidden');

                    pixQrCodeImage.src = response.qr_code_base64;
                    pixCopyPasteTextarea.value = response.qr_code;

                    if (copyPixButton) {
                        copyPixButton.onclick = () => {
                            pixCopyPasteTextarea.select();
                            document.execCommand('copy');
                            copyPixButton.textContent = 'Copiado!';
                            setTimeout(() => { copyPixButton.textContent = 'Copiar PIX'; }, 2000);
                        };
                    }

                } catch (error) {
                    showFormMessage(depositMessageArea, 'error', error.message || 'Erro ao gerar PIX. Tente novamente.');
                    pixDetailsDiv.classList.add('hidden');
                } finally {
                    generatePixButton.disabled = false;
                    generatePixButton.textContent = 'Gerar PIX';
                }
            });
        }
    }

    // --- Lógica da Página de Perfil do Usuário ---
    if (window.location.search.includes('page=user-profile')) {
        async function loadUserProfile() {
            try {
                const user = await apiRequest('GET', '/api/me', null, true);
                if (user) {
                    userProfileData.innerHTML = `
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Nome:</strong> <span class="text-white">${user.name || 'N/A'}</span></p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Email:</strong> <span class="text-white">${user.email || 'N/A'}</span></p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>CPF:</strong> <span class="text-white">${user.cpf || 'Não informado'}</span></p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Saldo:</strong> <span class="text-truck-green">R$ ${parseFloat(user.saldo || 0).toFixed(2)}</span></p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>ID de Usuário:</strong> <span class="text-white">${user.id || 'N/A'}</span></p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Status da Conta:</strong> <span class="text-white">${user.status || 'N/A'}</span></p>
                    `;
                    userProfileErrorMessage.classList.add('hidden');
                    if (cpfInput) {
                        cpfInput.value = user.cpf || ''; 
                    }
                }
            } catch (error) {
                userProfileData.innerHTML = '<p class="text-truck-light-gray">Não foi possível carregar os dados do seu perfil.</p>';
                userProfileErrorMessage.classList.remove('hidden');
                console.error('Erro ao carregar perfil do usuário:', error);
            }
        }
        
        if (updateCpfForm) {
            updateCpfForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const newCpf = cpfInput.value;

                if (!newCpf || newCpf.length !== 11 || !/^\d+$/.test(newCpf)) {
                    showFormMessage(updateCpfMessage, 'error', 'Por favor, insira um CPF válido com 11 dígitos.');
                    return;
                }

                showFormMessage(updateCpfMessage, 'info', 'Salvando CPF, aguarde...');
                updateCpfButton.disabled = true;
                updateCpfButton.textContent = 'Salvando...';

                try {
                    const response = await apiRequest('POST', '/api/me/update-cpf', { cpf: newCpf }, true);

                    showFormMessage(updateCpfMessage, 'success', response.message || 'CPF atualizado com sucesso!');
                    updateHeaderElementsVisibility(); 
                } catch (error) {
                    showFormMessage(updateCpfMessage, 'error', error.message || 'Erro ao salvar CPF. Tente novamente.');
                    console.error('Erro ao salvar CPF:', error);
                } finally {
                    updateCpfButton.disabled = false;
                    updateCpfButton.textContent = 'Salvar CPF';
                }
            });
        }

        if (localStorage.getItem('auth_token')) {
            loadUserProfile();
        } else {
            window.location.href = '?page=login';
        }
    }

    // --- Lógica da Página de Recuperação de Senha (Passo 1: Enviar Email) ---
    if (window.location.search.includes('page=forgot-password')) {
        if (forgotPasswordForm) {
            forgotPasswordForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const email = recoveryEmailInput.value;

                showFormMessage(forgotPasswordMessage, 'info', 'Enviando código, aguarde...');
                sendRecoveryCodeButton.disabled = true;
                sendRecoveryCodeButton.textContent = 'Processando...';

                try {
                    const response = await phpRecoveryRequest('send_recovery_code.php', { email: email });
                    
                    showFormMessage(forgotPasswordMessage, 'success', response.message);
                    setTimeout(() => {
                        window.location.href = `?page=reset-password&email=${encodeURIComponent(email)}`;
                    }, 2000);

                } catch (error) {
                    showFormMessage(forgotPasswordMessage, 'error', error.message || 'Erro ao enviar código. Tente novamente.');
                } finally {
                    sendRecoveryCodeButton.disabled = false;
                    sendRecoveryCodeButton.textContent = 'Enviar Código';
                }
            });
        }
    }

    // --- Lógica da Página de Redefinição de Senha (Passo 2: Código e Nova Senha) ---
    if (window.location.search.includes('page=reset-password')) {
        const urlParams = new URLSearchParams(window.location.search);
        const emailFromUrl = urlParams.get('email');
        if (emailFromUrl && resetEmailInput) {
            resetEmailInput.value = decodeURIComponent(emailFromUrl);
        }

        if (resetPasswordForm) {
            resetPasswordForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const email = resetEmailInput.value;
                const code = recoveryCodeInput.value;
                const password = newPasswordInput.value;
                const passwordConfirmation = newPasswordConfirmationInput.value;

                showFormMessage(resetPasswordMessage, 'info', 'Redefinindo senha, aguarde...');
                resetPasswordButton.disabled = true;
                resetPasswordButton.textContent = 'Processando...';

                try {
                    const response = await phpRecoveryRequest('reset_password_process.php', {
                        email: email,
                        code: code,
                        password: password,
                        password_confirmation: passwordConfirmation
                    });

                    showFormMessage(resetPasswordMessage, 'success', response.message);
                    setTimeout(() => {
                        window.location.href = '?page=login';
                    }, 3000);

                } catch (error) {
                    showFormMessage(resetPasswordMessage, 'error', error.message || 'Erro ao redefinir senha. Tente novamente.');
                } finally {
                    resetPasswordButton.disabled = false;
                    resetPasswordButton.textContent = 'Redefinir Senha';
                }
            });
        }
    }

    // --- Lógica da Página da Carteira (Wallet) ---
    if (window.location.search.includes('page=wallet')) {
        async function loadWalletData() {
            updateHeaderElementsVisibility(); 

            // Carrega histórico de depósitos
            try {
                const deposits = await apiRequest('GET', '/api/me/deposits', null, true); 
                if (deposits && deposits.length > 0) {
                    depositHistoryContainer.innerHTML = deposits.map(deposit => `
                        <div class="bg-[#2D2D2D] p-4 rounded-lg border border-gray-600 flex justify-between items-center">
                            <div>
                                <p class="text-white font-semibold">Depósito ID: ${deposit.id}</p>
                                <p class="text-truck-light-gray text-sm">Valor: R$ ${parseFloat(deposit.amount).toFixed(2)}</p>
                                <p class="text-truck-light-gray text-sm">Data: ${new Date(deposit.created_at).toLocaleString()}</p>
                            </div>
                            <span class="${deposit.status === 'paid' ? 'text-truck-green' : (deposit.status === 'pending' ? 'text-truck-yellow' : 'text-red-500')} font-semibold">
                                ${deposit.status ? deposit.status.charAt(0).toUpperCase() + deposit.status.slice(1) : 'N/A'}
                            </span>
                        </div>
                    `).join('');
                    depositHistoryErrorMessage.classList.add('hidden');
                } else {
                    depositHistoryContainer.innerHTML = '<p class="text-truck-light-gray text-center">Nenhum depósito encontrado.</p>';
                    depositHistoryErrorMessage.classList.add('hidden');
                }
            } catch (error) {
                console.error('Erro ao carregar histórico de depósitos:', error);
                depositHistoryContainer.innerHTML = '<p class="text-truck-light-gray text-center">Erro ao carregar depósitos.</p>';
                depositHistoryErrorMessage.classList.remove('hidden');
            }

            // Carrega histórico de saques
            try {
                const withdrawals = await apiRequest('GET', '/api/user-withdrawals', null, true);
                if (withdrawals && withdrawals.length > 0) {
                    withdrawalHistoryContainer.innerHTML = withdrawals.map(withdrawal => `
                        <div class="bg-[#2D2D2D] p-4 rounded-lg border border-gray-600 flex justify-between items-center">
                            <div>
                                <p class="text-white font-semibold">Saque ID: ${withdrawal.id}</p>
                                <p class="text-truck-light-gray text-sm">Valor Solicitado: R$ ${parseFloat(withdrawal.amount).toFixed(2)}</p>
                                <p class="text-truck-light-gray text-sm">Data: ${new Date(withdrawal.created_at).toLocaleString()}</p>
                                <p class="text-truck-light-gray text-sm">Chave PIX: ${JSON.parse(withdrawal.payment_details).pix_key || 'N/A'}</p>
                            </div>
                            <span class="${withdrawal.status === 'approved' ? 'text-truck-green' : (withdrawal.status === 'pending' ? 'text-truck-yellow' : 'text-red-500')} font-semibold">
                                ${withdrawal.status ? withdrawal.status.charAt(0).toUpperCase() + withdrawal.status.slice(1) : 'N/A'}
                            </span>
                        </div>
                    `).join('');
                    withdrawalHistoryErrorMessage.classList.add('hidden');
                } else {
                    withdrawalHistoryContainer.innerHTML = '<p class="text-truck-light-gray text-center">Nenhum saque encontrado.</p>';
                    withdrawalHistoryErrorMessage.classList.add('hidden');
                }
            } catch (error) {
                console.error('Erro ao carregar histórico de saques:', error);
                withdrawalHistoryContainer.innerHTML = '<p class="text-truck-light-gray text-center">Erro ao carregar saques.</p>';
                withdrawalHistoryErrorMessage.classList.remove('hidden');
            }
        }

        if (localStorage.getItem('auth_token')) {
            loadWalletData();
        } else {
            window.location.href = '?page=login';
        }
    }

    // --- Lógica da Página de Solicitação de Saque ---
    if (window.location.search.includes('page=withdraw-request')) {
        async function displayUserCpfForWithdrawal() {
            try {
                const user = await apiRequest('GET', '/api/me', null, true);
                if (user) {
                    if (user.cpf) {
                        userCpfDisplay.textContent = user.cpf;
                        requestWithdrawalButton.disabled = false;
                        requestWithdrawalButton.textContent = 'Solicitar Saque';
                    } else {
                        userCpfDisplay.textContent = 'CPF não cadastrado. Atualize seu perfil.';
                        userCpfDisplay.style.color = 'red';
                        if (withdrawRequestForm) {
                            requestWithdrawalButton.disabled = true;
                            requestWithdrawalButton.textContent = 'Cadastre seu CPF no Perfil';
                        }
                    }
                }
            } catch (error) {
                console.error('Erro ao carregar CPF para saque:', error);
                if (userCpfDisplay) {
                    userCpfDisplay.textContent = 'Erro ao carregar CPF.';
                    userCpfDisplay.style.color = 'red';
                    requestWithdrawalButton.disabled = true;
                    requestWithdrawalButton.textContent = 'Erro ao carregar CPF';
                    showFormMessage(withdrawMessage, 'error', 'Erro ao carregar informações do usuário. Recarregue a página.');
                }
            }
        }

        if (withdrawRequestForm) {
            displayUserCpfForWithdrawal();

            withdrawRequestForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const amount = parseFloat(withdrawAmountInput.value);
                const pixKey = userCpfDisplay.textContent; 

                if (isNaN(amount) || amount < 5) {
                    showFormMessage(withdrawMessage, 'error', 'O valor mínimo para saque é de R$ 5,00.');
                    return;
                }
                if (!pixKey || pixKey.length < 11 || !/^\d{11}$/.test(pixKey)) {
                    showFormMessage(withdrawMessage, 'error', 'CPF inválido. Por favor, cadastre um CPF válido no seu perfil.');
                    return;
                }
                if (pixKey.includes('Cadastre') || pixKey.includes('Erro') || pixKey.includes('Não autenticado')) {
                    showFormMessage(withdrawMessage, 'error', 'Não foi possível obter seu CPF para saque. Recarregue a página ou atualize seu perfil.');
                    return;
                }

                showFormMessage(withdrawMessage, 'info', 'Solicitando saque, aguarde...');
                requestWithdrawalButton.disabled = true;
                requestWithdrawalButton.textContent = 'Processando...';

                try {
                    const response = await apiRequest('POST', '/api/withdraw-request', { 
                        amount: amount,
                        pix_key: pixKey
                    }, true);

                    showFormMessage(withdrawMessage, 'success', response.message || 'Solicitação de saque enviada com sucesso!');
                    updateHeaderElementsVisibility(); 

                } catch (error) {
                    showFormMessage(withdrawMessage, 'error', error.message || 'Erro ao solicitar saque. Tente novamente.');
                    console.error('Erro ao solicitar saque:', error);
                } finally {
                    requestWithdrawalButton.disabled = false;
                    requestWithdrawalButton.textContent = 'Solicitar Saque';
                }
            });
        }
    }


    updateHeaderElementsVisibility(); // Chamada inicial para definir visibilidade e saldo

});
