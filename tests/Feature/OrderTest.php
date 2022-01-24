<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use ProductSeeder;

class OrderingTest extends TestCase {
    use RefreshDatabase;

    const API_REGISTER = '/api/auth/register';
    const API_ORDER = '/api/auth/order';
    
    const TEST_EMAIL = 'backend@multisyscorp.com';
    const TEST_PASSWORD = 'test123';

    private User $user;
    
    protected function setUp(): void {
        parent::setUp();
        
        //Register the user
        $response = $this->postJson(self::API_REGISTER, [
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
        ]);
        $response->assertStatus(201)
            ->assertJson([  
                'message' => 'User successfully registered',
            ]);

        $this->user = User::firstWhere('email', '=', self::TEST_EMAIL);
        $this->seed(ProductSeeder::class);
    }

    /**
     * @test
     */
    public function testOrderSuccess() : void {
        //Testing product is not enough
        $response = $this->actingAs($this->user, 'api')
            ->postJson(self::API_ORDER, [
                'product_id' => 1,
                'quantity' => 2,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'You have successfully ordered this product',
            ]);
    }

    /**
     * @test
     */
    public function testOrderFailed() : void {
        $response = $this->actingAs($this->user, 'api')
            ->postJson(self::API_ORDER, [
                'product_id' => 2,
                'quantity' => 9999,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Failed to order this product due to unavailability of the stock',
            ]);
    }
}