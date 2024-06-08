<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = ['title', 'description'];
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
