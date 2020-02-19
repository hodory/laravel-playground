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
    public function non_owner_may_not_invite_users()
    {
        $project = ProjectFactory::create();
        $user = factory(User::class)->create();

        $assertInvitationForbidden = function () use ($project, $user) {
            $this->actingAs($user)
                ->post($project->path() . '/invitations')
                ->assertStatus(403);
        };

        $assertInvitationForbidden();
        $project->invite($user);

        $assertInvitationForbidden();
    }

    /**
     * @test
     */
    public function a_project_owner_can_invite_a_user()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $userToInvite = factory(User::class)->create();
        $this->actingAs($project->owner)->post($project->path() . '/invitations', [
            'email' => $userToInvite->email,
        ])
            ->assertRedirect($project->path()); // invite a user

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /**
     * @test
     */
    public function the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => 'notauser@example.com',
            ])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have a Birdboard account.',
            ], null, 'invitations');

    }

    /**
     * @test
     */
    public function invited_users_may_update_project_details()
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
