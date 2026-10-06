<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_api_root_returns_running_message(): void
    {
        $response = $this->get('/api');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Splitwise API is running.');
    }
}
