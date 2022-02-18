<?php
declare(strict_types=1);

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class EntityDTO extends DataTransferObject
{
    /**
     * @return array
     */
    public function notNull(): array
    {
        return array_filter($this->all());
    }
}
