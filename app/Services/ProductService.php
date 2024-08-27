<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @return string[]
     */
    public function saveProducts(): array
    {
        $data = $this->apiService->search('products', 'iPhone');

        if (!$this->isValidData($data)) {
            return ['error' => 'Invalid data format'];
        }

        $productsSaved = 0;
        $errors = [];

        foreach ($this->filterProductsByTitle($data['products'], 'iPhone') as $product) {
            try {
                Product::updateOrCreate(
                    ['id' => $product['id']],
                    [
                        'name' => $product['title'],
                        'description' => $product['description'] ?? 'No description',
                        'price' => $product['price'],
                        'category' => $product['category'] ?? 'default',
                        'stock' => $product['stock'] ?? 0
                    ]
                );
                $productsSaved++;
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if ($errors) {
            return ['error' => 'Some products could not be saved', 'details' => $errors];
        }

        return ['message' => "$productsSaved products saved successfully"];
    }

    /**
     * @return array
     */
    public function getIphones(): array
    {
        $iphones = Product::where('name', 'LIKE', '%iPhone%')->get();

        if ($iphones->isEmpty()) {
            return ['message' => 'No iPhones found'];
        }

        return $iphones->toArray();
    }

    /**
     * @param array $data
     * @return Product
     */
    public function saveProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    private function isValidData(array $data): bool
    {
        return isset($data['products']) && is_array($data['products']);
    }

    /**
     * @param array $products
     * @param string $title
     * @return array
     */
    private function filterProductsByTitle(array $products, string $title): array
    {
        return array_filter($products, function ($product) use ($title) {
            return stripos($product['title'], $title) !== false;
        });
    }
}

