<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductImportHistory;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import/update the products from the Open Food Facts database once a day.';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Product::truncate();

        $client = new Client();

        $productsFiles = ['products_01.json.gz', 'products_02.json.gz', 'products_03.json.gz',
                        'products_04.json.gz', 'products_05.json.gz', 'products_06.json.gz',
                        'products_07.json.gz', 'products_08.json.gz', 'products_09.json.gz'];

        foreach($productsFiles as $productFile){
            $response = $client->get("https://challenges.coode.sh/food/data/json/{$productFile}");

            if ($response->getStatusCode() === 200) {
                $gzContent = $response->getBody()->getContents();

                Storage::disk('local')->put($productFile, $gzContent);

                $gzFilePath = storage_path('app/' . $productFile);

                $jsonFilePath = storage_path('app/temp.json');

                exec("gunzip -c $gzFilePath > $jsonFilePath");

                $jsonStream = fopen($jsonFilePath, 'r');

                $lines = [];

                while (($line = fgets($jsonStream)) !== false && count($lines) < 100) {
                    $jsonData = json_decode($line, true);
                    if (!empty($jsonData) && is_array($jsonData)) {
                        $lines[] = $jsonData;
                    }
                }

                fclose($jsonStream);

                foreach ($lines as $lineData) {
                    $createProductData = Product::create([
                        'code' => $lineData['code'],
                        'status' => 'draft',
                        'imported_t' => Carbon::now(),
                        'url' => $lineData['url'],
                        'creator' => $lineData['creator'],
                        'created_t' => $lineData['created_t'],
                        'last_modified_t' => $lineData['last_modified_t'],
                        'product_name' => $lineData['product_name'],
                        'quantity' => $lineData['quantity'],
                        'brands' => $lineData['brands'],
                        'categories' => $lineData['categories'],
                        'labels' => $lineData['labels'],
                        'cities' => $lineData['cities'],
                        'purchase_places' => $lineData['purchase_places'],
                        'stores' => $lineData['stores'],
                        'ingredients_text' => $lineData['ingredients_text'],
                        'traces' => $lineData['traces'],
                        'serving_size' => $lineData['serving_size'],
                        'serving_quantity' => ($lineData['serving_quantity'] !== "") ? $lineData['serving_quantity'] : null,
                        'nutriscore_score' => ($lineData['nutriscore_score'] !== "") ? $lineData['nutriscore_score'] : null,
                        'nutriscore_grade' => $lineData['nutriscore_grade'],
                        'main_category' => $lineData['main_category'],
                        'image_url' => $lineData['image_url'],
                    ]);
                }

                ProductImportHistory::create([
                    'imported_at' => Carbon::now(),
                    'log'         => 'Successfully completed updated in the database.'
                ]);

            } else {
                ProductImportHistory::create([
                    'imported_at' => Carbon::now(),
                    'log'         => 'Unable to complete updating product data in the database'
                ]);

                $this->info('Failure to import products.');
            }
        }

        $this->info('Data updated successfully!');
    }
}
