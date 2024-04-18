<?php
class Response
{
   private int $status;
   private string $message;
   private $data;

    private function __construct(int $status, string $message, $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
    public static function create(int $status, string $message = '', $data = null): self
    {
        return new self($status, $message, $data);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getdata(): mixed
    {
        return $this->data;
    }

    public function isSuccess():bool {
        return $this->status === 1;
    }

    public function __toString(): string
    {
        return (string)json_encode(['status' => $this->status, 'message' => $this->message, 'data' => json_encode($this->data)]);
    }
}