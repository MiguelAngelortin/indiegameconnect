<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    protected $fillable = [
    'user_id',
    'developer_id',
];

public function user(){
    return $this->belongsTo(User::class);
}

public function developer(){
    return $this->belongsTo(User::class, 'developer_id');
}
}


