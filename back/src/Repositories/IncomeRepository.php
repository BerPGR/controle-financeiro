<?php 

namespace Mp\Controle\Repositories;

use Flight;
use PDO;
use RuntimeException;

class IncomeRepository {

    public function __construct(private PDO $pdo) {}

    public function getMonthlyIncome() {
        try {
            $sql = "SELECT SUM(value) as total from income WHERE date BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE());";
            $stmt = $this->pdo->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $result['total'];
        }
        catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function insertMonthlyIncome(array $payload): array  {
        try {
            $sql = "INSERT INTO income (value, date) VALUES (:value, :date);";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':value' => $payload['value'], ':date' => $payload['date']]);

            return ['status' => 204, 'message' => 'Rendimento inserido com sucesso!'];
        } catch (\Throwable $e) {
            throw new RuntimeException('Erro ao inserir rendimento: ' . $e->getMessage());
        }
    }
}