<?php

use App\Models\User;

it('logs in a user', function () {
    $user = User::factory()->create(['password' => 'password']);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->click('@login-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'name' => $user->name,
        'email' => $user->email,
    ]);
});

it('logs out a user', function () {
    $user = User::factory()->create(['password' => 'password']);

    $this->actingAs($user);

    visit('/')->click('Log Out');

    $this->assertGuest();
});
