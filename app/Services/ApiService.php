<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
    }

    /**
     * @param $endpoint
     * @param $query
     * @return array|mixed
     */
    public function search($endpoint, $query): mixed
    {
        $response = Http::get("{$this->apiUrl}/{$endpoint}/search", ['q' => $query]);
        return $response->json();
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array|mixed
     */
    public function add(string $endpoint, array $data): mixed
    {
        $response = Http::post("{$this->apiUrl}/{$endpoint}/add", $data);
        return $response->json();
    }
}
