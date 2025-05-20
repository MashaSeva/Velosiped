<?php
session_start();
require_once 'src/db_rep.php';

$db_rep = new DbRepository();
$clientsTable = $db_rep->clients;

$ageStats = $clientsTable->getAgeStatistics();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчёт №3 - Статистика по возрасту клиентов</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .report-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
        .stats-summary {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .print-btn, .export-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .export-btn.excel {
            background: #1d6f42;
        }
        .export-btn.word {
            background: #2b579a;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/logo.jpg" alt="Logo"> 
        </div>
        <h1>Отчёт №3 - Статистика по возрасту клиентов</h1>
        <nav class="nav-links">
            <a href="report№1.php">Отчёт №1</a>
		  <a href="report№2.php">Отчёт №2</a>
		  <a href="report№3.php">Отчёт №3</a>
	       <a href="adminAcc.php">Личный кабинет</a>
            <a href="staff.php">Выйти</a>
        </nav>
    </header>

    <div class="report-container">
        <div class="stats-summary">
            <h2>Общая статистика</h2>
            <p>Средний возраст клиентов: <?= $ageStats['average_age'] ?> лет</p>
            <p>Минимальный возраст: <?= $ageStats['min_age'] ?> лет</p>
            <p>Максимальный возраст: <?= $ageStats['max_age'] ?> лет</p>
            <p>Всего клиентов: <?= $ageStats['total_clients'] ?></p>
        </div>

        <h2>Распределение по возрастам</h2>
        <table>
            <thead>
                <tr>
                    <th>Возрастная группа</th>
                    <th>Количество клиентов</th>
                    <th>Процент от общего числа</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ageStats['age_groups'] as $group => $count): ?>
                <tr>
                    <td><?= htmlspecialchars($group) ?></td>
                    <td><?= $count ?></td>
                    <td><?= round(($count / $ageStats['total_clients']) * 100, 2) ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="export-buttons">
            <button class="print-btn" onclick="window.print()">Печать отчёта</button>
            <a href="export_report.php?type=age_stats&format=excel" class="export-btn excel">Экспорт в Excel</a>
            <a href="export_report.php?type=age_stats&format=word" class="export-btn word">Экспорт в Word</a>
        </div>
    </div>
</body>
</html>