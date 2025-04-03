<?php

include_once "models/orders.php";

class OrdersTable
{
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function create(Order $order): int
    {
        $stmt = $this->connection->prepare("INSERT INTO orders (ID_client, ID_Staff, Adress, Payment, Sum, Data_Order, Status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $date = $order->data_order->format('Y-m-d');
        $val = $order->payment->value;
        $stmt->bind_param("iisdsis", $order->id_client, $order->id_staff, $order->adress, $val, $order->sum, $date, $order->status);

        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows > 0) {
            // Return the ID of the newly inserted item
            return $stmt->insert_id;
        } else {
            // Return 0 or handle the error case as needed
            return 0;
        }
    }

    public function read($id): ?Order
    {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE ID_Order = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null; // Order not found
        }

        $data = $result->fetch_assoc();
        return $this->mapRowToOrder($data);
    }

    public function readAll(): array
    {
        $result = $this->connection->query("SELECT * FROM orders");
        $orderList = [];

        while ($data = $result->fetch_assoc()) {
            $orderList[] = $this->mapRowToOrder($data);
        }

        return $orderList;
    }

    public function update(Order $order): bool
    {
        $stmt = $this->connection->prepare("UPDATE orders SET ID_client = ?, ID_Staff = ?, Adress = ?, Payment = ?, Sum = ?, Data_Order = ?, Status = ? WHERE ID_Order = ?");
        $stmt->bind_param("iiisdsis", $order->id_client, $order->id_staff, $order->adress, $order->payment, $order->sum, $order->data_order->format('Y-m-d'), $order->status, $order->id_order);

        return $stmt->execute();
    }

    public function delete($id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM orders WHERE ID_Order = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    private function mapRowToOrder(array $row): Order
    {
        $order = new Order();
        $order->id_order = $row['ID_Order'];
        $order->id_client = $row['ID_client'];
        $order->id_staff = $row['ID_Staff'];
        $order->adress = $row['Adress'];
        $order->payment = match ($row['Payment']) {
            '1' => PaymentType::Card,
            '2' => PaymentType::Cash,
        };
        $order->data_order = new DateTime($row['Data_Order']);
        $order->status = $row['Status'];
        $order->sum = $row['Sum'];

        return $order;
    }
}
?>