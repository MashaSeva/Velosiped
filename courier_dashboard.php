<?php
session_start();
require_once 'src/db_rep.php';


if (!isset($_SESSION['staff_id'])) {
    header("Location: staff.php");
    exit();
}

$db_rep = new DbRepository();
$ordersTable = $db_rep->orders;
$courierOrders = $ordersTable->getOrdersByCourier($_SESSION['staff_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Курьер</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Курьер</h1>
        <nav>
            <span>Добро пожаловать, <?= htmlspecialchars($_SESSION['staff_name']) ?></span>
            <a href="staff.php">Выйти</a>
        </nav>
    </header>

    <section>
        <h2>Мои заказы</h2>
        <table>
            <tr>
                <th>№ заказа</th>
                <th>Адрес</th>
                <th>Статус</th>
            </tr>
            <?php foreach ($courierOrders as $order): ?>
            <tr>
                <td><?= $order['ID_Order'] ?></td>
                <td><?= htmlspecialchars($order['Adress']) ?></td>
                <td><?= $order['Status'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</body>
</html>