<?php
declare(strict_types=1);

namespace App\Tests\Suits\Unit\Domain;

use App\Domain\Bid;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class BidTest extends TestCase
{
    /**
     * @dataProvider bidWrongValueDataProvider
     */
    public function testCreateBidWithWrongValue(int $value)
    {
        $this->expectException(UnexpectedValueException::class);
        new Bid($value);
    }

    /**
     * @return array<string, int[]>
     */
    public function bidWrongValueDataProvider(): array
    {
        return [
            'zero bid' => [0],
            'negative bid' => [-1],
        ];
    }
    public function testIsHigherThan()
    {
        $smallerBid = new Bid(10);
        $biggerBid = new Bid(20);

        $this->assertTrue($biggerBid->isHigherThan($smallerBid));
    }
}
