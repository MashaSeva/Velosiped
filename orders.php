<?php
require_once 'src/models/orders.php';
require_once 'src/orders_table.php';
require_once 'src/models/element.php';
require_once 'src/elements_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();
$ordersTable = $db_rep->orders;
$elementsTable = $db_rep->elements;
$clientsTable = $db_rep->clients;
$staffTable = $db_rep->staff;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add_order') {
        $order = new Order();
        $order->id_client = $_POST['id_client'];
        $order->id_staff = $_POST['id_staff'];
        $order->adress = $_POST['address'];
        $order->payment = PaymentType::fromName($_POST['payment']);
        $order->data_order = new Date($_POST['data_order']);
        $order->status = $_POST['status'];
        $order->sum = $_POST['sum'];

        $orderId = $ordersTable->create($order);

        foreach ($_POST['product_ids'] as $product_id) {
            $element = new Element();
            $element->id_order = $orderId;
            $element->id_product = $product_id;

            $elementsTable->createElement($element);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_order') {
        $id = $_POST['id_order'];
        $ordersTable->delete($id);
    }
}

$orders = $ordersTable->readAll();
$clients = $clientsTable->readAll();
$staffMembers = $staffTable->readAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Orders</title>
</head>

<body>
<header>
    <div class="logo">
        <img src="img/logo.jpg" alt="Logo"> 
    </div>
<h1>Заказы</h1>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="staff.php">Сотрудники</a>
        <a href="clients.php">Клиенты</a>
	   <a href="orders.php">Заказы</a>
	   <a href="products.php">Продукты</a>
	   <a href="element.php">Элементы</a>
    </nav>
</header>

<section class="registration">
    <h2>Добавление заказов</h2>
    <form method="post">
        <select name="id_client">
            <?php
            foreach ($clients as $client) {
                echo "<option value='" . $client->id_client . "'>" . $client->name . "</option>";
            }
            ?>
        </select>
        <select name="id_staff">
            <?php
            foreach ($staffMembers as $staff) {
                echo "<option value='" . $staff->id_staff . "'>" . $staff->name . "</option>";
            }
            ?>
        </select>

        <select name="product_ids[]" multiple>
            <?php
            
            $productsTable = $db_rep->products; 
            $products = $productsTable->readAll();

            foreach ($products as $product) {
                echo "<option value='" . $product->id_product . "'>" . $product->name_product . "</option>";
            }
            ?>
        </select>

        <input type="text" name="address" placeholder="Address" required>
        <select name="payment">
            <option value="Card">Card</option>
            <option value="Cash">Cash</option>
        </select>
        <input type="date" name="data_order" required>
        <input type="text" name="status" placeholder="Status" required>
        <input type="number" step="0.01" name="sum" placeholder="Sum" required>
        <input type='hidden' name='action' value='add_order'>
        <button type="submit">Добавить заказ</button>
    </form>
</section>

    <h2>Список заказов</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Курьер</th>
            <th>Продукт</th>
            <th>Адрес</th>
            <th>Способ оплаты</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Сумма</th>
            <th></th>
        </tr>
        <?php
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>" . $order->id_order . "</td>";
            $client = $clientsTable->read($order->id_client);
            echo "<td>" . $client->name . "</td>";

            $staff = $staffTable->read($order->id_staff);
            echo "<td>" . $staff->name . "</td>";

            $productsList = [];

            $elements = $elementsTable->readElementsByOrderId($order->id_order);

            foreach ($elements as $element) {
                $product = $productsTable->read($element->id_product);

               
                $productsList[] = $product->name_product; 
            }

            echo '<td>' . implode(', ', $productsList) . '</td>';

            echo "<td>" . $order->adress . "</td>";
            echo "<td>" . $order->payment->name . "</td>";
            echo "<td>" . $order->data_order->format('Y-m-d') . "</td>";
            echo "<td>" . $order->status . "</td>";
            echo "<td>" . $order->sum . "</td>";
            echo "<td>
                    <form method='post'>
                        <input type='hidden' name='id_order' value='" . $order->id_order . "'>
                        <input type='hidden' name='action' value='edit'>
                        <button type='submit' class='edit-btn'>Редактировать</button>
                    </form>
                    <form method='post' onsubmit='return confirm(\"Вы уверены, что хотите удалить этого клиента?\")'>
                        <input type='hidden' name='id_order' value='" . $order->id_order . "'>
                        <input type='hidden' name='action' value='delete_order'>
                        <button type='submit' class='delete-btn'>Удалить</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

 <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
        $order_id = $_POST['id_order'];
        $order = $ordersTable->read($order_id);

      
        echo "
	   <section class='registration'>
        <h2>Редактирование заказа</h2>
        <form method='post'>
            <input type='hidden' name='id_order' value='" . $order->id_order . "'>
            <input type='text' name='id_client' value='" . $client->name . "' required>
            <input type='text' name='id_staff' value='" . $client->tel . "' required>
            <input type='email' name='adress' value='" . $client->email . "' required>
            <input type='password' name='payment' value='" . $client->password . "' required>
            <input type='date' name='Sum' value='" . $client->data_bd->format('Y-m-d') . "' required>
            <input type='date' name='Data_Order value='" . $client->data_bd->format('Y-m-d') . "' required>
            <input type='hidden' name='action' value='edit_save'>
            <button type='submit' name='edit'>Сохранить изменения</button>
        </form>
	</section>";
    }
    ?>
</body>

</html>