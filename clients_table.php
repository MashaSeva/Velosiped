<?php

include_once "models/client.php";

class ClientsTable
{
    private PDO $pdo;
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

	public function findByEmail(string $email): ?Client
{
    $sql = "SELECT * FROM client WHERE email = ?";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    
    $result = $stmt->get_result(); 
    $row = $result->fetch_assoc();

    if ($row) {
        $client = new Client();
        $client->id_client = (int)$row['id_client'];
        $client->name = $row['name'];
        $client->tel = $row['tel'];
        $client->email = $row['email'];
        $client->data_bd = new DateTime($row['data_bd']);
        $client->password = $row['password'];
        return $client;
    }

    return null; 
}


public function getAgeStatistics(): array
{
    $query = "SELECT 
                AVG(YEAR(CURRENT_DATE) - YEAR(Data_BD)) as average_age,
                MIN(YEAR(CURRENT_DATE) - YEAR(Data_BD)) as min_age,
                MAX(YEAR(CURRENT_DATE) - YEAR(Data_BD)) as max_age,
                COUNT(*) as total_clients
              FROM client";
    
    $result = $this->connection->query($query);
    $stats = $result->fetch_assoc();
    
    $ageGroupsQuery = "SELECT 
                        CASE
                            WHEN YEAR(CURRENT_DATE) - YEAR(Data_BD) < 18 THEN 'Младше 18'
                            WHEN YEAR(CURRENT_DATE) - YEAR(Data_BD) BETWEEN 18 AND 25 THEN '18-25 лет'
                            WHEN YEAR(CURRENT_DATE) - YEAR(Data_BD) BETWEEN 26 AND 35 THEN '26-35 лет'
                            WHEN YEAR(CURRENT_DATE) - YEAR(Data_BD) BETWEEN 36 AND 45 THEN '36-45 лет'
                            WHEN YEAR(CURRENT_DATE) - YEAR(Data_BD) BETWEEN 46 AND 55 THEN '46-55 лет'
                            ELSE 'Старше 55 лет'
                        END as age_group,
                        COUNT(*) as count
                      FROM client
                      GROUP BY age_group
                      ORDER BY age_group";
    
    $groupsResult = $this->connection->query($ageGroupsQuery);
    $ageGroups = [];
    while ($row = $groupsResult->fetch_assoc()) {
        $ageGroups[$row['age_group']] = $row['count'];
    }
    
    $stats['age_groups'] = $ageGroups;
    
    return $stats;
}

}
?>