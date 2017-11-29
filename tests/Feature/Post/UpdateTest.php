<?php

namespace Tests\Feature\Post;

use App\Domain\Post\Post;
use App\Helpers\AppMake;
use App\Helpers\TestHelper;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UpdateTest
 * @package Tests\Feature\Post
 */
class UpdateTest extends TestCase
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

        $expectedCategory = AppMake::Category()
            ->newQuery()
            ->find(10);

        $expectedData = [
            'header' => 'BlahBlahBlah33',
            'content' => 'My new text',
            'category' => [
                'id' => $expectedCategory->id,
                'name' => $expectedCategory->name,
            ]
        ];

        /** @var Post $postToUpdate */
        $postToUpdate = AppMake::Post()
            ->newQuery()
            ->whereHas('category', function ($q) {
                $q->where('id', 1); //not 10 means
            })
            ->first();

        //Act
        $response = $this->put(
            route("post::update", ['id' => $postToUpdate->id]),
            [
                'header' => $expectedData['header'],
                'content' => $expectedData['content'],
                'category-id' => $expectedCategory->id,
            ],
            $this->authHeaderForToken($token));

        //Assert
        if (!$success) {
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        } else {
            $response->assertStatus(Response::HTTP_OK);

            $postToUpdate->refresh();
            $this->assertSame($expectedData['header'], $postToUpdate->header);
            $this->assertSame($expectedData['content'], $postToUpdate->content);
            $this->assertSame($expectedData['category']['id'], $postToUpdate->category->id);
            $this->assertSame($expectedData['category']['name'], $postToUpdate->category->name);
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
        $response = $this->put(
            route("post::update", ['id' => $wrongPostId]),
            [
                'header' => 'kkk',
                'content' => 'kkk',
                'category-id' => 1,
            ],
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
