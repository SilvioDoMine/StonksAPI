<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockData extends Model
{
    public static function store(string $stockCode, array $stockInfo)
    {
        $stock = new self;

        $stock->code = $stockCode;
        $stock->price = $stockInfo['price'];
        $stock->variation_amount = $stockInfo['variation_amount'];
        $stock->variation_percentage = $stockInfo['variation_percentage'];

        $stock->save();

        return $stock;
    }

    public static function updateData(string $name, array $data)
    {
   
    }

    /**
     * Procura a informação de uma ação à partir do seu papel.
     * 
     * @param   string  $stockCode
     * @return  \App\Models\StockData|null
     */
    public static function findByCode(string $stockCode)
    {
        return self::where('code', $stockCode)->first();
    }
}
