<?php
declare(strict_types=1);

namespace App\Domain;

use UnexpectedValueException;

final class AuctionFactory
{
    /**
     * @throws UnexpectedValueException
     */
    public function create(AuctionInfoInterface $auctionInfo): AuctionInterface
    {
        return match($auctionInfo::class) {
            SecondPriceSealedBidAuctionInfo::class => $this->createSecondPriceSealedBidAuction($auctionInfo),
            default => throw new UnexpectedValueException('Unknown auction type.'),
        };
    }

    /**
     * @throws UnexpectedValueException when data inside info is against the domain rules
     */
    private function createSecondPriceSealedBidAuction(
        SecondPriceSealedBidAuctionInfo $auctionInfo
    ): SecondPriceSealedBidAuction {
        $buyers = [];

        foreach ($auctionInfo->getBuyersBids() as $buyerName => $bids) {
            $buyer = new Buyer($buyerName);

            foreach ($bids as $bid) {
                $buyer->placeBid(new Bid($bid));
            }

            $buyers[] = $buyer;
        }

        return new SecondPriceSealedBidAuction($auctionInfo->getReservePrice(), ...$buyers);
    }
}
