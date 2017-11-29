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

    /**
     * Do user login request with given credentials or with default subscriber credentials
     * @param array|null $creds
     * @return string Token
     */
    protected function doLogin(?array $creds = null): string
    {
        $creds = $creds ?? \UserSeeder::USER_SEEDS['SUBSCRIBER'];
        $response = $this->post(route("user::login"), $creds);
        return $response->json()['token'];
    }
}