<?php

class Department {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $statement = $this->pdo->query("SELECT id, name, description FROM departments ORDER BY id");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } 

    public function findById(int $id): ?array {
        $statement = $this->pdo->prepare("SELECT id, name, description FROM departments WHERE id = :id");
        $statement->execute(['id' => $id]);
        $department = $statement->fetch(PDO::FETCH_ASSOC);
        return $department ?: null;
    }

    public function create(string $name, ?string $description): bool {
        $statement = $this->pdo->prepare("INSERT INTO departments (name, description) VALUES (:name, :description)");
        return $statement->execute(['name' => $name, 'description' => $description]);
    }

    public function update(int $id, string $name, ?string $description): bool {
        $statement = $this->pdo->prepare("UPDATE departments SET name = :name, description = :description, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $statement->execute(['id' => $id, 'name' => $name, 'description' => $description]);
    }

    public function delete(int $id): bool {
        $statement = $this->pdo->prepare("DELETE FROM departments WHERE id = :id");
        return $statement->execute(['id' => $id]);
    }
}