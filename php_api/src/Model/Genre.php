<?php

class Genre {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findAll()
    {
        $query = "
                SELECT *
                FROM genres
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
                FROM genres 
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
                INSERT INTO genres (name)
                VALUES (:name)
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $input["name"]);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function update($id, $input)
    {
        $query = "
                UPDATE genres
                SET 
                    id = :id,
                    name = :name
                WHERE id = :id
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("id", $id);
            $stmt->bindParam(":name", $input["name"]);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function delete($id)
    {
        $query = "
                DELETE FROM genres
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