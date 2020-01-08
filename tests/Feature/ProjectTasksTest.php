<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $this->post($project->path() . '/tasks', ['body' => 'Test Task']);

        $this->get($project->path())
            ->assertSee('Test Task');
    }


    /**
     * @test
     */
    public function only_the_owner_of_the_project_may_add_tasks()
    {

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Task']);
    }

    /**
     * @test
     */
    public function a_task_require_a_body()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
