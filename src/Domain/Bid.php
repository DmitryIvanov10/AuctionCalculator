<?php
declare(strict_types=1);

namespace App\Domain;

final class Bid
{
    public function __construct(private readonly int $value)
    {
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
