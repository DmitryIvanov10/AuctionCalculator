<?php
declare(strict_types=1);

namespace App\Domain;

use UnexpectedValueException;

final class Bid
{
    private readonly int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new UnexpectedValueException('Bid value must be more than 0.');
        }

        $this->value = $value;
    }

    public function isHigherThan(Bid $bid): bool
    {
        return $this->value > $bid->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
