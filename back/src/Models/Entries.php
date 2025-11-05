<?php

namespace Mp\Financias\Model;

use DateTime;
use Mp\Financias\Enums\Kind;

class Entries {
    private int $id;
    private Kind $kind;
    private string $description;
    private float $amount;

    private DateTime $date;
    private string $category;
    private DateTime $createdAt;

    public function __construct(int $id, Kind $kind, string $description, float $amount, DateTime $date, string $category, DateTime $createdAt) {
        $this->id = $id;
        $this->kind = $kind;
        $this->description = $description;
        $this->amount = $amount;
        $this->date = $date;
        $this->category = $category;
        $this->createdAt = $createdAt;
    }
}