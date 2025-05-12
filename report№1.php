<?php
session_start();
require_once 'src/db_rep.php';
require_once 'src/models/staff.php';


$db_rep = new DbRepository();
$ordersTable = $db_rep->orders;

$startDate = date('Y-m-01');
$endDate = date('Y-m-t');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['start_date'] ?? date('Y-m-01');
    $endDate = $_POST['end_date'] ?? date('Y-m-t');
}

$stats = $ordersTable->getDeliveryStatistics($startDate, $endDate);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчёт №1 - Статистика доставок</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .report-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .period-selector {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .print-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
.export-btn {
            background: #1d6f42;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/logo.jpg" alt="Logo"> 
        </div>
        <h1>Отчёт №1 - Статистика доставок</h1>
        <nav class="nav-links">
		  <a href="report№1.php">Отчёт №1</a>
		  <a href="report№2.php">Отчёт №2</a>
		  <a href="report№3.php">Отчёт №3</a>
	       <a href="adminAcc.php">Личный кабинет</a>
            <a href="staff.php">Выйти</a>
        </nav>
    </header>

    <div class="report-container">
        <div class="period-selector">
            <h2>Выберите период</h2>
            <form method="post">
                <label for="start_date">Начальная дата:</label>
                <input type="date" id="start_date" name="start_date" 
                       value="<?= htmlspecialchars($startDate) ?>" required>
                
                <label for="end_date">Конечная дата:</label>
                <input type="date" id="end_date" name="end_date" 
                       value="<?= htmlspecialchars($endDate) ?>" required>
                
                <button type="submit">Показать</button>
            </form>
        </div>

        <h2>Статистика за период: <?= date('d.m.Y', strtotime($startDate)) ?> - <?= date('d.m.Y', strtotime($endDate)) ?></h2>
        
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Количество заказов</th>
                    <th>Сумма доставок</th>
                    <th>Средняя сумма заказа</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stats)): ?>
                    <tr>
                        <td colspan="5">Нет данных за выбранный период</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($stats as $index => $stat): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $stat['order_count'] ?></td>
                        <td><?= number_format($stat['total_amount'], 2) ?> ₽</td>
                        <td><?= number_format($stat['avg_amount'], 2) ?> ₽</td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

      <div class="export-buttons">
    <button class="print-btn" onclick="window.print()">Печать отчёта</button>
    <a href="export_report.php?type=courier_stats&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" 
       class="export-btn">Экспорт в Excel</a>
    <a href="export_report.php?type=courier_stats&format=word&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" 
       class="export-btn" style="background: #2b579a;">Экспорт в Word</a>

</div>
    </div>
</body>
</html>