<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function a_project_can_invite_a_user()
    {

        // Given I have a project
        $project = ProjectFactory::create();

        // And the owner of the project invites another user
        $project->invite($newUser = factory(User::class)->create());

        // Then, that new user will have permission to add tasks
        $this->signIn($newUser);
        $this->post(action('ProjectTasksController@store', $project), $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);
    }

    /**
     * @test
     */
    public function a_user_can_see_all_projects_they_have_benn_invited_to_on_their_dashboard()
    {
        // given we're sign in
        $user = $this->signIn();
        // and we're been invited to a project that was not by created by us

        $project = tap(ProjectFactory::create())->invite($user);

        // when I visit my dashboard
        // I should see that the project
        $this->get('/projects')
            ->assertSee($project->title);
    }
}
