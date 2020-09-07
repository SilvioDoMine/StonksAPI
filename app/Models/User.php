<?php

namespace App\Models;

use App\Services\StockService;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Retorna todas as ações do usuário.
     * 
     * @return  Collection
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function getStocks()
    {
        $StockService = new StockService;

        $stocks = $this->stocks->pluck("code");

        $allStocks = [];

        foreach ($stocks as $stock) {
            $data = StockData::findByCode($stock);
  
            if (!$data || $data->updated_at->addMinute() < now()) {
                $allStocks[$stock] = $StockService->searchStock($stock);

                if (!$data) {
                    StockData::store($stock, $allStocks[$stock]);
                } else {
                    $data->price = $allStocks[$stock]["price"];
                    $data->variation_amount = $allStocks[$stock]["variation_amount"];
                    $data->variation_percentage = $allStocks[$stock]["variation_percentage"];
                    $data->updated_at = now();
                    $data->save();
                }

            } else {
                $allStocks[$stock] = [
                    "price" => $data->price,
                    "variation_amount" => $data->variation_amount,
                    "variation_percentage" => $data->variation_percentage,
                ];
            }
        }

        return $allStocks;
    }
}
