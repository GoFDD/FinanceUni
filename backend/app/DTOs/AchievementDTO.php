<?php

namespace App\DTOs;

class AchievementDTO
{
    public int $id;
    public string $title;
    public string $description;
    public int $xp_reward;
    public string $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->xp_reward = $data['xp_reward'] ?? 0;
        $this->status = $data['status'] ?? 'locked';
    }
}