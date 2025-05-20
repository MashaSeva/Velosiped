<!DOCTYPE html>
<html>
<head>
    <title>Redirect Page</title>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">

</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo.jpg" alt="Logo"> 
    </div>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="staff.php">Сотрудники</a>
        <a href="clients.php">Клиенты</a>



    </nav>
</header>

<section class="hero">
    <img src="img/main.png" alt="Главное изображение">
</section>

    

    <?php
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
        
        switch($page) {
            case 'clients':
                header("Location: clients.php");
                exit();
            case 'orders':
                header("Location: orders.php");
                exit();
            case 'products':
                header("Location: products.php");
                exit();
            case 'staff':
                header("Location: staff.php");
                exit();
            default:
                echo "Invalid page requested";
        }
    }
    ?>

</body>
</html>