<?php

include_once "clients_table.php";
include_once "staff_table.php";
include_once "orders_table.php";
include_once "products_table.php";
include_once "elements_table.php";

class DbRepository
{
    private const DB_ADDRESS = "mysql";
    private const DB_NAME = "root";
    private const DB_PASSWORD = "root";
    private const DB_DATABASE = "велосипед";

    private mysqli $db;

    public ClientsTable $clients;
	  private PDO $pdo;
    public ProductsTable $products;

    public StaffTable $staff;

    public OrdersTable $orders;

    public ElementsTable $elements;
    

    public function __construct()
    {
        $this->db = new mysqli(
            DbRepository::DB_ADDRESS,
            DbRepository::DB_NAME,
            DbRepository::DB_PASSWORD,
            DbRepository::DB_DATABASE
        );

        $this->clients = new ClientsTable($this->db);
        $this->products = new ProductsTable($this->db);
        $this->staff = new StaffTable($this->db);
        $this->orders = new OrdersTable($this->db);
        $this->elements = new ElementsTable($this->db);


    }
}
?>