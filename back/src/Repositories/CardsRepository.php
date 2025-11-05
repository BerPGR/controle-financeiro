<?php

namespace Mp\Financias\Repositories;

use Mp\Financias\Enums\Kind;
use PDO;

class CardsRepository {
    public function __construct(private PDO $pdo) {}

    public function all() {
        $stmt = $this->pdo->query("SELECT * from cards");
        return $stmt->fetchAll();
    }

    public function insert(string $name, string $color) {
        $stmt = $this->pdo->prepare("INSERT INTO cards (name, color) VALUES (:name, :color);");
        $ok = $stmt->execute([':name' => $name, ':color' => $color]);

        $id = (int) $this->pdo->lastInsertId();

        return $id;
    }
}