<?php

namespace Mp\Controle\Repositories;

use PDO;

class CardsRepository {
    public function __construct(private PDO $pdo) {}

    public function all(): array {
        $stmt = $this->pdo->query("SELECT id, name, color, created_at FROM cards ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function insert(string $name, ?string $color): int {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO cards (name, color) VALUES (:name, :color)");
            $stmt->execute([':name' => $name, ':color' => $color]);

            $id = (int)$this->pdo->lastInsertId();
            $this->pdo->commit();
            return $id;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new \RuntimeException("Erro ao inserir: " . $e->getMessage(), 500, $e);
        }
    }
}
