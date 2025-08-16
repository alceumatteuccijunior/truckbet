    <?php
    // truck-api/public/db_config.php
    // ESTE ARQUIVO CONTÉM CREDENCIAIS DE BANCO DE DADOS
    // NUNCA UTILIZE ESTA ABORDAGEM EM PRODUÇÃO POR SEGURANÇA.
    // Use o sistema de configuração e ORM do Laravel.

    // Obtenha estas credenciais do seu arquivo truck-api/.env
    // Ex: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
    
    define('DB_HOST', 'localhost'); // Geralmente 'localhost' no CPanel
    define('DB_NAME', 'castelob_truck_api'); // SEU_NOME_DO_BANCO_COMPLETO_AQUI
    define('DB_USER', 'castelob_truck_api'); // SEU_USUARIO_DO_BANCO_COMPLETO_AQUI
    define('DB_PASS', 'jxtkaugz9sL5cb6PMWtm'); // SUA_SENHA_DO_BANCO_AQUI

    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Em um ambiente de produção, logar o erro e mostrar uma mensagem genérica.
        // error_log("Erro de conexão PDO: " . $e->getMessage());
        die("Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.");
    }
    