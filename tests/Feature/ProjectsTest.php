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
        $attributes = factory('App\Project')->raw();

        // when & then
        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /**
     * @test
     */
    public function a_user_can_view_a_project()
    {
        $this->withoutExceptionHandling();

        // given
        $project = factory('App\Project')->create();

        // when & then
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /**
     * @test
     */
    public function a_project_requires_a_title()
    {
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_project_requires_a_description()
    {
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /**
     * @test
     */
    public function a_project_requires_a_owner()
    {
        $attributes = factory('App\Project')->raw(['owner_id' => null]);
        $this->post('/projects', $attributes)->assertSessionHasErrors('owner_id');
    }
}
