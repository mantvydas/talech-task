<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function testProductStoreWrongData()
    {
        $user = factory('App\Models\User')->create();
        $requestData = [
            'name'     =>     'test',
            'ean'     =>     '2491138689688',
        ];
        $response = $this->actingAs($user)->call('POST', '/products', $requestData);
        $response->assertSessionHasErrors(['color']);
    }

    public function testProductStoreSuccessfully()
    {
        $user = factory('App\Models\User')->create();
        $requestData = [
            'name'     =>     'test',
            'ean'     =>     '1491028679682',
            'color'     =>     'red',
            'price'     =>     15.52,
            'weight'     =>     15.52,
            'quantity'     =>     15,
            'type'     =>     'ladies',
            'active'     =>     1,
            'images' => [
                UploadedFile::fake()->image('avatar.jpg')
            ]
        ];

        $response = $this->actingAs($user)->call('POST', '/products', $requestData);
        $response->assertSessionHasNoErrors();
    }

    public function testProductUpdateSuccessfully()
    {
        $user = factory('App\Models\User')->create();
        $product = (factory('App\Models\Product')->create())->toArray();
        $product['quantity'] = 2;

        $response = $this->actingAs($user)->call('PUT', '/products/'.$product['id'], $product);
        $response->assertSessionHasNoErrors();
    }

    public function testProductApiGetUnauthorized()
    {
        $this->json('GET', '/api/products')
            ->assertStatus(401);
    }

    public function testProductApiStoreUnauthorized()
    {
        $this->json('POST', '/api/products')
            ->assertStatus(401);
    }

    public function testProductApiStoreWrongData()
    {
        $user = factory('App\Models\User')->create();
        $token = $user->createToken('test')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('POST', '/api/products', [], $headers)
            ->assertStatus(422);
    }

    public function testProductApiGetProductNotFound()
    {
        $user = factory('App\Models\User')->create();
        $token = $user->createToken('test')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('GET', '/api/products/88888888', [], $headers)
            ->assertStatus(404);
    }

    public function testProductApiGetProductSuccessfully()
    {
        $user = factory('App\Models\User')->create();
        $token = $user->createToken('test')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        $product = factory('App\Models\Product')->create();
        $this->json('GET', '/api/products/'.$product->id, [], $headers)
            ->assertStatus(200);
    }

    public function testProductApiGetProductsSuccessfully()
    {
        $user = factory('App\Models\User')->create();
        $token = $user->createToken('test')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('GET', '/api/products', [], $headers)
            ->assertStatus(200);
    }

    public function testProductApiDeleteProductSuccessfully()
    {
        $user = factory('App\Models\User')->create();
        $token = $user->createToken('test')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        $product = factory('App\Models\Product')->create();
        $this->json('DELETE', '/api/products/'.$product->id, [], $headers)
            ->assertStatus(204);
    }
}
