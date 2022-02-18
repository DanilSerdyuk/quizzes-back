<?php
declare(strict_types=1);

namespace App\DTO\Validators;

use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

#[\Attribute]
class StringList implements Validator
{
    /**
     * StringList constructor.
     */
    public function __construct(private array $list)
    {
    }

    /**
     * @param mixed $value
     *
     * @return ValidationResult
     */
    public function validate(mixed $value): ValidationResult
    {
        if (in_array($value, $this->list, true)) {
            return ValidationResult::valid();
        }

        return ValidationResult::invalid('String not found.');
    }
}
