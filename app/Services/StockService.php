<?php

namespace App\Services;

use Goutte\Client;
use Exception;

class StockService
{
    private $Client;

    /**
     * Nova instância do serviço
     */
    public function __construct()
    {
        $this->Client = new Client();
    }

    /**
     * 
     */
    public function getStockData(string $stockCode)
    {
        return $this->searchStock($stockCode);
    }

    /**
     * 
     */
    public function searchStock(string $stockCode)
    {
        $crawler = $this->Client->request("GET", "http://www.google.com/search?q={$stockCode}");

        $stockData = [];

        $crawler->filter(".iBp4i")->each(function($node) use (&$stockData) {
            $data = explode(" ", $node->text());

            $stockData["price"] = $this->sanitizeValue($data[0]);
            $stockData["variation_amount"] = $this->sanitizeValue($data[1]);
            $stockData["variation_percentage"] = $this->sanitizeValue($data[2]);
        });

        if (empty($stockData)) {
            throw new Exception("Não foi possível pegar dados da ação [{$stockCode}]. Nenhum valor foi encontrado.");
        }

        return $stockData;
    }

    private function sanitizeValue(string $string): string
    {
        $string = str_replace("(", "", $string);
        $string = str_replace(")", "", $string);
        $string = str_replace(",", ".", $string);
        
        return $string;
    }
}
