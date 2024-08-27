<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Product;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Product::factory()->create([
            'name' => 'iPhone 12',
            'description' => 'Description for iPhone 12',
            'price' => 799.99,
            'category' => 'electronics',
            'stock' => 5,
        ]);
    }

    #[Test]
    public function it_can_fetch_all_iphones(): void
    {
        $response = $this->get('/api/iphones');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'iPhone 12']);
        $response->assertJsonMissing(['name' => 'Samsung Galaxy']);
    }

    #[Test]
    public function it_can_create_a_product(): void
    {
        $response = $this->post('/api/products/save');
        $response->assertStatus(201);
        $response->assertJson(fn ($json) => $json->where('message', '7 products saved successfully')->etc());
        $this->assertDatabaseHas('products', [
            'name' => 'iPhone 12',
            'description' => 'Description for iPhone 12',
            'price' => 799.99,
            'category' => 'electronics',
            'stock' => 5,
        ]);
    }
}


