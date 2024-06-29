<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateUser()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();
        $user->update(['first_name' => 'UpdatedName']);
        $this->assertEquals('UpdatedName', $user->fresh()->first_name);
    }

    public function testDeleteUser()
    {
        $user = User::factory()->create();
        $user->delete();
        $this->assertSoftDeleted($user);
    }

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
