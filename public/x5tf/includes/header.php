<?php
// truck-admin-pure-php/includes/header.php
// A lógica de verificação de login foi REMOVIDA.
// ATENÇÃO: Este painel é acessível publicamente por URL (INSEGURO).
require_once __DIR__ . '/../config/db_connect.php'; // Garante a conexão com o banco
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - TruckBet</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #1A1A1A; color: #E0E0E0; }
        .header { background-color: #2D2D2D; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }
        .header h2 { margin: 0; color: #FFC107; }
        .header nav ul { margin: 0; padding: 0; list-style: none; display: flex; }
        .header nav ul li { margin-left: 20px; }
        .header nav ul li a { color: #E0E0E0; text-decoration: none; font-weight: bold; transition: color 0.3s; }
        .header nav ul li a:hover { color: #FFC107; }
        .main-content { margin-left: 200px; padding: 20px; } /* Ajusta para a largura da sidebar */
        .content { background-color: #2D2D2D; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); margin-top: 20px; }
        
        .sidebar { background-color: #222; width: 200px; height: 100vh; position: fixed; top: 0; left: 0; padding-top: 80px; box-shadow: 2px 0 5px rgba(0,0,0,0.3); overflow-y: auto;} /* Adicionado overflow-y para barras de rolagem */
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li a { display: block; padding: 15px 20px; color: #E0E0E0; text-decoration: none; border-bottom: 1px solid #333; transition: background-color 0.3s, color 0.3s; }
        .sidebar ul li a:hover { background-color: #3D3D3D; color: #FFC107; }
        .sidebar ul li:last-child a { border-bottom: none; } /* Para o último item */

        .table-container { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #444; }
        table th { background-color: #3D3D3D; color: #eee; font-weight: bold; }
        table tr:nth-child(even) { background-color: #222; }
        table tr:hover { background-color: #3D3D3D; }
        .action-links a, .action-buttons { color: #007bff; text-decoration: none; margin-right: 10px; }
        .action-buttons { display: inline-block; padding: 5px 10px; border-radius: 4px; font-size: 0.9em; cursor: pointer; border: none;}
        .action-buttons.edit { background-color: #007bff; color: white;}
        .action-buttons.delete { background-color: #dc3545; color: white;}
        .action-buttons:hover { opacity: 0.9; }

        .btn-add { background-color: #28a745; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-bottom: 15px; }
        .btn-add:hover { background-color: #218838; }
        .btn-submit { background-color: #FFC107; color: #333; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; transition: background-color 0.3s; }
        .btn-submit:hover { background-color: #e0a800; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #eee; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="password"],
        .form-group input[type="number"], .form-group input[type="datetime-local"], .form-group select,
        .form-group textarea {
            width: 100%; padding: 10px; border: 1px solid #555; border-radius: 4px; background-color: #444; color: #eee;
            box-sizing: border-box; /* Garante que padding não aumente a largura */
        }
        .form-group input[type="checkbox"] { margin-top: 10px; }
        
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; }
        .alert-success { background-color: #28a745; color: white; }
        .alert-error { background-color: #dc3545; color: white; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
        .stat-card { background-color: #3D3D3D; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); text-align: center; }
        .stat-card h3 { color: #FFC107; font-size: 1.2em; margin-bottom: 10px; }
        .stat-card p { font-size: 2em; font-weight: bold; color: #E0E0E0; }
        
        /* Estilos para Mobile */
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; box-shadow: none; padding-top: 0; }
            .sidebar ul { display: flex; flex-wrap: wrap; justify-content: center; }
            .sidebar ul li { margin: 5px; }
            .sidebar ul li a { border-bottom: none; border-radius: 5px; padding: 10px 15px; }
            .main-content { margin-left: 0; }
            .header { flex-direction: column; align-items: flex-start; }
            .header nav ul { margin-top: 10px; }
            .header .user-info { margin-top: 10px; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/index.php">Dashboard</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/users.php">Usuários</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/races.php">Corridas</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/drivers.php">Pilotos</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/race_participants.php">Participantes</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/odds.php">Odds</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/bet_types.php">Tipos de Aposta</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/bets.php">Opções de Aposta</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/user_apostas.php">Apostas de Usuários</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/deposits.php">Depósitos</a></li>
            <li><a href="https://truckbet.trovaobrasil.com.br/public/x5tf/pages/withdrawals.php">Solicitações</a></li>
            <!-- O link "Sair" foi removido, pois não há login -->
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <h2>Painel de Administração TruckBet</h2>
            <!-- A informação do usuário logado foi REMOVIDA, pois não há login -->
            <!-- <div class="user-info">
                Bem-vindo, <?php //echo htmlspecialchars($_SESSION['admin_user_name'] ?? 'Admin'); ?>!
                <a href="logout.php">(Sair)</a>
            </div> -->
        </div>
