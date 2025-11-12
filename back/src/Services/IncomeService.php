<?php 

namespace Mp\Controle\Services;

use Mp\Controle\Repositories\IncomeRepository;

class IncomeService {
    public function __construct(private IncomeRepository $repository) {}

    public function monthlyIncomeGet() {
        return $this->repository->getMonthlyIncome();
    }

    public function insertIncome(array $payload) {
        return $this->repository->insertMonthlyIncome($payload);
    }
}