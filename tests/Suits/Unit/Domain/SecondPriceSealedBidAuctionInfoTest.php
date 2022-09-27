<?php
declare(strict_types=1);

namespace App\Tests\Suits\Unit\Domain;

use App\Domain\SecondPriceSealedBidAuctionInfo;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class SecondPriceSealedBidAuctionInfoTest extends TestCase
{
    public function testSuccessfulCreate()
    {
        $reservedPrice = 100;
        $buyersBids = [
            'A' => [80, 120],
            'B' => [130, 150],
        ];
        $input = [
            'reservedPrice' => $reservedPrice,
            'buyersBids' => $buyersBids,
        ];

        $info = new SecondPriceSealedBidAuctionInfo($input);

        $this->assertSame($reservedPrice, $info->getReservedPrice());
        $this->assertSame($buyersBids, $info->getBuyersBids());
    }

    /**
     * @param array<string, mixed> $input
     *
     * @dataProvider incorrectInfoDataProvider
     */
    public function testUnsuccessfulCreate(array $input)
    {
        $this->expectException(RuntimeException::class);
        new SecondPriceSealedBidAuctionInfo($input);
    }

    /**
     * @return array<string, mixed>
     */
    public function incorrectInfoDataProvider(): array
    {
        return [
            'empty info' => [[]],
            'no reserved price' => [
                [
                    'buyersBids' => [],
                ],
            ],
            'no buyers bids' => [
                [
                    'reservedPrice' => 100,
                ],
            ],
            'incorrect buyers name' => [
                [
                    'reservedPrice' => 100,
                    'buyersBids' => [
                        1 => [],
                    ],
                ],
            ],
            'incorrect bids' => [
                [
                    'reservedPrice' => 100,
                    'buyersBids' => [
                        'A' => ['a'],
                    ],
                ],
            ],
            'incorrect reserved price format' => [
                [
                    'reservedPrice' => 'a',
                    'buyersBids' => [
                        'A' => [100],
                    ],
                ],
            ],
        ];
    }
}
