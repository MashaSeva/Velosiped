<?php
// Include the necessary classes
require_once 'src/models/client.php';
require_once 'src/clients_table.php';
require_once 'src/db_rep.php';

$db_rep = new DbRepository();

// Initialize the ClientsTable object
$staffTable = $db_rep->staff; // Assuming the connection property is accessible

// CRUD operations based on requests
// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add a staff member
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $staff = new Staff();
        $staff->name = $_POST['name'];
        $staff->tel_staff = $_POST['tel_staff'];
        $staff->password = $_POST['password'];
        $staff->title = TitleType::fromName($_POST['title']); // Assuming this is passed correctly

        $staffTable->create($staff);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_save') {
        $staff = new Staff();
        $staff->id_staff = $_POST['id_staff'];
        $staff->name = $_POST['name'];
        $staff->tel_staff = $_POST['tel_staff'];
        $staff->password = $_POST['password'];
        $staff->title = TitleType::fromName($_POST['title']);

        $staffTable->update($staff);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_staff'];
        $staffTable->delete($id);
    }
}

// Fetch all staff members for displaying and editing
$staffMembers = $staffTable->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Management</title>
</head>
<body>
    <h1>Manage Staff Members</h1>

    <h2>Add Staff Member</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="tel_staff" placeholder="Telephone" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="title">
    <option value="Administrator">Administrator</option>
    <option value="Courier">Courier</option>
</select>

            
        <input type='hidden' name='action' value='add'>
        <button type="submit">Add Staff Member</button>
    </form>

    <h2>View and Edit Staff Members</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Telephone</th>
            <th>Title</th>
            <th>Actions</th>
        </tr>
        <?php
        foreach ($staffMembers as $staff) {
            echo "<tr>";
            echo "<td>" . $staff->id_staff . "</td>";
            echo "<td>" . $staff->name . "</td>";
            echo "<td>" . $staff->tel_staff . "</td>";
            echo "<td>" . $staff->title->name . "</td>";
            echo "<td>
                    <form method='post'>
                        <input type='hidden' name='id_staff' value='" . $staff->id_staff . "'>
                        <input type='hidden' name='action' value='edit'>
                        <button type='submit'>Edit</button>
                    </form>
                    <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this staff member?\")'>
                        <input type='hidden' name='id_staff' value='" . $staff->id_staff . "'>
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
        $staff_id = $_POST['id_staff'];
        $staff = $staffTable->read($staff_id); // Fetch the staff member details by ID

        // Display the edit form with pre-filled values
        echo "
        <h2>Edit Staff Member</h2>
        <form method='post'>
            <input type='hidden' name='id_staff' value='" . $staff->id_staff . "'>
            <input type='text' name='name' value='" . $staff->name . "' required>
            <input type='text' name='tel_staff' value='" . $staff->tel_staff . "' required>
            <select name='title'>
                <option value='Administrator' " . ($staff->title == 'Administrator' ? 'selected' : '') . ">Administrator</option>
                <option value='Courier' " . ($staff->title == 'Courier' ? 'selected' : '') . ">Courier</option>
                
            </select>
            
            <input type='password' name='password' value='" . $staff->password . "' required>
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
</html>
