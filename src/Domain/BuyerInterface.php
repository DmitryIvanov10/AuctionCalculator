<?php
declare(strict_types=1);

namespace App\Domain;

interface BuyerInterface
{
    public function getName(): string;

    public function placeBid(Bid $bid): void;

    public function getHighestBid(): ?Bid;
}
