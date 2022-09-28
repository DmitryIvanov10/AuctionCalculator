<?php
declare(strict_types=1);

namespace App\Domain;

use RuntimeException;

interface AuctionInterface
{
    /**
     * @throws RuntimeException if no winner found
     */
    public function getWinner(): BuyerInterface;

    /**
     * @throws RuntimeException if no winner found
     */
    public function getWinningBid(): Bid;
}
