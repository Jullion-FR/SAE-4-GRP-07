<?php

class Database
{

    public function connect()
    {   
        $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
        return $conn;
    }

    public function query($sql, $types = "", $args = [])
    {
        // types est un string qui contient les types des arguments
        // Par ex : "ssds" signifie que les 4 arguments sont de type string, string, decimal, string
        $conn = $this->connect();

        $stmt = $conn->prepare($sql);
        
        if (!empty($types))
        {
            $stmt->bind_param($types, ...$args);
        }

        $stmt->execute();

        $id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return $id;
    }

    public function callProcedure($procedureCall, $types, $args){

        $conn = $this->connect();
        $stmt = $conn->prepare("CALL " . $procedureCall);
        
        if (!empty($types))
        {
            $stmt->bind_param($types, ...$args);
        }
        $stmt->execute();
        
        // Récupérer le résultat
        $result = "";
        $stmt->bind_result($result);
        $stmt->fetch();
        
        $stmt->close();
        
        return $result;
        
    }

    public function select($sql, $types = "", $args = [])
    {
        $conn = $this->connect();

        $stmt = $conn->prepare($sql);
        if (!empty($types))
        {
            $stmt->bind_param($types, ...$args);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        return Database::cleanResults($data);
    }

    private static function cleanResults(array $results): array
    {
        return array_map(function ($row) {
            return array_map('htmlspecialchars', $row);
        }, $results);
    }

}
?>