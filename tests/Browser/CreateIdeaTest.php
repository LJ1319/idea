<?php

use App\Models\User;

it('creates a new idea', function () {
    $this->actingAs($user = User::factory()->create());

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Some example title')
        ->click('@button-status-completed')
        ->fill('description', 'An example description')
        ->click('Create')
        ->assertPathIs('/ideas');

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'Some example title',
        'status' => 'completed',
        'description' => 'An example description',
    ]);
});
