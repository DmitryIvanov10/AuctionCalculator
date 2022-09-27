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
    private int $reservedPrice;

    public function __construct(array $data)
    {
        try {
            $this->setReservedPrice($data['reservedPrice']);

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

    public function getReservedPrice(): int
    {
        return $this->reservedPrice;
    }

    private function setReservedPrice(int $reservedPrice): void
    {
        $this->reservedPrice = $reservedPrice;
    }

    private function setBuyersBids(string $buyerName, int ...$bids): void
    {
        $this->buyersBids[$buyerName] = $bids;
    }
}
