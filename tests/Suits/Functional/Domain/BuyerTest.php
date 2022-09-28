<?php
declare(strict_types=1);

namespace App\Tests\Suits\Functional\Domain;

use App\Domain\Bid;
use App\Domain\Buyer;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class BuyerTest extends TestCase
{
    /**
     * @dataProvider buyerWrongNameDataProvider
     */
    public function testCreateBuyerWithWrongName(string $name)
    {
        $this->expectException(UnexpectedValueException::class);
        new Buyer($name);
    }

    /**
     * @return array<string, string[]>
     */
    public function buyerWrongNameDataProvider(): array
    {
        return [
            'empty string' => [''],
            'string with only empty signs' => ['    
            '],
        ];
    }

    public function testGetHighestBid()
    {
        $buyer = new Buyer('test');
        $this->assertNull($buyer->getHighestBid());

        $firstBid = new Bid(10);
        $secondBid = new Bid(5);
        $thirdBid = new Bid(20);

        $buyer->placeBid($firstBid);
        $this->assertSame($firstBid, $buyer->getHighestBid());

        $buyer->placeBid($secondBid);
        $this->assertSame($firstBid, $buyer->getHighestBid());

        $buyer->placeBid($thirdBid);
        $this->assertSame($thirdBid, $buyer->getHighestBid());
    }
}
