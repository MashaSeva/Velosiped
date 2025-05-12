<?php
session_start();

if (!isset($_SESSION["client_id"])) {
    header("Location: login_client.php");
    exit();
}

require_once 'src/db_rep.php';
require_once 'src/clients_table.php';

$db_rep = new DbRepository();
$clientsTable = $db_rep->clients;

$client = $clientsTable->read($_SESSION["client_id"]);

$errors = [];
$success = false;
$edit_mode = isset($_GET['edit']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');

    // Валидация
    if (empty($name)) $errors[] = "Имя обязательно для заполнения";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email";
    if (empty($tel)) $errors[] = "Телефон обязателен для заполнения";

    if (empty($errors)) {
        try {
            $client->name = $name;
            $client->email = $email;
            $client->tel = $tel;
            $client->data_bd = new DateTime($birth_date);
            
            $clientsTable->update($client);
            
            // Обновляем сессию
            $_SESSION["client_name"] = $client->name;
            $_SESSION["client_email"] = $client->email;
            $_SESSION["client_tel"] = $client->tel;
            $_SESSION["client_data_bd"] = $client->data_bd->format('Y-m-d');
            
            $success = true;
            $edit_mode = false; 
        } catch (Exception $e) {
            $errors[] = "Ошибка при обновлении данных: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой аккаунт</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .account-info {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .field-group {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .field-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }
        
        .field-value {
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .edit-form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .account-actions {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn-edit {
            background: #3498db;
            color: white;
            border: none;
        }
        
        .btn-save {
            background: #2ecc71;
            color: white;
            border: none;
        }
        
        .btn-cancel {
            background: #e74c3c;
            color: white;
            border: none;
        }
        
        .btn-logout {
            background: #f39c12;
            color: white;
            border: none;
        }
        
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
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

    <div class="account-info">
        <?php if ($success): ?>
            <div class="alert alert-success">Данные успешно обновлены!</div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <h2>Личная информация</h2>
        
        <?php if (!$edit_mode): ?>
            <!-- Режим просмотра -->
            <div class="field-group">
                <span class="field-label">Имя:</span>
                <div class="field-value"><?php echo htmlspecialchars($client->name); ?></div>
            </div>
            
            <div class="field-group">
                <span class="field-label">Email:</span>
                <div class="field-value"><?php echo htmlspecialchars($client->email); ?></div>
            </div>
            
            <div class="field-group">
                <span class="field-label">Телефон:</span>
                <div class="field-value"><?php echo htmlspecialchars($client->tel); ?></div>
            </div>
            
            <div class="field-group">
                <span class="field-label">Дата рождения:</span>
                <div class="field-value"><?php echo $client->data_bd->format('d.m.Y'); ?></div>
            </div>
            
            <div class="account-actions">
                <a href="?edit=1" class="btn btn-edit">Редактировать</a>
                <a href="login_client.php" class="btn btn-logout">Выйти</a>
            </div>
            
        <?php else: ?>
            <!-- Режим редактирования -->
            <form method="post" class="edit-form">
                <div class="field-group">
                    <label for="name" class="field-label">Имя:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($client->name); ?>" required>
                </div>
                
                <div class="field-group">
                    <label for="email" class="field-label">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client->email); ?>" required>
                </div>
                
                <div class="field-group">
                    <label for="tel" class="field-label">Телефон:</label>
                    <input type="tel" id="tel" name="tel" value="<?php echo htmlspecialchars($client->tel); ?>" required>
                </div>
                
                <div class="field-group">
                    <label for="birth_date" class="field-label">Дата рождения:</label>
                    <input type="date" id="birth_date" name="birth_date" 
                           value="<?php echo $client->data_bd->format('Y-m-d'); ?>" required>
                </div>
                
                <div class="account-actions">
                    <button type="submit" name="save_profile" class="btn btn-save">Сохранить</button>
                    <a href="ClientAcc.php" class="btn btn-cancel">Отмена</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>