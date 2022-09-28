<?php
declare(strict_types=1);

namespace App\Domain;

use UnexpectedValueException;

final class Buyer implements BuyerInterface
{
    private ?Bid $highestBid;
    private readonly string $name;

    public function __construct(string $name)
    {
        $name = trim($name);

        if (strlen($name) === 0) {
            throw new UnexpectedValueException('Buyer name must not be an empty string');
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function placeBid(Bid $bid): void
    {
        if (!isset($this->highestBid)) {
            $this->highestBid = $bid;
            return;
        }

        if ($bid->isHigherThan($this->highestBid)) {
            $this->highestBid = $bid;
        }
    }

    public function getHighestBid(): ?Bid
    {
        return $this->highestBid ?? null;
    }
}
