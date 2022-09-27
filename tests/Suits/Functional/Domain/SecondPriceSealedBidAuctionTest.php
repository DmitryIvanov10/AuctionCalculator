<?php
declare(strict_types=1);

namespace App\Tests\Suits\Functional\Domain;

use App\Domain\AuctionFactory;
use App\Domain\SecondPriceSealedBidAuctionInfo;
use PHPUnit\Framework\TestCase;

final class SecondPriceSealedBidAuctionTest extends TestCase
{
    private readonly AuctionFactory $auctionFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auctionFactory = new AuctionFactory();
    }

    /**
     * @param array<string, mixed> $auctionInfo
     *
     * @dataProvider auctionInfoDataProvider
     */
    public function testAuction(string $expectedWinner, int $expectedPrice, array $auctionInfo)
    {
        $auctionInfoObject = new SecondPriceSealedBidAuctionInfo($auctionInfo);
        $auction = $this->auctionFactory->create($auctionInfoObject);

        $this->assertSame($expectedWinner, $auction->getWinner()->getName());
        $this->assertSame($expectedPrice, $auction->getWinningBid()->getValue());
    }

    /**
     * @return array<string, mixed>
     */
    public function auctionInfoDataProvider(): array
    {
        return [
            'one bidder' => [
                'expectedWinner' => 'A',
                'expectedPrice' => 100,
                'auctionInfo' => [
                    'reservedPrice' => 100,
                    'buyersBids' => [
                        'A' => [100],
                    ],
                ],
            ],
            'two bidders one less then reserved price' => [
                'expectedWinner' => 'A',
                'expectedPrice' => 100,
                'auctionInfo' => [
                    'reservedPrice' => 100,
                    'buyersBids' => [
                        'A' => [110],
                        'B' => [90],
                    ],
                ],
            ],
            'two bidders both more then reserved price' => [
                'expectedWinner' => 'B',
                'expectedPrice' => 110,
                'auctionInfo' => [
                    'reservedPrice' => 100,
                    'buyersBids' => [
                        'A' => [110],
                        'B' => [120],
                    ],
                ],
            ],
            'example test case' => [
                'expectedWinner' => 'E',
                'expectedPrice' => 130,
                'auctionInfo' => [
                    'reservedPrice' => 100,
                    'buyersBids' => [
                        'A' => [110, 130],
                        'B' => [],
                        'C' => [125],
                        'D' => [105, 115, 90],
                        'E' => [132, 135, 140],
                    ],
                ],
            ],
        ];
    }
}
