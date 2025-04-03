<?php
// Include the necessary classes
require_once 'src/models/product.php';
require_once 'src/products_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();

$productsTable = $db_rep->products;

// CRUD operations based on requests

// Handle form submissions
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

// Fetch all products for displaying and editing
$products = $productsTable->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Management</title>
</head>
<body>
<h1>Manage Products</h1>

<h2>Add Product</h2>
<form method="post">
    <input type="text" name="produser" placeholder="Producer" required>
    <input type="text" name="name_product" placeholder="Name" required>
    <input type="date" name="data_end" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="number" step="0.01" name="weight" placeholder="Weight" required>
    <input type='hidden' name='action' value='add'>
    <button type="submit">Add Product</button>
</form>

<h2>View and Edit Products</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Producer</th>
        <th>Name</th>
        <th>Date End</th>
        <th>Price</th>
        <th>Weight</th>
        <th>Actions</th>
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
                <button type='submit'>Edit</button>
            </form>
            <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this product?\")'>
                <input type='hidden' name='id_product' value='" . $product->id_product . "'>
                <input type='hidden' name='action' value='delete'>
                <button type='submit'>Delete</button>
            </form>
          </td>";
        echo "</tr>";
    }
    ?>
</table>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $product_id = $_POST['id_product'];
    $product = $productsTable->read($product_id); // Fetch the product details by ID

    // Display the edit form with pre-filled values
    echo "
    <h2>Edit Product</h2>
    <form method='post'>
        <input type='hidden' name='id_product' value='" . $product->id_product . "'>
        <input type='text' name='produser' value='" . $product->produser . "' required>
        <input type='text' name='name_product' value='" . $product->name_product . "' required>
        <input type='date' name='data_end' value='" . $product->data_end->format('Y-m-d') . "' required>
        <input type='number' step='0.01' name='price' value='" . $product->price . "' required>
        <input type='number' step='0.01' name='weight' value='" . $product->weight . "' required>
        <input type='hidden' name='action' value='edit_save'>
        <button type='submit'>Save Changes</button>
    </form>";
}
?>

<!-- Back button -->
<button onclick="goBack()">Go Back</button>

<script>
    // JavaScript function to go back to localhost
    function goBack() {
        window.location.href = 'http://localhost/лиза/index.php';
    }
</script>
</body>
</html>