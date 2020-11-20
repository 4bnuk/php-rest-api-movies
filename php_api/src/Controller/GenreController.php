<?php

class GenreController {
    private $requestMethod;
    private $genreId;
    private $genre;

    public function __construct($conn, $requestMethod, $genreId) {
        $this->conn = $conn;
        $this->requestMethod = $requestMethod;
        $this->genreId = $genreId;
        $this->genre = new Genre($conn);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case "GET":
                if ($this->genreId) {
                    $response = $this->getGenre($this->genreId);
                } else {
                    $response = $this->getAllGenres();
                }
                break;
            case "POST":
                $response = $this->createGenre();
                break;
            case "PUT":
                $response = $this->updateGenre($this->genreId);
                break;
            case "DELETE":
                $response = $this->deleteGenre($this->genreId);
                break;
            default:
                $response = $this->notFound();
                break;
        }
        header($response["status_code_header"]);
        if ($response["body"]) {
            echo $response["body"];
        }
    }

    private function getAllGenres()
    {
        $result = $this->genre->findAll();
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function getGenre($genreId)
    {
        $result = $this->genre->find($genreId);
        if (!$result) {
            return $this->notFound();
        }
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function createGenre()
    {
        $input = json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateInput($input)) {
            return $this->unprocessableEntity();
        }
        $this->genre->create($input);
        $response["status_code_header"] = "HTTP/1.1 201 Created";
        $response["body"] = json_encode(array("message" => "Genre Inserted Successfully"));
        return $response;
    }

    private function updateGenre($genreId)
    {
        $result = $this->genre->find($genreId);
        if (!$result) {
            return $this->notFound();
        }
        $input = json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateInput($input)) {
            return $this->unprocessableEntity();
        }
        $this->genre->update($genreId, $input);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode(array("message" => "Genre Updated Successfully"));
        return $response;
    }

    private function deleteGenre($genreId)
    {
        $result = $this->genre->find($genreId);
        if (!$result) {
            return $this->notFound();
        }
        $this->genre->delete($genreId);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode(array("message" => "Genre Deleted Successfully"));
        return $response;
    }

    private function validateInput($input)
    {
        if (!isset($input["name"])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntity()
    {
        $response["status_code_header"] = "HTTP/1.1 422 Unprocessable Entity";
        $response["body"] = json_encode(array("error" => "Invalid Input"));
        return $response;
    }

    private function notFound()
    {
        $response["status_code_header"] = "HTTP/1.1 404 Not Found";
        $response["body"] = json_encode(array("error" => "Genre Not Found"));
        return $response;
    }
}