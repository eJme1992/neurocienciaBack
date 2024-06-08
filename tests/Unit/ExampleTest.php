<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test database connection.
     */
    public function test_database_connection(): void
    {
        $connection = null;

        try {
            $connection = DB::connection()->getPdo();
        } catch (\Exception $e) {
            // Handle the exception as you wish.
        }

        $this->assertNotNull($connection);
    }
}