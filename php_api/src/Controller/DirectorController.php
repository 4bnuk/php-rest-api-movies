<?php

class DirectorController
{
    private $requestMethod;
    private $directorId;
    private $director;

    public function __construct($conn, $requestMethod, $directorId) {
        $this->requestMethod = $requestMethod;
        $this->directorId = $directorId;
        $this->director = new Director($conn);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case "GET":
                if ($this->directorId) {
                    $response = $this->getDirector($this->directorId);
                } else {
                    $response = $this->getAllDirectors();
                }
                break;
            case "POST":
                $response = $this->createDirector();
                break;
            case "PUT":
                $response = $this->updateDirector($this->directorId);
                break;
            case "DELETE":
                $response = $this->deleteDirector($this->directorId);
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

    private function getAllDirectors()
    {
        $result = $this->director->findAll();
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function getDirector($directorId)
    {
        $result = $this->director->find($directorId);
        if (!$result) {
            return $this->notFound();
        }
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function createDirector()
    {
        $input = json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateInput($input)) {
            return $this->unprocessableEntity();
        }
        $this->director->create($input);
        $response["status_code_header"] = "HTTP/1.1 201 Created";
        $response["body"] = json_encode(array("message" => "Director Inserted Successfully"));
        return $response;
    }

    private function updateDirector($directorId)
    {
        $result = $this->director->find($directorId);
        if (!$result) {
            return $this->notFound();
        }
        $input = json_decode(file_get_contents("php://input"), TRUE);
        if (!$this->validateInput($input)) {
            return $this->unprocessableEntity();
        }
        $this->director->update($directorId, $input);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode(array("message" => "Director Updated Successfully"));
        return $response;
    }

    private function deleteDirector($directorId)
    {
        $result = $this->director->find($directorId);
        if (!$result) {
            return $this->notFound();
        }
        $this->director->delete($directorId);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode(array("message" => "Director Deleted Successfully"));
        return $response;
    }

    private function validateInput($input)
    {
        if (!isset($input["firstname"])) {
            return false;
        }
        if (!isset($input["lastname"])) {
            return false;
        }
        if (!isset($input["birthdate"])) {
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
        $response["body"] = json_encode(array("error" => "Director Not Found"));
        return $response;
    }
}
