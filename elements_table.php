<?php

include_once "models/element.php";

class ElementsTable
{
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function createElement(Element $element): bool
    {
        $stmt = $this->connection->prepare("INSERT INTO element (ID_Product, ID_Order) VALUES (?, ?)");
        $stmt->bind_param("ii", $element->id_product, $element->id_order);

        return $stmt->execute();
    }

    public function readElementsByOrderId($order_id): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM element WHERE ID_Order = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $elements = [];
        while ($row = $result->fetch_assoc()) {
            $elements[] = $this->mapRowToElement($row);
        }

        return $elements;
    }

    public function readElement($id): ?Element
    {
        $stmt = $this->connection->prepare("SELECT * FROM element WHERE ID_Element = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null; 
        }

        $data = $result->fetch_assoc();
        return $this->mapRowToElement($data);
    }

    public function updateElement(Element $element): bool
    {
        $stmt = $this->connection->prepare("UPDATE element SET ID_Product = ?, ID_Order = ? WHERE ID_Element = ?");
        $stmt->bind_param("iii", $element->id_product, $element->id_order, $element->id_element);

        return $stmt->execute();
    }

    public function deleteElement($id): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM element WHERE ID_Element = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    private function mapRowToElement(array $row): Element
    {
        $element = new Element();
        $element->id_element = $row['ID_Element'];
        $element->id_product = $row['ID_Product'];
        $element->id_order = $row['ID_Order'];

        return $element;
    }
}

?>