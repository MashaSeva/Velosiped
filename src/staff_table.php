<?php

include_once "models/staff.php";

class StaffTable
{
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function create(Staff $staff): bool
    {
        $stmt = $this->connection->prepare("INSERT INTO staff (Name, Tel_Staff, title, Password_Staff) VALUES (?, ?, ?, ?)");
        $title = $staff->title ? 1 : 0;
        $stmt->bind_param("ssis", $staff->name, $staff->tel_staff, $title, $staff->password);

        return $stmt->execute();
    }

    public function read($id): ?Staff
    {
        $stmt = $this->connection->prepare("SELECT * FROM staff WHERE ID_Staff = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null; // Staff member not found
        }

        $data = $result->fetch_assoc();
        return $this->mapRowToStaff($data);
    }

    public function readAll(): array
    {
        $result = $this->connection->query("SELECT * FROM staff");
        $staffList = [];

        while ($data = $result->fetch_assoc()) {
            $staffList[] = $this->mapRowToStaff($data);
        }

        return $staffList;
    }

    public function update(Staff $staff): bool
    {
        $stmt = $this->connection->prepare("UPDATE staff SET Name = ?, Tel_Staff = ?, title = ?, Password_Staff = ? WHERE ID_Staff = ?");
        $val = $staff->title->value;
        $stmt->bind_param("ssisi", $staff->name, $staff->tel_staff, $val, $staff->password, $staff->id_staff);
        return $stmt->execute();
    }

    public function delete($id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM staff WHERE ID_Staff = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    private function mapRowToStaff(array $row): Staff
    {
        $staff = new Staff();
        $staff->id_staff = $row['ID_Staff'];
        $staff->name = $row['Name'];
        $staff->password = $row['Password_Staff'];
        $staff->tel_staff = $row['Tel_Staff']; 
	   $staff->title = (bool)$row['Title'];


    return $staff;

    }

public function findByPhone(string $phone): ?Staff
{
    $stmt = $this->connection->prepare("SELECT * FROM staff WHERE Tel_Staff = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    return $this->mapRowToStaff($result->fetch_assoc());
}
}
?>