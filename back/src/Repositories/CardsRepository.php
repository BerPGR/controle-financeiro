<?php

namespace Mp\Controle\Repositories;

use PDO;
use RuntimeException;

class CardsRepository
{
    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, color, created_at FROM cards ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function insert(string $name, ?string $color, string $user_id): int
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO cards (user_id, name, color) VALUES (:user_id, :name, :color)");
            $stmt->execute([':user_id' => $user_id, ':name' => $name, ':color' => $color]);

            $id = (int)$this->pdo->lastInsertId();
            $this->pdo->commit();
            return $id;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new RuntimeException("Erro ao inserir: " . $e->getMessage(), 0, $e);
        }
    }

    public function update(int $id, string $name, string $color): array
    {
        try {
            $sql = "UPDATE cards SET name = :name, color = :color WHERE id = :id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":name" => $name, ":color" => $color, ':id' => $id]);

            return ['status' => 204, 'message' => 'Card atualizado com sucesso!'];
        } catch (\Throwable $e) {
            throw new RuntimeException("Erro ao atualizar card" . $e->getMessage());
        }
    }

    public function delete(int $id): array
    {
        try {
            $sql = "DELETE FROM cards WHERE id = :id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['status' => 204, 'message' => 'Sucesso ao deletar card!'];
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erro ao deletar card: ' . $e->getMessage());
        }
    }

    public function summary()
    {
        $sql = "
            SELECT 
                c.id, 
                c.name,
                COALESCE(SUM(CASE WHEN e.kind = 'INVESTMENT' THEN e.amount END), 0) AS total_income,
                COALESCE(SUM(CASE WHEN e.kind = 'EXPENSE' THEN e.amount END), 0) AS total_expense
            FROM cards c
            LEFT JOIN 
                entries e ON e.card_id = c.id
            GROUP BY 
                c.id, c.name
            ORDER BY 
                c.name
        ";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['total_income'] = (float) $row['total_income'];
            $row['total_expense']    = (float) $row['total_expense'];
        }

        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN e.kind = 'INVESTMENT' THEN e.amount END), 0) as all_income,
                COALESCE(SUM(CASE WHEN e.kind = 'EXPENSE' THEN e.amount END), 0) as all_expense 
            FROM entries e
        ";

        $stmt = $this->pdo->query($sql);
        $pieRow = $stmt->fetch();
        $pieRow['all_income'] = (float) $pieRow['all_income'];
        $pieRow['all_expense'] = (float) $pieRow['all_expense'];

        $sql = "
            SELECT 
                c.id,
                c.name,
                COALESCE(SUM(CASE WHEN e.kind = 'EXPENSE' THEN e.amount END), 0) as total_expense 
            FROM cards c
            LEFT JOIN entries e ON e.card_id = c.id
            GROUP BY 
                c.id, 
                c.name 
            ORDER BY 
                c.name
        ";

        $stmt = $this->pdo->query($sql);
        $pieCardExpesnses = $stmt->fetchAll();

        $sql = "
            WITH months AS (
                SELECT 1 AS month
                UNION ALL SELECT 2
                UNION ALL SELECT 3
                UNION ALL SELECT 4
                UNION ALL SELECT 5
                UNION ALL SELECT 6
                UNION ALL SELECT 7
                UNION ALL SELECT 8
                UNION ALL SELECT 9
                UNION ALL SELECT 10
                UNION ALL SELECT 11
                UNION ALL SELECT 12
            )
            SELECT
                c.name AS card_name,
                YEAR(CURDATE()) AS year,
                m.month,
                -- referência do mês: primeiro dia do mês
                STR_TO_DATE(
                    CONCAT(YEAR(CURDATE()), '-', LPAD(m.month, 2, '0'), '-01'),
                    '%Y-%m-%d'
                ) AS month_reference,
                COALESCE(SUM(e.amount), 0) AS total_expense
            FROM cards c
            CROSS JOIN months m
            LEFT JOIN entries e
                ON e.card_id = c.id
                AND e.kind = 'EXPENSE'
                AND YEAR(e.date) = YEAR(CURDATE())
                AND MONTH(e.date) = m.month
            GROUP BY
                c.id,
                c.name,
                m.month
            ORDER BY
                c.name,
                m.month;
        ";

        $stmt = $this->pdo->query($sql);
        $lineChart = $stmt->fetchAll();

        $data = ['chart' => $rows, 'pie' => $pieRow, 'pieCard' => $pieCardExpesnses, 'lineChart' => $lineChart];

        return $data;
    }
}
