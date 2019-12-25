<?php
/**
 * Created by PhpStorm.
 * User: khzero
 * Date: 2019-12-23
 * Time: 오후 11:38
 */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_create_a_projecT()
    {
        // Exception Handling 처리 제외
        $this->withoutExceptionHandling();

        // given
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        // when & then
        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /**
     * @test
     */
    public function a_project_requires_a_title() {
        $this->withoutMiddleware();
        $this->post('/projects', [])->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_project_requires_a_description() {
        $this->withoutMiddleware();
        $this->post('/projects', [])->assertSessionHasErrors('description');
    }
}
