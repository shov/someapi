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
     */
    public function positive()
    {
        //Arrange

        /** @var Post $expectedPost */
        $expectedPost = AppMake::Post()
            ->newQuery()
            ->with('category')
            ->find(3);

        $token = $this->doLogin(\UserSeeder::USER_SEEDS['SUBSCRIBER']);

        //Act
        $response = $this->get(
            route("post::get", ['id' => $expectedPost->id]),
            $this->authHeaderForToken($token));

        //Assert
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
