<?php

namespace Mp\Controle\Services;

use Mp\Controle\Repositories\EntriesRepository;

class EntriesService {
    public function __construct(private EntriesRepository $repository) {}

    public function getAllEntriesFromCard($cardId) {
        return $this->repository->getAllFromCardId($cardId);
    }

    public function createEntry($cardId, $payload) {
        return $this->repository->createEntry($cardId, $payload);
    }
}