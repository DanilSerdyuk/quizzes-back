<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class QuizWasCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private int $userId, private array $questions)
    {
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

}
