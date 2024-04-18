<?php

class Role
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function fromArray(array $data): Role
    {
        return new self($data['id'], $data['name']);
    }

    public static function fromValues(int $id, string $name): Role
    {
        return new self($id, $name);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isDetective(): bool
    {
        return $this->getName() === 'Detective';
    }

    public function isMafia(): bool
    {
        return $this->getName() === 'Mafia';
    }

    public function isVillager(): bool
    {
        return $this->getName() === 'Villager';
    }

    public function isDoctor(): bool
    {
        return $this->getName() === 'Doctor';
    }
}