<?php

namespace Mp\Controle\Repositories;

use PDO;
use RuntimeException;

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
            throw new RuntimeException("Erro ao inserir: " . $e->getMessage(), 0, $e);
        }
    }

    public function update(int $id, string $name, string $color): array {
        try {
            $sql = "UPDATE cards SET name = :name, color = :color WHERE id = :id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":name" => $name, ":color" => $color, ':id' => $id]);

            return ['status' => 204, 'message' => 'Card atualizado com sucesso!'];
        } catch (\Throwable $e) {
            throw new RuntimeException("Erro ao atualizar card" . $e->getMessage());
        }
    }

    public function delete(int $id): array {
        try {
            $sql = "DELETE FROM cards WHERE id = :id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['status'=> 204, 'message'=> 'Sucesso ao deletar card!'];
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erro ao deletar card: '. $e->getMessage());
        }
    }
}
