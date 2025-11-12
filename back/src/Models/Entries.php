<?php

namespace Mp\Controle\Model;

use DateTime;
use Mp\Controle\Enums\Kind;

class Entries {
    private int $id;
    private Kind $kind;
    private string $description;
    private float $amount;

    private DateTime $date;
    private DateTime $createdAt;

    public function __construct(int $id, Kind $kind, string $description, float $amount, DateTime $date, DateTime $createdAt) {
        $this->id = $id;
        $this->kind = $kind;
        $this->description = $description;
        $this->amount = $amount;
        $this->date = $date;
        $this->createdAt = $createdAt;
    }
}