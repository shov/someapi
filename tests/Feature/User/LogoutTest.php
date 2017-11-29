<?php

namespace Tests\Feature\User;

use App\Helpers\TestHelper;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class LogoutTest
 * @package Tests\Feature\User
 */
class LogoutTest extends TestCase
{
    use TestHelper;

    /**
     * @test
     */
    public function realLogout()
    {
        //Arrange
        $token = $this->doLogin();

        //Act
        $responseLogout = $this->post(route('user::logout'), [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);
        /*$responseTryToGetAfterLogout = $this->get(route('post::get', ['id' => 1]), [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);*/
        $responseTryLogoutAfterLogout = $this->post(route('user::logout'), [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        //Assert
        $responseLogout->assertStatus(Response::HTTP_OK);
        //$responseTryToGetAfterLogout->assertStatus(Response::HTTP_UNAUTHORIZED);
        $responseTryLogoutAfterLogout->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function pretenderLogout()
    {
        //Arrange
        $token = 'some.shit.here' . time();

        //Act
        $responseTryLogoutWithFakeToken = $this->post(route('user::logout'), [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        //Assert
        $responseTryLogoutWithFakeToken->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
