<?php

namespace Tests\Feature\User;

use App\Domain\User\Facades\UserService;
use App\Helpers\TestHelper;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class LoginTest
 * @package Tests\Feature\User
 */
class LoginTest extends TestCase
{
    use TestHelper;

    /**
     * @test
     */
    public function login()
    {
        //Arrange
        $userCreds = \UserSeeder::USER_SEEDS['SUBSCRIBER'];
        $user = UserService::getUserByEmail($userCreds['email']);

        //Act
        $response = $this->post(route('user::login'), [
            'email' => $userCreds['email'],
            'password' => $userCreds['password'],
        ]);

        //Assert
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'token',
        ]);

        $this->assertTrue(strlen($response->json()['token']) > 0);
    }

    /**
     * @test
     */
    public function loginSeveralUsers()
    {
        //Arrange
        $credentials = array_values(\UserSeeder::USER_SEEDS);

        //Act
        $responses = [];
        foreach ($credentials as $credential) {
            $responses[] = $this->post(route('user::login'), $credential);
        }

        //Assert
        $tokens = [];
        foreach ($credentials as $i => $credential) {
            $responses[$i]->assertStatus(Response::HTTP_OK);

            $responses[$i]->assertJsonStructure([
                'token',
            ]);

            $this->assertTrue(strlen($responses[$i]->json()['token']) > 0);
            $tokens[] = $responses[$i]->json()['token'];
        }

        $tokens = array_unique($tokens);
        $this->assertSame(count($responses), count($tokens));
    }

    /**
     * @test
     */
    public function wrongLogin()
    {
        //Arrange
        $email = 'aaaaaaaaa' . time() . '@aaaaaaaa.aa';
        $password = time() . 'UPPERlower';

        //Act
        $response = $this->post(route('user::login'), compact('email', 'password'));

        //Assert
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function secondTimeLogin()
    {
        //Arrange
        $userCreds = \UserSeeder::USER_SEEDS['EDITOR'];

        $responses = [];

        //Act
        $responses[] = $this->post(route('user::login'), $userCreds);

        $responses[] = $this->post(route('user::login'), $userCreds);

        //Assert
        foreach ($responses as $response) {
            $response->assertStatus(Response::HTTP_OK);

            $response->assertJsonStructure([
                'token',
            ]);
        }

        $this->assertNotEquals($responses[0]->json()['token'], $responses[1]->json()['token']);
    }
}
