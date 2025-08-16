<?php
// truck-admin-pure-php/config/db_connect.php
// ATENÇÃO: Credenciais de banco de dados diretamente no código.
// ESTA ABORDAGEM É EXTREMAMENTE INSEGURA EM PRODUÇÃO SEM MEDIDAS ADICIONAIS.
// ACESSO LIVRE A QUALQUER UM QUE TENHA A URL.

define('DB_HOST', 'localhost'); // Geralmente 'localhost' no CPanel
define('DB_USER', 'castelob_truck_api'); // SEU_USUARIO_DO_BANCO_COMPLETO_AQUI
define('DB_PASS', 'jxtkaugz9sL5cb6PMWtm'); // SUA_SENHA_DO_BANCO_AQUI
define('DB_NAME', 'castelob_truck_api'); // SEU_NOME_DO_BANCO_COMPLETO_AQUI

// Conexão usando MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    // Em produção, logar o erro e não exibir detalhes ao usuário
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Opcional: Definir charset para evitar problemas com acentuação
$conn->set_charset("utf8mb4");

// A linha 'session_start();' foi REMOVIDA, pois não haverá login/sessão.
