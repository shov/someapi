<?php

namespace Tests\Feature\Post;

use App\Domain\Post\Post;
use App\Helpers\AppMake;
use App\Helpers\TestHelper;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GetTest
 * @package Tests\Feature\Post
 */
class GetTest extends TestCase
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

        /** @var Post $expectedPost */
        $expectedPost = AppMake::Post()
            ->newQuery()
            ->with('category')
            ->find(3);

        //Act
        $response = $this->get(
            route("post::get", ['id' => $expectedPost->id]),
            $this->authHeaderForToken($token));

        //Assert
        if (!$success) {
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        } else {
            $response->assertStatus(Response::HTTP_OK);

            $response->assertJsonStructure([
                'header',
                'content',
                'category' => [
                    'id',
                    'name',
                ]
            ]);

            $response->assertJson([
                'header' => $expectedPost->header,
                'content' => $expectedPost->content,
                'category' => [
                    'id' => $expectedPost->category->id,
                    'name' => $expectedPost->category->name,
                ]
            ]);
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
        $response = $this->get(
            route("post::get", ['id' => $wrongPostId]),
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
            [\UserSeeder::USER_SEEDS['SUBSCRIBER'], true],
            [\UserSeeder::USER_SEEDS['EDITOR'], true],
            [\UserSeeder::USER_SEEDS['ADMIN'], true],
        ];
    }
}
