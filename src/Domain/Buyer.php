<?php
declare(strict_types=1);

namespace App\Domain;

final class Buyer implements BuyerInterface
{
    private ?Bid $highestBid;

    public function __construct(private readonly string $name)
    {
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
