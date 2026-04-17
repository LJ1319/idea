<?php

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'johndoe@example.com')
        ->fill('password', 'password')
        ->press('Create Account')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
    ]);
});

it('requires a valid email', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john doe')
        ->fill('password', 'password')
        ->press('Create Account')
        ->assertPathIs('/register');
});
