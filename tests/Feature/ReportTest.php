<?php

namespace Tests\Feature;

use App\Mail\DailyReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReportTest extends TestCase
{
    /**
     * Daily sales report is being sent by artisan command.
     *
     * @return void
     */
    public function testDailyReport()
    {
        Mail::fake();

        Artisan::call('report:daily');
        Mail::assertSent(DailyReport::class);
    }
}
