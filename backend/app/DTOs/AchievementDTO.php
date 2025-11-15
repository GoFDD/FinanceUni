<?php

namespace App\DTO;

class AchievementDTO
{
    public string $name;
    public string $description;
    public int $xp_reward;
    public bool $unlocked;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->xp_reward = $data['xp_reward'];
        $this->unlocked = (bool)($data['unlocked'] ?? false);
    }
}
