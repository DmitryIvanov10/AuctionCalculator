<?php
declare(strict_types=1);

namespace App\Domain;

use UnexpectedValueException;

final class AuctionFactory
{
    public function create(AuctionInfoInterface $auctionInfo): AuctionInterface
    {
        return match($auctionInfo::class) {
            SecondPriceSealedBidAuctionInfo::class => $this->createSecondPriceSealedBidAuction($auctionInfo),
            default => throw new UnexpectedValueException('Unknown auction type.'),
        };
    }

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

        return new SecondPriceSealedBidAuction($auctionInfo->getReservedPrice(), ...$buyers);
    }
}
