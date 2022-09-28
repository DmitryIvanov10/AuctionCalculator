<?php
declare(strict_types=1);

namespace App\Domain;

use RuntimeException;
use UnexpectedValueException;

final class SecondPriceSealedBidAuction implements AuctionInterface
{
    /**
     * @var BuyerInterface[]
     */
    private array $buyers;
    private ?BuyerInterface $winner;
    private Bid $winningBid;
    private readonly int $reservePrice;

    public function __construct(int $reservePrice, BuyerInterface ...$buyers)
    {
        if ($reservePrice <= 0) {
            throw new UnexpectedValueException('Reserve price must more than 0.');
        }

        $this->reservePrice = $reservePrice;
        $this->buyers = $buyers;
        $this->winningBid = new Bid($this->reservePrice);
    }

    /**
     * @inheritDoc
     */
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
                $this->winningBid = $this->winner->getHighestBid();
                $this->winner = $buyer;
            }
        }

        return $this->winner ?? throw new RuntimeException('The auction has no winner.');
    }

    /**
     * @inheritDoc
     */
    public function getWinningBid(): Bid
    {
        if (!isset($this->winner)) {
            $this->winner = $this->getWinner();
        }

        return $this->winningBid;
    }
}
