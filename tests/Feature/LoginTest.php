<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Http\Controllers\AuthController;
class LoginTest extends TestCase
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
     * Test user login
     * @test
     */
    public function testLogin(){
        //Register the user
        $response = $this->postJson(self::REGISTER_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(201)
            ->assertJson([  
                'message' => 'User successfully registered',
            ]);

        //Login using correct credentials
        $response = $this->postJson(self::LOGIN_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(201);
    }

    /**
     * Test user failed login
     * @test
     */
    public function testLoginFailed(){
        //Register the user
        $response = $this->withoutExceptionHandling()->postJson(self::REGISTER_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(201)
            ->assertJson([  
                'message' => 'User successfully registered',
            ]);

        //Login using correct credentials
        $response = $this->postJson(self::LOGIN_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD_INCORRECT,
        ]);
        $response->assertStatus(401)
            ->assertJson([  
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * @test
     */
    public function testLoginLocking() {
        //Register the user
        $response = $this->postJson(self::REGISTER_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User successfully registered',
            ]);
        
            
        for($count = 1; $count < self::FAILED_ATTEMPT_COUNT; ++$count) {
            //Login using incorrect credentials
            $response = $this->postJson(self::LOGIN_API, [
                'email' => self::TEST_EMAIL,
                'password' => self::TEST_PASSWORD_INCORRECT,
            ]);
            $response->assertStatus(401);
        }

        $response = $this->postJson(self::LOGIN_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD_INCORRECT,
        ]);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials. Account locked for 5 minutes',
            ]);

        $response = $this->postJson(self::LOGIN_API, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD_INCORRECT,
        ]);
        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Account locked',
            ]);
    }
}