<?php

require_once 'src/models/client.php';
require_once 'src/clients_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();

// Initialize the ClientsTable object
$clientsTable = $db_rep->clients;

// CRUD operations based on requests

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $client = new Client();
        $client->name = $_POST['name'];
        $client->tel = $_POST['tel'];
        $client->email = $_POST['email'];
        $client->password = $_POST['password'];
        $client->data_bd = new DateTime($_POST['data_bd']);

        $clientsTable->create($client);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_save') {
        $client = new Client();
        $client->id_client = $_POST['id_client'];
        $client->name = $_POST['name'];
        $client->tel = $_POST['tel'];
        $client->email = $_POST['email'];
        $client->password = $_POST['password'];
        $client->data_bd = new DateTime($_POST['data_bd']);

        $clientsTable->update($client);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $client_id = $_POST['id'];
        $clientsTable->delete($client_id);
    }
}

// Fetch all clients for displaying and editing
$clients = $clientsTable->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clients Management</title>
</head>
<body>
    <h1>Manage Clients</h1>

    <h2>Add Client</h2>
    <form method="post"> 
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="tel" placeholder="Telephone" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="date" name="data_bd" required>
        <input type='hidden' name='action' value='add'>
        <button type="submit">Add Client</button>
    </form>

    <h2>View and Edit Clients</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Telephone</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php
        foreach ($clients as $client) {
            echo "<tr>";
            echo "<td>" . $client->id_client . "</td>";
            echo "<td>" . $client->name . "</td>";
            echo "<td>" . $client->tel . "</td>";
            echo "<td>" . $client->email . "</td>";
            echo "<td>
                    <form method='post'>
                        <input type='hidden' name='id_client' value='" . $client->id_client . "'>
                        <input type='hidden' name='action' value='edit'>
                        <button type='submit'>Edit</button>
                    </form>
                    <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this client?\")'>
                        <input type='hidden' name='id' value='" . $client->id_client . "'>
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
        $client_id = $_POST['id_client'];
        $client = $clientsTable->read($client_id); // Fetch the client details by ID

        // Display the edit form with pre-filled values
        echo "
        <h2>Edit Client</h2>
        <form method='post'>
            <input type='hidden' name='id_client' value='" . $client->id_client . "'>
            <input type='text' name='name' value='" . $client->name . "' required>
            <input type='text' name='tel' value='" . $client->tel . "' required>
            <input type='email' name='email' value='" . $client->email . "' required>
            <input type='password' name='password' value='" . $client->password . "' required>
            <input type='date' name='data_bd' value='" . $client->data_bd->format('Y-m-d') . "' required>
            <input type='hidden' name='action' value='edit_save'>
            <button type='submit' name='edit'>Save Changes</button>
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
</body>
</html>
