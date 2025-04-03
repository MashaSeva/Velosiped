<?php
// Include the necessary classes and establish a database connection
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

// CRUD operations based on requests

// Handle form submissions for Orders
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add_order') {
        $order = new Order();
        $order->id_client = $_POST['id_client'];
        $order->id_staff = $_POST['id_staff'];
        $order->adress = $_POST['address'];
        $order->payment = PaymentType::fromName($_POST['payment']);
        $order->data_order = new DateTime($_POST['data_order']);
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

// Fetch all orders for displaying and editing
$orders = $ordersTable->readAll();
$clients = $clientsTable->readAll();
$staffMembers = $staffTable->readAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orders Management</title>
</head>

<body>
    <h1>Manage Orders</h1>

    <h2>Add Order</h2>
    <form method="post">
        <!-- Order form fields -->
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
            // Fetch all products from ProductsTable
            $productsTable = $db_rep->products; // You would need to instantiate this if not already done
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
        <button type="submit">Add Order</button>
    </form>

    <h2>View and Edit Orders</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Client ID</th>
            <th>Staff ID</th>
            <th>Products</th>
            <th>Address</th>
            <th>Payment</th>
            <th>Data Order</th>
            <th>Status</th>
            <th>Sum</th>
            <th>Actions</th>
        </tr>
        <?php
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>" . $order->id_order . "</td>";
            // Fetch and display client name based on ID
            $client = $clientsTable->read($order->id_client);
            echo "<td>" . $client->name . "</td>";

            // Fetch and display staff member name based on ID
            $staff = $staffTable->read($order->id_staff);
            echo "<td>" . $staff->name . "</td>";

            $productsList = [];

            $elements = $elementsTable->readElementsByOrderId($order->id_order);

            foreach ($elements as $element) {
                $product = $productsTable->read($element->id_product);

                // Add product information to the productsList array
                $productsList[] = $product->name_product; // You can include more details here
            }

            // Print all products in a single <td> tag
            echo '<td>' . implode(', ', $productsList) . '</td>';

            echo "<td>" . $order->adress . "</td>";
            echo "<td>" . $order->payment->name . "</td>";
            echo "<td>" . $order->data_order->format('Y-m-d') . "</td>";
            echo "<td>" . $order->status . "</td>";
            echo "<td>" . $order->sum . "</td>";
            echo "<td>
                    <!-- Delete form -->
                    <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this order?\")'>
                        <input type='hidden' name='id_order' value='" . $order->id_order . "'>
                        <input type='hidden' name='action' value='delete_order'>
                        <button type='submit'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

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