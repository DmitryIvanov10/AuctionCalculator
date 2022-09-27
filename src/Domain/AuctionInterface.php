<?php
declare(strict_types=1);

namespace App\Domain;

interface AuctionInterface
{
    public function getWinner(): BuyerInterface;

    public function getWinningBid(): Bid;
}
