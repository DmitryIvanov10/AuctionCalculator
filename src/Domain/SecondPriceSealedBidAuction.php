<?php
declare(strict_types=1);

namespace App\Domain;

use RuntimeException;

final class SecondPriceSealedBidAuction implements AuctionInterface
{
    /**
     * @var BuyerInterface[]
     */
    private array $buyers;
    private ?BuyerInterface $winner;
    private ?BuyerInterface $runnerUp;

    public function __construct(private readonly int $reservePrice, BuyerInterface ...$buyers)
    {
        $this->buyers = $buyers;
    }

    public function getWinner(): BuyerInterface
    {
        if (isset($this->winner)) {
            return $this->winner;
        }

        foreach ($this->buyers as $buyer) {
            if ($buyer->getHighestBid()?->getValue() < $this->reservePrice) {
                continue;
            }

            if (!isset($this->winner)) {
                $this->winner = $buyer;
                continue;
            }

            if ($buyer->getHighestBid()->isHigherThan($this->winner->getHighestBid())) {
                $this->runnerUp = $this->winner;
                $this->winner = $buyer;
            }
        }

        return $this->winner ?? throw new RuntimeException('No winner found');
    }

    public function getWinningBid(): Bid
    {
        if (!isset($this->winner)) {
            $this->winner = $this->getWinner();
        }

        return isset($this->runnerUp)
            ? $this->runnerUp->getHighestBid()
            : new Bid($this->reservePrice);
    }
}
