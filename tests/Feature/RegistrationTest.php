<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Http\Controllers\AuthController;
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    const ORDER_API = '/api/auth/order';
    const REGISTER_API = '/api/auth/register';
    const LOGIN_API = '/api/auth/login';
    
    const TEST_EMAIL = 'backend@multicorp.com';
    const TEST_PASSWORD = 'test123';
    const TEST_PASSWORD_INCORRECT = 'test121231233';

    const FAILED_ATTEMPT_COUNT = AuthController::FAILED_ATTEMPT_COUNT;
    
    /**
     * Test user registration
     * @test
     */
    public function testRegistration() : User {
        //Register the user
        $response = $this->postJson(self::REGISTER_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(201)
            ->assertJson([  
                'message' => 'User successfully registered',
            ]);

        //Check if the user exists in the database
        $user = User::where('email', self::TEST_EMAIL)->first();
        $this->assertNotNull($user);
        
        return $user;
    }

    /**
     * Test duplicate email
     * @depends testRegistration
     * @test
     */
    public function testRegistrationDuplicate(User $user) : void {
        $response = $this->postJson(self::REGISTER_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User successfully registered',
            ]);

        $response = $this->postJson(self::REGISTER_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Email already taken',
            ]);
    }
}