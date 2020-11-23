<?php

class MovieController {
    private $requestMethod;
    private $movieId;
    private $movie;

    public function __construct($conn, $requestMethod, $movieId) {
        $this->requestMethod = $requestMethod;
        $this->movieId = $movieId;
        $this->movie = new Movie($conn);
    }

    public function processRequest() {
        switch ($this->requestMethod) {
            case "GET":
                if ($this->movieId) {
                    $response = $this->getMovie($this->movieId);
                } else {
                    $response = $this->getAllMovies();
                }
                break;
            case "POST":
                $response = $this->createMovie();
                break;
            case "PUT":
                $response = $this->updateMovie($this->movieId);
                break;
            case "DELETE":
                $response = $this->deleteMovie($this->movieId);
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

    private function getAllMovies() {
        $result = $this->movie->findAll();
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function getMovie($movieId) {
        $result = $this->movie->find($movieId);
        if (!$result) {
            return $this->notFound();
        }
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function createMovie() {
        $input = json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateInput($input)) {
            return $this->unprocessableEntity();
        }
        $this->movie->create($input);
        $response["status_code_header"] = "HTTP/1.1 201 Created";
        $response["body"] = json_encode(array("message" => "Movie Inserted Successfully"));
        return $response;
    }

    private function updateMovie($movieId) {
        $result = $this->movie->find($movieId);
        if (!$result) {
            return $this->notFound();
        }
        $input = json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateInput($input)) {
            return $this->unprocessableEntity();
        }
        $this->movie->update($movieId, $input);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode(array("message" => "Movie Updated Successfully"));
        return $response;
    }

    private function deleteMovie($movieId) {
        $result = $this->movie->find($movieId);
        if (!$result) {
            return $this->notFound();
        }
        $this->movie->delete($movieId);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode(array("message" => "Movie Deleted Successfully"));
        return $response;
    }

    private function validateInput($input) {
        if (!isset($input["genre_id"])) {
            return false;
        }
        if (!isset($input["title"])) {
            return false;
        }
        if (!isset($input["description"])) {
            return false;
        }
        if (!isset($input["director_id"])) {
            return false;
        }
        if (!isset($input["release_date"])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntity() {
        $response["status_code_header"] = "HTTP/1.1 422 Unprocessable Entity";
        $response["body"] = json_encode(array("error" => "Invalid Input"));
        return $response;
    }

    private function notFound() {
        $response["status_code_header"] = "HTTP/1.1 404 Not Found";
        $response["body"] = json_encode(array("error" => "Movie Not Found"));
        return $response;
    }
}
