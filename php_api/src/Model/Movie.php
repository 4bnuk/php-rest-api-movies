<?php

class Movie {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findAll() {
        $query = "
                SELECT m.id, m.title, m.description, m.release_date, g.name as genre, CONCAT(d.firstname, ' ', d.lastname) as director
                FROM movies m,directors d,genres g
                WHERE m.genre_id=g.id AND m.director_id=d.id
                ORDER BY m.release_date
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function find($id) {
        $query = "
                SELECT m.id, m.title, m.description, m.release_date, g.name as genre, CONCAT(d.firstname, ' ', d.lastname) as director
                FROM movies m,directors d,genres g
                WHERE m.genre_id=g.id 
                AND m.director_id=d.id
                AND m.id = :id
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

    public function create($input) {
        $query = "
                INSERT INTO movies (genre_id, title, description, director_id, release_date)
                VALUES (:genre_id, :title, :description, :director_id, :release_date)
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":genre_id", $input["genre_id"]);
            $stmt->bindParam(":title", $input["title"]);
            $stmt->bindParam(":description", $input["description"]);
            $stmt->bindParam(":director_id", $input["director_id"]);
            $stmt->bindParam(":release_date", $input["release_date"]);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function update($id, $input) {
        $query = "
                UPDATE movies
                SET 
                    id = :id,
                    genre_id = :genre_id, 
                    title = :title, 
                    description = :description, 
                    director_id = :director_id,
                    release_date = :release_date
                WHERE id = :id
                ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("id", $id);
            $stmt->bindParam(":genre_id", $input["genre_id"]);
            $stmt->bindParam(":title", $input["title"]);
            $stmt->bindParam(":description", $input["description"]);
            $stmt->bindParam(":director_id", $input["director_id"]);
            $stmt->bindParam(":release_date", $input["release_date"]);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function delete($id) {
        $query = "
                DELETE FROM movies
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