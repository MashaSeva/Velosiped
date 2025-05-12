<?php
session_start();

if (!isset($_SESSION["client_id"])) {
    header("Location: login_client.php");
    exit();
}

require_once 'src/db_rep.php';
require_once 'src/orders_table.php';

$db_rep = new DbRepository();
$ordersTable = $db_rep->orders;

$clientId = $_SESSION["client_id"];
$orders = $ordersTable->getClientOrders($clientId);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .orders-list {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
        }
        .orders-list th, .orders-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .orders-list th {
            background-color: #f2f2f2;
        }
        .no-orders {
            text-align: center;
            margin: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
 <header>
        <div class="logo">
            <img src="img/logo.jpg" alt="Logo"> 
        </div>
        <nav class="nav-links">
            <a href="index.php">Главная</a>
			<a href="Orders_client.php">Мои заказы</a>
            <a href="ClientAcc.php" class="account-icon">
    <img src="img/Acc.png" alt="Аккаунт" width="24">
</a>
        </nav>
    </header>

    <h1>Мои заказы</h1>
    
    <?php if (empty($orders)): ?>
        <p class="no-orders">У вас пока нет заказов</p>
    <?php else: ?>
        <table class="orders-list">
            <thead>
                <tr>
                    <th>№ заказа</th>
                    <th>Дата</th>
                    <th>Адрес</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['ID_Order']) ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($order['Data_Order'])) ?></td>
                    <td><?= htmlspecialchars($order['Adress']) ?></td>
                    <td><?= number_format($order['Sum'], 2) ?> ₽</td>
                    <td><?= htmlspecialchars($order['Status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
</body>
</html>