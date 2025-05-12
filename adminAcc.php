<?php
session_start();
require_once 'src/db_rep.php';


if (!isset($_SESSION['staff_id'])) {
    header("Location: staff.php");
    exit();
}

$db_rep = new DbRepository();
$staffTable = $db_rep->staff;
$ordersTable = $db_rep->orders;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администратор</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Администратор</h1>
        <nav class="nav-links">
		  <a href="report№1.php">Отчёт №1</a>
		  <a href="report№2.php">Отчёт №2</a>
		  <a href="report№3.php">Отчёт №3</a>
            <a href="staff.php">Выйти</a>
        </nav>
    </header>

    
</body>
</html>