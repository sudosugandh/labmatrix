<?php

namespace Tests;

use App\Models\Lab;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (! $this->app->environment('testing')) {
            return;
        }

        if (! Schema::hasTable('labs')) {
            return;
        }

        if (Lab::query()->exists()) {
            return;
        }

        Lab::create([
            'name' => 'Test Lab',
            'email' => 'test@example.org',
            'phone' => '0000000000',
            'address' => 'Test Address',
        ]);
    }
}
