<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 20221126,
            'status' => "published",
            'imported_t' => Carbon::now(),
            'url' => "https://world.openfoodfacts.org/product/20221126",
            'creator' => "securita",
            'created_t' => 1415302075,
            'product_name' => "Madalenas quadradas",
            'quantity' => "380 g (6 x 2 u.)",
            'brands' => "La Cestera",
            'categories' => "Lanches comida, Lanches doces, Biscoitos e Bolos, Bolos, Madalenas",
            'labels' => "Contem glutem, Contém derivados de ovos, Contém ovos",
            'cities' => "",
            'purchase_places' => "Braga, Portugal",
            'stores' => "Lidl",
            'ingredients_text' => "farinha de trigo, açúcar, óleo vegetal de girassol, clara de ovo, ovo, humidificante (sorbitol), levedantes químicos (difosfato dissódico, hidrogenocarbonato de sódio), xarope de glucose-frutose, sal, aroma",
            'traces' => "Frutos de casca rija, Leite, Soja, Sementes de sésamo, Produtos à base de sementes de sésamo",
            'serving_size' => "madalena 31.7 g",
            'serving_quantity' => 31.7,
            'nutriscore_score' => 17,
            'nutriscore_grade' => "d",
            'main_category' => "en:madeleines",
            'image_url' => "https://static.openfoodfacts.org/images/products/20221126/front_pt.5.400.jpg",
        ];
    }
}
