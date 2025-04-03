<?php

include_once "models/client.php";

class ClientsTable
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function create(Client $client)
    {
        $query = "INSERT INTO client (Name, Tel, Email, Password, Data_BD) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $data = $client->data_bd->format('Y-m-d');
        $stmt->bind_param("sssss", $client->name, $client->tel, $client->email, $client->password, $data);
        $stmt->execute();
    }

    public function read($id)
    {
        $query = "SELECT * FROM client WHERE ID_client = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $clientData = $result->fetch_assoc();
        $result->close();

        if ($clientData) {
            $client = new Client();
            $client->id_client = $clientData['id_client'];
            $client->name = $clientData['name'];
            $client->tel = $clientData['tel'];
            $client->email = $clientData['email'];
            $client->password = $clientData['password'];
            $client->data_bd = new DateTime($clientData['data_bd']);

            return $client;
        }

        return null;
    }

    public function readAll()
    {
        $clients = [];

        $query = "SELECT * FROM client";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $client = new Client();
                $client->id_client = $row['id_client'];
                $client->name = $row['name'];
                $client->tel = $row['tel'];
                $client->email = $row['email'];
                $client->password = $row['password'];
                $client->data_bd = new DateTime($row['data_bd']);

                $clients[] = $client;
            }
        }

        return $clients;
    }

    public function update(Client $client)
    {
        $query = "UPDATE client SET Name = ?, Tel = ?, Email = ?, Password = ?, Data_BD = ? WHERE ID_client = ?";
        $stmt = $this->connection->prepare($query);
        $data = $client->data_bd->format('Y-m-d');
        $stmt->bind_param("sssssi", $client->name, $client->tel, $client->email, $client->password, $data, $client->id_client);
        $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM client WHERE ID_client = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
?>