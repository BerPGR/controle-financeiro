<?php

namespace Mp\Controle\Model;

use DateTime;

class Cards
{
    private int $id;
    private string $name;
    private string $color;
    private DateTime $createdAt;

    public function __construct(int $id, string $name, string $color, DateTime $createdAt) {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->createdAt = $createdAt;
    }
}