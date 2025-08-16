<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simular Webhook PushinPay</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f2f5; margin: 0; }
        .container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h1 { text-align: center; color: #333; margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"] { width: calc(100% - 22px); padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em; }
        button { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1.1em; width: 100%; transition: background-color 0.3s; }
        button:hover { background-color: #218838; }
        .note { background-color: #fff3cd; border-left: 5px solid #ffeeba; padding: 15px; margin-bottom: 20px; font-size: 0.9em; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Simular Webhook de Pagamento PIX</h1>
        <div class="note">
            <p><strong>Instruções:</strong></p>
            <ul>
                <li>Obtenha um <strong>ID de Transação PushinPay real</strong> de um PIX que você gerou.</li>
                <li>Cole o ID no campo abaixo.</li>
                <li>Clique em "Enviar Notificação 'Pago'".</li>
                <li>Verifique o arquivo <code>truck-api/storage/logs/laravel.log</code> no seu servidor para ver se o webhook foi processado.</li>
            </ul>
        </div>
        <form action="https://truckbet.vip/truck-api/public/api/pushinpay-webhook" method="POST">
            <label for="transactionId">ID da Transação PushinPay:</label>
            <input type="text" id="transactionId" name="id" required placeholder="Ex: pp_xxxxxxxxxxxxxxxxxxxxxxxxxxxx">
            
            <input type="hidden" name="status" value="paid">
            <input type="hidden" name="value" value="1000"> <!-- Valor em centavos, para exemplo. Pode ser qualquer valor. -->
            <input type="hidden" name="event" value="cash_in_paid"> <!-- Evento típico de pagamento confirmado -->

            <button type="submit">Enviar Notificação 'Pago'</button>
        </form>
    </div>
</body>
</html>
