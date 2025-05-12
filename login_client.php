<?php
session_start();

require_once 'src/models/client.php';
require_once 'src/clients_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();
$clientsTable = $db_rep->clients;

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Некорректный email!";
    } else {
        $client = $clientsTable->findByEmail($email);

        if ($client !== null && password_verify($password, $client->password)) {

            $_SESSION['client_id'] = $client->id_client;
            $_SESSION['client_name'] = $client->name;
            $_SESSION['client_email'] = $client->email;
		  $_SESSION['client_tel'] = $client->tel;
    		  $_SESSION['client_data_bd'] = $client->data_bd->format('Y-m-d');

            header("Location: ClientAcc.php");
            exit();
        } else {
            $error = "Неверный email или пароль.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход клиента</title>
    <link rel="stylesheet" href="style.css">
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
    <h2>Вход в аккаунт</h2>
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
        <p>Нет аккаунта? <a href="clients.php">Зарегистрироваться</a></p>
    </form>
</section>
</body>
</html>