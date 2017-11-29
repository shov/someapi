<?php declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Contracts\Console\Kernel;

trait TestHelper
{
    use DatabaseMigrations;
    use CommonHelper;
    use DbHelper;

    public function runDatabaseMigrations()
    {
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }

    /**
     * Return array with JWT Auth header for passed token
     *
     * @param string $token
     * @return array
     */
    protected function authHeaderForToken(string $token): array
    {
        return [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ];
    }
}