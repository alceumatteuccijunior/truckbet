// Arquivo: truck-front-simples/js/script.js
document.addEventListener('DOMContentLoaded', () => {
    // URL base da sua API Laravel
    const API_BASE_URL = 'https://truckbet.trovaobrasil.com.br/truck-api/public';

    // Elementos do Menu Hamburguer
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMenuButton = document.getElementById('closeMenuButton');

    // Elemento para exibir o saldo no header
    const headerBalanceSpan = document.getElementById('headerBalance');

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


    // --- Função auxiliar para fazer requisições à API ---
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
                if (response.status === 401 || response.status === 403) {
                     localStorage.removeItem('auth_token');
                     setTimeout(() => { window.location.href = '?page=login'; }, 1500);
                     throw new Error('Sessão expirada ou não autorizada. Faça login novamente.');
                }
                if (response.status === 422 && responseData.errors) {
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

    // --- Lógica para Atualizar Saldo no Header ---
    async function updateHeaderBalance() {
        if (localStorage.getItem('auth_token') && headerBalanceSpan) {
            try {
                const user = await apiRequest('GET', '/api/me', null, true);
                if (user && user.saldo !== undefined) {
                    headerBalanceSpan.textContent = parseFloat(user.saldo).toFixed(2);
                }
            } catch (error) {
                console.error('Falha ao atualizar saldo no header:', error);
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
                loginMessage.textContent = error.message || 'Erro de conexão ou credenciais inválidas.';
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
            const registerMessage = document.getElementById('registerMessage');

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
                    updateHeaderBalance(); 
                    
                    userDataDiv.innerHTML = `
                        <p class="text-truck-light-gray text-lg mb-1"><strong>ID:</strong> ${user.id}</p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Nome:</strong> ${user.name}</p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Email:</strong> ${user.email}</p>
                        <p class="text-truck-light-gray text-lg mb-1"><strong>Saldo:</strong> R$ ${parseFloat(user.saldo || 0).toFixed(2)}</p>
                    `;
                }
            } catch (error) {
                userDataDiv.innerHTML = '<p style="color: red;">Não foi possível carregar dados do usuário. Por favor, faça login novamente.</p>';
                console.error('Erro ao carregar dados do usuário:', error);
                if (error.message.includes('Unauthenticated')) {
                     localStorage.removeItem('auth_token');
                     setTimeout(() => { window.location.href = '?page=login'; }, 1500);
                }
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
                                <strong class="text-white">Data/Hora:</strong> <span class="text-truck-light-gray">${new Date(race.data_hora).toLocaleString()}</span>
                            </div>
                            <strong class="text-white mt-2 md:mt-0">Status:</strong> <span class="text-truck-green">${race.status === 'true' ? 'Aberta' : 'Fechada'}</span>
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
            logoutButtonInstance.addEventListener('click', async () => {
                try {
                    await apiRequest('POST', '/api/logout', null, true);
                    localStorage.removeItem('auth_token');
                    alert('Logout realizado com sucesso!');
                    window.location.href = '?page=login';
                } catch (error) {
                    console.error('Erro ao fazer logout:', error);
                    alert('Erro ao fazer logout. Tente novamente.');
                }
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
                        amount: amount
                    }, true);

                    betMessage.style.color = 'green';
                    betMessage.textContent = result.message || 'Aposta realizada com sucesso!';
                    
                    updateHeaderBalance(); 

                    setTimeout(() => { hidePlaceBetModal(); }, 2000);

                } catch (error) {
                    betMessage.style.color = 'red';
                    betMessage.textContent = error.message || 'Erro ao realizar a aposta.';
                    console.error('Erro ao apostar:', error);
                }
            });
        }


        async function loadAvailableBets() {
            try {
                const races = await apiRequest('GET', '/api/races/open');
                const bets = await apiRequest('GET', '/api/bets');

                if ((!races || races.length === 0) && (!bets || bets.length === 0)) {
                    racesAndBetsContainer.innerHTML = '<p class="text-truck-light-gray text-center">Nenhuma corrida ou aposta disponível no momento.</p>';
                    return;
                }

                const racesWithBets = races.map(race => {
                    const relatedBets = bets.filter(bet => bet.race_id === race.id);
                    return { ...race, bets: relatedBets };
                });

                racesAndBetsContainer.innerHTML = racesWithBets.map(race => `
                    <div class="bg-[#3D3D3D] p-5 rounded-lg shadow-md border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-3">Corrida: ${race.id} - ${new Date(race.data_hora).toLocaleString()}</h3>
                        <p class="text-truck-light-gray mb-4">Status: <span class="text-truck-green">${race.status === 'true' ? 'Aberta' : 'Fechada'}</span></p>
                        
                        <h4 class="text-lg font-semibold text-white mb-3">Apostas para esta Corrida:</h4>
                        ${race.bets && race.bets.length > 0 ? `
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                ${race.bets.map(bet => `
                                    <div class="bg-[#2D2D2D] p-4 rounded-lg border border-gray-600 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                                        <div class="text-center sm:text-left">
                                            <p class="text-white font-semibold">${bet.description || 'Aposta'}</p>
                                            <p class="text-truck-light-gray text-sm">Piloto: ${bet.player_name || 'N/A'}</p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-truck-yellow font-bold text-xl">x${bet.odd.toFixed(2)}</span>
                                            <button 
                                                class="place-bet-button bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-2 px-4 rounded-full shadow-md transition duration-300 transform hover:scale-105 active:scale-95"
                                                data-bet-id="${bet.id}"
                                                data-race-id="${race.id}"
                                                data-bet-description="${bet.description}"
                                                data-bet-odd="${bet.odd}"
                                                data-race-info="Corrida ${race.id} - ${new Date(race.data_hora).toLocaleString()}">
                                                Apostar
                                            </button>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        ` : '<p class="text-truck-light-gray">Nenhuma aposta disponível para esta corrida.</p>'}
                    </div>
                `).join('');

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

    updateHeaderBalance();
});
