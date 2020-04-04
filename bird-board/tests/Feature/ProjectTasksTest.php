<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
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
        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test Task']);

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
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true,
        ]);
    }

    /**
     * @test
     */
    public function a_task_can_be_marked_as_incomplete()
    {
        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => true,
            ]);

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => false,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => false,
        ]);
    }


    /**
     * @test
     */
    public function only_the_owner_of_the_project_may_update_a_task()
    {

        $this->signIn();

        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /**
     * @test
     */
    public function a_task_require_a_body()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->actingAs($project->owner)->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
