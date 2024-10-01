<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
     protected $table = 'TransactionHistory';
     
    public function transactions()
    {
        return $this->hasMany(TransactionHistory::class, 'user_id'); // Assuming 'user_id' is the foreign key
    }
}
