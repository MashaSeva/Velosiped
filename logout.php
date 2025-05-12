<?php
session_start();

// Определяем, откуда перенаправлять после выхода
$redirect_to = 'login_client.php'; 

if (isset($_SESSION['staff_id'])) {
    $redirect_to = 'login_staff.php';
}

// Уничтожаем сессию
session_unset();
session_destroy();

// Перенаправляем на соответствующую страницу входа
header("Location: " . $redirect_to);
exit();
?>