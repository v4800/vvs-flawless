<?php

namespace Tests\Unit;

use App\Support\ReservationPayment;
use PHPUnit\Framework\TestCase;

class ReservationPaymentTest extends TestCase
{
    public function test_standard_catalog_deposit_is_fixed_at_one_hundred_euros(): void
    {
        $this->assertSame(
            100.0,
            ReservationPayment::depositAmount(950)
        );

        $this->assertSame(
            850.0,
            ReservationPayment::balanceAmount(950)
        );
    }

    public function test_deposit_never_exceeds_the_watch_price(): void
    {
        $this->assertSame(
            80.0,
            ReservationPayment::depositAmount(80)
        );

        $this->assertSame(
            0.0,
            ReservationPayment::balanceAmount(80)
        );
    }

    public function test_explicit_stored_snapshot_can_be_used_for_a_future_custom_order(): void
    {
        $this->assertSame(
            250.0,
            ReservationPayment::depositAmount(1200, 250)
        );

        $this->assertSame(
            950.0,
            ReservationPayment::balanceAmount(1200, 250)
        );
    }

    public function test_confirmed_paid_amount_uses_the_snapshot(): void
    {
        $this->assertSame(
            250.0,
            ReservationPayment::confirmedPaidAmount(
                1200,
                true,
                false,
                250
            )
        );

        $this->assertSame(
            1200.0,
            ReservationPayment::confirmedPaidAmount(
                1200,
                true,
                true,
                250
            )
        );
    }
}
