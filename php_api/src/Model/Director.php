<?php

class Director {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findAll()
    {
        $query = "
                SELECT *
                FROM directors
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function find($id)
    {
        $query = "
                SELECT *
                FROM directors
                WHERE id = :id
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function create($input)
    {
        $query = "
                INSERT INTO directors (firstname, lastname, birthdate)
                VALUES (:firstname, :lastname, :birthdate)
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":firstname", $input["firstname"]);
            $stmt->bindParam(":lastname", $input["lastname"]);
            $stmt->bindParam(":birthdate", $input["birthdate"]);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function update($id, $input)
    {
        $query = "
                UPDATE directors
                SET 
                    id = :id,
                    firstname = :firstname, 
                    lastname = :lastname, 
                    birthdate = :birthdate 
                WHERE id = :id
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("id", $id);
            $stmt->bindParam(":firstname", $input["firstname"]);
            $stmt->bindParam(":lastname", $input["lastname"]);
            $stmt->bindParam(":birthdate", $input["birthdate"]);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function delete($id)
    {
        $query = "
                DELETE FROM directors
                WHERE id = :id
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }
}
