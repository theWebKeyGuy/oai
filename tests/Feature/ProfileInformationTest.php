<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_can_be_updated()
    {
        $this->actingAs($user = User::factory()->make([
            'first_name'    => 'Test',
            'last_name'     => 'Name',
            'email'         => 'test@example.com'
        ]));

        $response = $this->put('/user/profile-information', [
            'first_name'    => 'Test',
            'last_name'     => 'Name',
            'email'         => 'test@example.com'
        ]);

        $this->assertEquals('Test', $user->fresh()->first_name);
        $this->assertEquals('Name', $user->fresh()->last_name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}
