<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * Retorna o usuário desta ação
     * 
     * @return  \App\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
