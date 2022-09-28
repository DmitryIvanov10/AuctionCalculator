<?php
declare(strict_types=1);

namespace App\Domain;

use RuntimeException;
use Throwable;

final class SecondPriceSealedBidAuctionInfo implements AuctionInfoInterface
{
    /**
     * @var array<string, int[]>
     */
    private array $buyersBids;
    private int $reservePrice;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        try {
            $this->setReservePrice($data['reservePrice']);

            foreach ($data['buyersBids'] as $buyerName => $bids) {
                $this->setBuyersBids($buyerName, ...$bids);
            }
        } catch (Throwable) {
            throw new RuntimeException('Incorrect input format');
        }
    }

    /**
     * @return array<string, int[]>
     */
    public function getBuyersBids(): array
    {
        return $this->buyersBids;
    }

    public function getReservePrice(): int
    {
        return $this->reservePrice;
    }

    private function setReservePrice(int $reservePrice): void
    {
        $this->reservePrice = $reservePrice;
    }

    private function setBuyersBids(string $buyerName, int ...$bids): void
    {
        $this->buyersBids[$buyerName] = $bids;
    }
}
