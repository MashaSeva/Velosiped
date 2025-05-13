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

        
        if ($stmt->affected_rows > 0) {
            return $stmt->insert_id;
        } else {
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
            return null;
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
        $order->data_order = new Date($row['Data_Order']);
        $order->status = $row['Status'];
        $order->sum = $row['Sum'];

        return $order;
    }

public function getClientOrders($clientId)
{
    $query = "SELECT ID_Order, Data_Order, Adress, Sum, Status 
              FROM `orders` 
              WHERE ID_client = ? 
              ORDER BY Data_Order DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while ($order = $result->fetch_assoc()) {
        $orders[] = $order;
    }
    
    return $orders;
}

public function getDeliveryStatistics(string $startDate, string $endDate): array
{
    $query = "SELECT 
                COUNT(ID_Order) as order_count,
                SUM(Sum) as total_amount,
                AVG(Sum) as avg_amount
              FROM `orders`
              WHERE Status = 'Доставлен'
                AND Data_Order BETWEEN ? AND ?
              ORDER BY order_count DESC";
    
    $stmt = $this->connection->prepare($query);
    $endDate = $endDate ;
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $stats[] = [
            'order_count' => $row['order_count'],
            'total_amount' => $row['total_amount'],
            'avg_amount' => $row['avg_amount']
        ];
    }
    
    return $stats;
}

public function getOrdersByCourier($courierId)
{
    $query = "SELECT * FROM `orders` WHERE ID_Staff = ? AND Status IN ('В доставке', 'Доставлен')";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("i", $courierId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while ($order = $result->fetch_assoc()) {
        $orders[] = $order;
    }
    
    return $orders;
}
public function getClientOrderStats(string $startDate, string $endDate): array
{
    $query = "SELECT 
                ID_client as client_id,
                COUNT(ID_Order) as order_count,
                AVG(Sum) as avg_amount,
                SUM(Sum) as total_amount
              FROM `orders`
              WHERE Data_Order BETWEEN ? AND ?
              GROUP BY ID_client
              ORDER BY avg_amount DESC";
    
    $stmt = $this->connection->prepare($query);
    $endDate = $endDate . ' 23:59:59';
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $stats[] = $row;
    }
    
    return $stats;
}
public function getCourierStatistics(string $startDate, string $endDate): array
{
    $query = "SELECT 
                s.ID_Staff,
                s.Name as courier_name,
                COUNT(o.ID_Order) as order_count,
                SUM(o.Sum) as total_amount
               
              FROM `orders` o
              JOIN staff s ON o.ID_Staff = s.ID_Staff
              WHERE  o.Status = 'Доставлен'
                AND o.Data_Order BETWEEN ? AND ?
              GROUP BY s.ID_Staff
              ORDER BY order_count DESC";
    
    $stmt = $this->connection->prepare($query);
    $endDate = $endDate . ' 23:59:59';
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $stats[] = [
            $row['order_count'],
            $row['total_amount'],
            number_format($row['total_amount'], 2) . ' ₽',
           
        ];
    }
    
    return $stats;
}
}
?>