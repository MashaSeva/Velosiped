<!DOCTYPE html>
<html>
<head>
    <title>Redirect Page</title>
</head>
<body>
    <h1>Choose a Page to Visit:</h1>
    <form method="get" action="">
        <button type="submit" name="page" value="clients">Clients</button>
        <button type="submit" name="page" value="orders">Orders</button>
        <button type="submit" name="page" value="products">Products</button>
        <button type="submit" name="page" value="staff">Staff</button>
    </form>

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