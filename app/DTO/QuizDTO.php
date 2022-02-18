<?php
declare(strict_types=1);

namespace App\DTO;

class QuizDTO extends EntityDTO
{
    /** @var int|null $id */
    public ?int $id;

    /** @var int|null $user_id */
    public ?int $user_id;

    /** @var string $title */
    public string $title;
}
