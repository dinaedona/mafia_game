<?php

class AbstractController
{
    public function __construct()
    {
    }

    public function request()
    {
        $data = $this->getRequestData();
        try {
            return $this->handleMethod($data['method'], $data);
        } catch (Exception $e) {
        }
    }

    private function getRequestData(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }
        return $_GET;
    }

    private function handleMethod(string $method, array $data)
    {
        if (method_exists($this, $method)) {
            return $this->$method($data);
        }
        throw new Exception('Invalid endpoint name');
    }
}

?>