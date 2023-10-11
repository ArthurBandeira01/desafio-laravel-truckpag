<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImportHistory;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private $productModel;
    private $productImportHistoryModel;

    public function __construct(Product $productModel, ProductImportHistory $productImportHistoryModel)
    {
        $this->productModel = $productModel;
        $this->productImportHistoryModel = $productImportHistoryModel;
    }

    public function index()
    {
        $databaseConnection = DB::connection()->getPdo() ? 'Connected' : 'Not Connected';
        $lastCronExecution = Cache::get('last_cron_execution', 'Not executed');
        $uptime = Carbon::parse(Cache::get('api_online_since'))->diffForHumans();
        $memoryUsage = memory_get_usage(true) / 1024 / 1024;

        $statusCode = 200;

        if($databaseConnection === 'Not Connected') $statusCode = 503;

        return response()->json([
            'Status' => $statusCode,
            'DatabaseConnection' => $databaseConnection,
            'LastCronExecution' => $lastCronExecution,
            'Uptime' => $uptime,
            'MemoryUsage' => $memoryUsage . ' MB',
        ], $statusCode);
    }

    public function listProducts()
    {
        $listProducts = $this->productModel->paginate();

        return ProductResource::collection($listProducts);
    }

    public function showProduct($code)
    {
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return new ProductResource($product);
    }

    public function updateProduct($code)
    {
        try {
            $client = new Client();

            $response = $client->get("https://world.openfoodfacts.org/api/v2/product/{$code}.json");

            $content = json_decode($response->getBody()->getContents());
            $updateProductData = Product::where('code', $code)->update([
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

            if($updateProductData){
                $productUpdated = $this->productModel->where('code', $code)->first();
                return new ProductResource($productUpdated);
            }else{
                return response()->json(['message' => 'Failure to update product!']);
            }

        } catch (Exception $exception){
            $errorMessage = $exception->getMessage();
            return response()->json(['message' => 'Failure to update product. Error: ' .$errorMessage]);
        }

    }

    public function deleteProduct($code)
    {
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update(['status' => 'trash']);
        $product->delete();

        return new ProductResource($product);
    }
}
