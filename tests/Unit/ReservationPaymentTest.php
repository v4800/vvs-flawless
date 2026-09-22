<?php

namespace Tests\Unit;

use App\Support\ReservationPayment;
use PHPUnit\Framework\TestCase;

class ReservationPaymentTest extends TestCase
{
    public function test_it_uses_a_fixed_100_euro_deposit(): void
    {
        $this->assertSame(100.0, ReservationPayment::depositAmount(950));
        $this->assertSame(850.0, ReservationPayment::balanceAmount(950));
        $this->assertSame(
            950.0,
            ReservationPayment::confirmedPaidAmount(950, true, true)
        );
    }

    public function test_it_preserves_a_stored_historical_deposit(): void
    {
        $this->assertSame(
            250.0,
            ReservationPayment::depositAmount(1000, 250)
        );
        $this->assertSame(
            750.0,
            ReservationPayment::balanceAmount(1000, 250)
        );
        $this->assertSame(
            250.0,
            ReservationPayment::confirmedPaidAmount(
                1000,
                true,
                false,
                250
            )
        );
    }

    public function test_deposit_never_exceeds_total_price(): void
    {
        $this->assertSame(80.0, ReservationPayment::depositAmount(80));
        $this->assertSame(0.0, ReservationPayment::balanceAmount(80));
    }
}
