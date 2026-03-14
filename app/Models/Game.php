<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title',
        'description',
        'publisher',    
        'release_date',    
        'status',    
        'engine',    
        'download_url',    
        'cover_image',    
        'version',    
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function genres(){
        return $this->belongsToMany(Genre::class);
    }
}