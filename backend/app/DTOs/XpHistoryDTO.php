<?php

namespace App\DTOs;

class XpHistoryDTO
{
    public string $description;
    public int $amount;
    public string $date;

    public function __construct(array $data)
    {
        $this->description = $data['description'];
        $this->amount = $data['amount'];
        $this->date = $data['created_at'] ?? now()->toDateTimeString();
    }
}
