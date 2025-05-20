<?php

include_once "models/product.php";

class ProductsTable
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function create(Product $product)
    {
        $produser = $product->produser;
        $name_product = $product->name_product;
        $data_end = $product->data_end->format('Y-m-d');
        $price = $product->price;
        $weight = $product->weight;

        $query = "INSERT INTO product (Produser, Name_Product, Data_End, Price, weight) 
                  VALUES ('$produser', '$name_product', '$data_end', '$price', '$weight')";

        return $this->connection->query($query);
    }

    public function readAll()
    {
        $products = [];

        $query = "SELECT * FROM product";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product = new Product();
                $product->id_product = $row['ID_Product'];
                $product->produser = $row['Produser'];
                $product->name_product = $row['Name_Product'];
                $product->data_end = new DateTime($row['Data_End']);
                $product->price = $row['Price'];
                $product->weight = $row['weight'];

                $products[] = $product;
            }
        }

        return $products;
    }

    public function read($id_product)
    {
        $query = "SELECT * FROM product WHERE ID_Product = '$id_product'";
        $result = $this->connection->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $product = new Product();
            $product->id_product = $row['ID_Product'];
            $product->produser = $row['Produser'];
            $product->name_product = $row['Name_Product'];
            $product->data_end = new DateTime($row['Data_End']);
            $product->price = $row['Price'];
            $product->weight = $row['weight'];

            return $product;
        }

        return null;
    }

    public function update(Product $product)
    {
        $id_product = $product->id_product;
        $produser = $product->produser;
        $name_product = $product->name_product;
        $data_end = $product->data_end->format('Y-m-d');
        $price = $product->price;
        $weight = $product->weight;

        $query = "UPDATE product SET Produser='$produser', Name_Product='$name_product', 
                  Data_End='$data_end', Price='$price', weight='$weight' WHERE ID_Product='$id_product'";

        return $this->connection->query($query);
    }

    public function delete($id_product)
    {
        $query = "DELETE FROM product WHERE ID_Product = '$id_product'";
        
        return $this->connection->query($query);
    }
}
?>