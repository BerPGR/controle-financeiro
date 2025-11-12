<?php

namespace Mp\Controle\Repositories;

use PDO;

class EntriesRepository
{
    public function __construct(private PDO $pdo) {}

    public function getAllFromCardId(string $cardId)
    {
        $sql = "SELECT id, kind, description, amount, date FROM entries where card_id = $cardId";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function createEntry($cardId, $payload)
    {
        $this->pdo->beginTransaction();
        try {
            $sql = "INSERT INTO entries (card_id, kind, description, amount, date) VALUES (:card_id, :kind, :description, :amount, :date);";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute(
                [
                    ":card_id" => $cardId,
                    ":kind" => $payload['kind'],
                    ":description" => $payload['description'],
                    ":amount" => $payload['amount'],
                    ":date" => $payload['date'],
                ]
            );
            if ($ok) {
                $this->pdo->commit();
                $id = $this->pdo->lastInsertId();
            }
            return $id;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new \RuntimeException("Erro ao inserir: " . $e->getMessage() . $e->getLine(), 500, $e);
        }
    }

    public function deleteEntryById($id) {
        $this->pdo->beginTransaction();
        try {
            $sql = "DELETE FROM entries WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([':id' => $id]);
    
            if ($ok) {
                $this->pdo->commit();
            }

            return ['status' => 204, 'message' => 'Registro deletado com sucesso'];
        }
        catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new \RuntimeException('Falha ao deletar registro: '. $e->getMessage());
        }
    }
}
