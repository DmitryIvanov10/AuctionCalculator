<?php
declare(strict_types=1);

namespace App\Tests\Suits\Unit\Domain;

use App\Domain\AuctionFactory;
use App\Domain\AuctionInfoInterface;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class AuctionFactoryTest extends TestCase
{
    private readonly AuctionFactory $auctionFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auctionFactory = new AuctionFactory();
    }

    public function testCreateForUnknownAuctionInfo()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->auctionFactory->create(
            new class implements AuctionInfoInterface {}
        );
    }
}
