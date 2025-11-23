<?php

namespace App\DTOs;

class GoalDTO
{
    public int $id;
    public string $title;
    public ?string $category;
    public float $target_value;
    public float $current_value;
    public float $progress;
    public int $xp_reward;
    public string $status;
    public bool $is_system_goal;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->category = $data['category'] ?? null;
        $this->target_value = (float) $data['target_value'];
        $this->current_value = (float) $data['current_value'];
        $this->progress = (float) ($data['progress'] ?? 0);
        $this->xp_reward = (int) ($data['xp_reward'] ?? 0);
        $this->status = $data['status'] ?? 'active';
        $this->is_system_goal = (bool) $data['is_system_goal'];
    }
}
