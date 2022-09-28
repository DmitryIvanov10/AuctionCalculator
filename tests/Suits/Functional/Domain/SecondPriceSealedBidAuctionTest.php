<?php
declare(strict_types=1);

namespace App\Tests\Suits\Functional\Domain;

use App\Domain\AuctionFactory;
use App\Domain\AuctionInterface;
use App\Domain\SecondPriceSealedBidAuctionInfo;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UnexpectedValueException;

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
     * @dataProvider incorrectAuctionInfoDataProvider
     */
    public function testCreateAuctionWithIncorrectData(array $auctionInfo)
    {
        $this->expectException(UnexpectedValueException::class);
        $this->auctionFactory->create(
            new SecondPriceSealedBidAuctionInfo($auctionInfo)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function incorrectAuctionInfoDataProvider(): array
    {
        return [
            'incorrect reserve price' => [
                'auctionInfo' => [
                    'reservePrice' => 0,
                    'buyersBids' => [
                        'A' => [100],
                    ],
                ],
            ],
            'incorrect buyer name' => [
                'auctionInfo' => [
                    'reservePrice' => 100,
                    'buyersBids' => [
                        '' => [100],
                    ],
                ],
            ],
            'incorrect bid value' => [
                'auctionInfo' => [
                    'reservePrice' => 100,
                    'buyersBids' => [
                        'A' => [0],
                    ],
                ],
            ],
        ];
    }

    public function testGetWinnerWhenNoWinnerForAuction()
    {
        $auction = $this->createAuctionWithNoWinner();

        $this->expectException(RuntimeException::class);
        $auction->getWinner();
    }

    public function testGetWinnerBidWhenNoWinnerForAuction()
    {
        $auction = $this->createAuctionWithNoWinner();

        $this->expectException(RuntimeException::class);
        $auction->getWinningBid();
    }

    /**
     * @param array<string, mixed> $auctionInfo
     *
     * @dataProvider auctionInfoDataProvider
     */
    public function testAuction(string $expectedWinner, int $expectedPrice, array $auctionInfo)
    {
        $auction = $this->auctionFactory->create(
            new SecondPriceSealedBidAuctionInfo($auctionInfo)
        );

        $this->assertSame($expectedWinner, $auction->getWinner()->getName());
        $this->assertSame($expectedPrice, $auction->getWinningBid()->getValue());
    }

    /**
     * @return array<string, mixed>
     */
    public function auctionInfoDataProvider(): array
    {
        return [
            'one buyer' => [
                'expectedWinner' => 'A',
                'expectedPrice' => 100,
                'auctionInfo' => [
                    'reservePrice' => 100,
                    'buyersBids' => [
                        'A' => [100],
                    ],
                ],
            ],
            'two buyers one less then reserve price' => [
                'expectedWinner' => 'A',
                'expectedPrice' => 100,
                'auctionInfo' => [
                    'reservePrice' => 100,
                    'buyersBids' => [
                        'A' => [110],
                        'B' => [90],
                    ],
                ],
            ],
            'two buyers both more then reserve price' => [
                'expectedWinner' => 'B',
                'expectedPrice' => 110,
                'auctionInfo' => [
                    'reservePrice' => 100,
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
                    'reservePrice' => 100,
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

    private function createAuctionWithNoWinner(): AuctionInterface
    {
        return $this->auctionFactory->create(
            new SecondPriceSealedBidAuctionInfo([
                'reservePrice' => 100,
                'buyersBids' => [
                    'A' => [80],
                    'B' => [90],
                ],
            ])
        );
    }
}
