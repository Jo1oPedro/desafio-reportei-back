<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function testSuccessfulRegister(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'cascata',
            'email' => 'jpppedreira@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertArrayHasKey('expires_in', $data);
    }

    public function testFailedRegister()
    {
        try {
            $response = $this->post('/api/register', [
                'name' => 'cascata',
                'email' => 'jpppedreira@gmail.com',
                'password' => '123456',
                'password_confirmation' => '12345678'
            ]);

            $response->assertStatus(302);
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('errors', $response->json());
        }
    }
}
