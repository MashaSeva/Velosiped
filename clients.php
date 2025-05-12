<?php

require_once 'src/models/client.php';
require_once 'src/clients_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();

$clientsTable = $db_rep->clients;
$successMessage = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $client = new Client();
        $client->name = $_POST['name'];
        $client->tel = $_POST['tel'];
        $client->email = $_POST['email'];
        $client->data_bd = new DateTime($_POST['data_bd']);
        $client->password = password_hash($_POST['password'], PASSWORD_DEFAULT); 


        $clientsTable->create($client);		
	   
	   header("Location: clients.php");
	   
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_save') {
        $client = new Client();
        $client->id_client = $_POST['id_client'];
        $client->name = $_POST['name'];
        $client->tel = $_POST['tel'];
        $client->email = $_POST['email'];
        $client->data_bd = new DateTime($_POST['data_bd']);
        $client->password = $_POST['password'];
        

        $clientsTable->update($client);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $client_id = $_POST['id'];
        $clientsTable->delete($client_id);
    }
}


$clients = $clientsTable->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Clients</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo.jpg" alt="Logo"> 
    </div>
<h1>Клиенты</h1>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="staff.php">Сотрудники</a>
        <a href="clients.php">Клиенты</a>
    </nav>
</header>


<section class="registration">
<h2>Регистрация</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Имя" required>
        <input type="tel" name="tel" placeholder="Телефон" required>
        <input type="email" name="email" placeholder="Электронная почта" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="date" name="data_bd" required>
	   <input type="hidden" name="action" value="add">
        <button type="submit">Зарегистрироваться</button>
<p>Уже есть аккаунт? <a href="login_client.php">Войти</a></p>

    </form>
</section>





</body>
</body>
</html>
