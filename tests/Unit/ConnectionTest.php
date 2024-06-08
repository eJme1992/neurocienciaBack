<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ConnectionTest extends TestCase
{
    use DatabaseTransactions;

   
    protected function setUp(): void
    {
        parent::setUp();
    }
    

    public function testDatabaseConnection(): void
    {
        $connection = null;
        try {
            $connection = DB::connection()->getPdo();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail("Failed to connect to the database: {$e->getMessage()}");
        }
        $this->assertNotNull($connection);
    }
}
