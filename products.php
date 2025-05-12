<?php
require_once 'src/models/product.php';
require_once 'src/products_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();

$productsTable = $db_rep->products;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $product = new Product();
        $product->produser = $_POST['produser'];
        $product->name_product = $_POST['name_product'];
        $product->data_end = new DateTime($_POST['data_end']);
        $product->price = $_POST['price'];
        $product->weight = $_POST['weight'];

        $productsTable->create($product);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_save') {
        $product = new Product();
        $product->id_product = $_POST['id_product'];
        $product->produser = $_POST['produser'];
        $product->name_product = $_POST['name_product'];
        $product->data_end = new DateTime($_POST['data_end']);
        $product->price = $_POST['price'];
        $product->weight = $_POST['weight'];

        $productsTable->update($product);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id_product = $_POST['id_product'];
        $productsTable->delete($id_product);
    }
}

$products = $productsTable->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Products</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo.jpg" alt="Logo"> 
    </div>
<h1>Продукты</h1>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="staff.php">Сотрудники</a>
        <a href="clients.php">Клиенты</a>
	   <a href="orders.php">Заказы</a>
	   <a href="products.php">Продукты</a>
	   <a href="element.php">Элементы</a>
    </nav>
</header>

<section class='registration'>
<h2>Добавление продукта</h2>
<form method="post">
    <input type="text" name="produser" placeholder="Производитель" required>
    <input type="text" name="name_product" placeholder="Название" required>
    <input type="date" name="data_end" required>
    <input type="number" step="0.01" name="price" placeholder="Цена" required>
    <input type="number" step="0.01" name="weight" placeholder="Вес" required>
    <input type='hidden' name='action' value='add'>
    <button type="submit">Добавить продукта</button>
</form>
</section>

<h2>Список продуктов</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Производитель</th>
        <th>Название</th>
        <th>Годен до</th>
        <th>Цена</th>
        <th>Вес</th>
        <th></th>
    </tr>
    <?php
    foreach ($products as $product) {
        echo "<tr>";
        echo "<td>" . $product->id_product . "</td>";
        echo "<td>" . $product->produser . "</td>";
        echo "<td>" . $product->name_product . "</td>";
        echo "<td>" . $product->data_end->format('Y-m-d') . "</td>";
        echo "<td>" . $product->price . "</td>";
        echo "<td>" . $product->weight . "</td>";
        echo "<td>
            <form method='post'>
                <input type='hidden' name='id_product' value='" . $product->id_product . "'>
                <input type='hidden' name='action' value='edit'>
                <button type='submit' class='edit-btn'>Редактировать</button>
            </form>
            <form method='post' onsubmit='return confirm(\"Вы уверены, что хотите удалить этот продукт?\")'>
                <input type='hidden' name='id_product' value='" . $product->id_product . "'>
                <input type='hidden' name='action' value='delete'>
                <button type='submit' class='delete-btn'>Удалить</button>
            </form>
          </td>";
        echo "</tr>";
    }
    ?>
</table>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $product_id = $_POST['id_product'];
    $product = $productsTable->read($product_id);
    echo "
<section class='registration'>
    <h2>Редактирование продукта</h2>
    <form method='post'>
        <input type='hidden' name='id_product' value='" . $product->id_product . "'>
        <input type='text' name='produser' value='" . $product->produser . "' required>
        <input type='text' name='name_product' value='" . $product->name_product . "' required>
        <input type='date' name='data_end' value='" . $product->data_end->format('Y-m-d') . "' required>
        <input type='number' step='0.01' name='price' value='" . $product->price . "' required>
        <input type='number' step='0.01' name='weight' value='" . $product->weight . "' required>
        <input type='hidden' name='action' value='edit_save'>
        <button type='submit'>Сохранить изменения</button>
    </form>
</section>";
}
?>


</body>
</html>