<?php
declare(strict_types=1);

namespace App\DTO;

use App\DTO\Validators\StringList;
use App\Enums\QuestionTypeEnum;

class QuestionDTO extends EntityDTO
{
    /** @var int|null $id */
    public ?int $id;

    /** @var string $title */
    public string $title;

    #[StringList([QuestionTypeEnum::TEXT, QuestionTypeEnum::NUMBER])]
    public string $type;

    /** @var string|null $correct_answer */
    public ?string $correct_answer;

    /** @var int|null $quiz_id */
    public ?int $quiz_id;
}
