<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use App\Models\Product;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    //It will check the connection on the route GET /
    public function testIndex(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertJson([
            'DatabaseConnection' => 'Connected'
        ]);
    }

    //List products with paginate on the route GET /products
    public function testListProducts()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
    }

    //List the product and your content on the route GET /products/{code}
    public function testShowProduct()
    {
        $codeProduct = Product::factory()->create();

        $response = $this->get("/products/{$codeProduct->code}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'status',
                'imported_t',
                'url',
                'creator',
                'created_t',
                'last_modified_t',
                'product_name',
                'quantity',
                'brands',
                'categories',
                'labels',
                'cities',
                'purchase_places',
                'stores',
                'ingredients_text',
                'traces',
                'serving_size',
                'serving_quantity',
                'nutriscore_score',
                'nutriscore_grade',
                'main_category',
                'image_url',
            ],
        ]);
    }

    //Update products in the database on the route PUT /products/{code}
    public function testUpdateProduct()
    {
        $product = Product::factory()->create();

        $client = new Client();

        $response = $client->get("https://world.openfoodfacts.org/api/v2/product/{$product->code}.json");

        $content = json_decode($response->getBody()->getContents());

        $updateProductData = Product::where('code', $product->code)->update([
            'code' => $content->code,
            'status' => 'draft',
            'imported_t' => Carbon::now(),
            'creator' => $content->product->creator,
            'created_t' => $content->product->created_t,
            'last_modified_t' => $content->product->last_modified_t,
            'product_name' => $content->product->product_name,
            'quantity' => $content->product->quantity,
            'brands' => $content->product->brands,
            'categories' => $content->product->categories,
            'labels' => $content->product->labels,
            'purchase_places' => $content->product->purchase_places,
            'stores' => $content->product->stores,
            'ingredients_text' => $content->product->ingredients_text,
            'traces' => $content->product->traces,
            'serving_size' => $content->product->serving_size,
            'serving_quantity' => ($content->product->serving_quantity !== "") ? $content->product->serving_quantity : null,
            'nutriscore_score' => ($content->product->nutriscore_score !== "") ? $content->product->nutriscore_score : null,
            'nutriscore_grade' => $content->product->nutriscore_grade,
            'image_url' => $content->product->image_url,
        ]);

        $response = $this->put("/products/{code}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'status',
                'imported_t',
                'url',
                'creator',
                'created_t',
                'last_modified_t',
                'product_name',
                'quantity',
                'brands',
                'categories',
                'labels',
                'cities',
                'purchase_places',
                'stores',
                'ingredients_text',
                'traces',
                'serving_size',
                'serving_quantity',
                'nutriscore_score',
                'nutriscore_grade',
                'main_category',
                'image_url',
            ],
        ]);
    }

    //Delete product (status = 'trash') on the route DELETE /products/{code}
    public function testDeleteProduct()
    {
        $product = Product::factory()->create();

        $response = $this->delete("/products/{$product->code}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'status',
                'imported_t',
                'url',
                'creator',
                'created_t',
                'last_modified_t',
                'product_name',
                'quantity',
                'brands',
                'categories',
                'labels',
                'cities',
                'purchase_places',
                'stores',
                'ingredients_text',
                'traces',
                'serving_size',
                'serving_quantity',
                'nutriscore_score',
                'nutriscore_grade',
                'main_category',
                'image_url',
            ],
        ]);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
