<?php
session_start();
require_once 'src/models/client.php';
require_once 'src/clients_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();

$staffTable = $db_rep->staff; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $staff = new Staff();
        $staff->name = $_POST['name'];
        $staff->tel_staff = $_POST['tel_staff'];
         $staff->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $staff->title = TitleType::fromName($_POST['title']); 
        $staffTable->create($staff);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_save') {
        $staff = new Staff();
        $staff->id_staff = $_POST['id_staff'];
        $staff->name = $_POST['name'];
        $staff->tel_staff = $_POST['tel_staff'];
        $staff->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $staff->title = TitleType::fromName($_POST['title']);

        $staffTable->update($staff);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_staff'];
        $staffTable->delete($id);
    }
}

$staffMembers = $staffTable->readAll();

require_once 'src/db_rep.php';
require_once 'src/staff_table.php';

$error = '';
$db_rep = new DbRepository();
$staffTable = $db_rep->staff;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tel = trim($_POST['tel']);
    $password = trim($_POST['password']);


    $staff = $staffTable->findByPhone($tel);

    if ($staff && password_verify($password, $staff->password)) {

        $_SESSION['staff_id'] = $staff->id_staff;
        $_SESSION['staff_name'] = $staff->name;
        $_SESSION['title'] = $staff->title;
        
        header("Location: " . ($staff->title ? "adminAcc.php" : "courier_dashboard.php"));

        exit();
    } else {
        $error = "Неверный телефон или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Сотрудники</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo.jpg" alt="Logo"> 
    </div>
<h1>Сотрудники</h1>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="staff.php">Сотрудники</a>
        <a href="clients.php">Клиенты</a>
    </nav>
</header>

<div class="registration">
        <h1>Вход для сотрудников</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="tel" placeholder="Телефон" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
  

    
</body>
</html>