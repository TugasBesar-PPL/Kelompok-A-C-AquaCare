<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function beforeRefreshingDatabase()
    {
        $connectionName = config('database.default');
        $connection = config("database.connections.{$connectionName}", []);

        if (
            app()->environment() !== 'testing'
            || $connectionName !== 'sqlite'
            || ($connection['driver'] ?? null) !== 'sqlite'
            || ($connection['database'] ?? null) !== ':memory:'
            || ! empty($connection['url'])
        ) {
            throw new RuntimeException(
                'Feature tests using RefreshDatabase require APP_ENV=testing and SQLite :memory: with no DB_URL.'
            );
        }
    }
}
