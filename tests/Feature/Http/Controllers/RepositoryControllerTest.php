<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_guest()
    {
        $this->get('repositories')->assertRedirect('login');        // index
        $this->get('repositories/1')->assertRedirect('login');      // show
        $this->get('repositories/1/edit')->assertRedirect('login'); // edit
        $this->put('repositories/1')->assertRedirect('login');      // update
        $this->delete('repositories/1')->assertRedirect('login');   // destroy
        $this->get('repositories/create')->assertRedirect('login'); // create
        $this->post('repositories', [])->assertRedirect('login');   // store - guardar
    }

    public function test_index_empty()
    {
      // $this->withoutExceptionHandling();
      Repository::factory()->create(); // user_id = 1
      $user = User::factory()->create(); // id = 2

      $this
        ->actingAs($user)
        ->get('repositories')
        ->assertStatus(200)
        ->assertSee('No hay repositorios creados');
    }

    public function test_index_with_data()
    {
      // $this->withoutExceptionHandling();
      $user = User::factory()->create(); // id = 1
      $repository = Repository::factory()->create(['user_id' => $user->id]); // user_id = 1

      $this
        ->actingAs($user)
        ->get('repositories')
        ->assertStatus(200)
        ->assertSee($repository->id)
        ->assertSee($repository->url);
    }

    public function test_store()
    {
      $this->withoutExceptionHandling();

      $data = [
        'url' => $this->faker->url,
        'description' => $this->faker->text,
      ];
      $user = User::factory()->create();

      $this->actingAs($user)->post('repositories', $data)->assertRedirect('repositories');
      $this->assertDatabaseHas('repositories', $data);
    }

    public function test_update()
    {
      $this->withoutExceptionHandling();
      
      $user = User::factory()->create();
      $repository = Repository::factory()->create(['user_id' => $user->id]);
      $data = [
        'url' => $this->faker->url,
        'description' => $this->faker->text,
      ];

      $this->actingAs($user)->put("repositories/$repository->id", $data)->assertRedirect("repositories/$repository->id/edit");
      $this->assertDatabaseHas('repositories', $data);
    }

    public function test_update_policy()
    {
      $user = User::factory()->create(); // id = 1
      $repository = Repository::factory()->create(); // user_id = 2
      $data = [
        'url' => $this->faker->url,
        'description' => $this->faker->text,
      ];

      $this->actingAs($user)
        ->put("repositories/$repository->id", $data)
        ->assertStatus(403);
    }

    // VALIDACIONES
    public function test_validate_store()
    {
      // $this->withoutExceptionHandling();

      $user = User::factory()->create();

      $this->actingAs($user)
        ->post('repositories', [])
        ->assertStatus(302)
        ->assertSessionHasErrors(['url', 'description']);
    }

    public function test_validate_update()
    {
      // $this->withoutExceptionHandling();
      
      $repository = Repository::factory()->create();
      $user = User::factory()->create();

      $this->actingAs($user)
        ->put("repositories/$repository->id", [])
        ->assertStatus(302)
        ->assertSessionHasErrors(['url', 'description']);
    }

    public function test_destroy()
    {
      $this->withoutExceptionHandling();
      
      $user = User::factory()->create();
      $repository = Repository::factory()->create(['user_id' => $user->id]);

      $this->actingAs($user)
        ->delete("repositories/$repository->id")
        ->assertRedirect('repositories');
      $this->assertDatabaseMissing('repositories', [
        'id' => $repository->id,
        'url' => $repository->url,
        'description' => $repository->description
      ]);
    }

    public function test_destroy_policy()
    {
      $user = User::factory()->create(); // id = 1
      $repository = Repository::factory()->create(); // user_id = 2

      $this
        ->actingAs($user)
        ->delete("repositories/$repository->id")
        ->assertStatus(403);
    }
}
