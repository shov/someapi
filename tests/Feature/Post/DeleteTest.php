<?php

namespace Tests\Feature\Post;

use App\Domain\Post\Post;
use App\Helpers\AppMake;
use App\Helpers\TestHelper;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class DeleteTest
 * @package Tests\Feature\Post
 */
class DeleteTest extends TestCase
{
    use TestHelper;

    /**
     * @test
     * @dataProvider userRolesDataProvider
     */
    public function testWithUserRoles($creds, $success)
    {
        //Arrange
        $token = is_null($creds) ? 'some.shit' : $this->doLogin($creds);

        /** @var Post $postToDelete */
        $postToDeleteId = AppMake::Post()
            ->newQuery()
            ->find(1)
            ->id;

        //Act
        $response = $this->delete(
            route("post::delete", ['id' => $postToDeleteId]),
            [],
            $this->authHeaderForToken($token));

        //Assert
        if (!$success) {
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        } else {
            $response->assertStatus(Response::HTTP_OK);

            $this->assertTrue(is_null(AppMake::Post()
                ->newQuery()
                ->find($postToDeleteId)));
        }
    }

    /**
     * @test
     * @dataProvider userRolesDataProvider
     */
    public function testWrongId($creds, $authSuccess)
    {
        //Arrange
        $token = is_null($creds) ? 'some.shit' : $this->doLogin($creds);

        $wrongPostId = AppMake::Post()
                ->newQuery()
                ->max('id') * 2;

        //Act
        $response = $this->delete(
            route("post::delete", ['id' => $wrongPostId]),
            [],
            $this->authHeaderForToken($token));

        //Assert
        if (!$authSuccess) {
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        } else {
            $response->assertStatus(Response::HTTP_NOT_FOUND);
        }
    }

    public function userRolesDataProvider()
    {
        return [
            [null, false],
            [\UserSeeder::USER_SEEDS['SUBSCRIBER'], false],
            [\UserSeeder::USER_SEEDS['EDITOR'], true],
            [\UserSeeder::USER_SEEDS['ADMIN'], true],
        ];
    }
}
