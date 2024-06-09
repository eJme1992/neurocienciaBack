<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $table = 'users_answers';

    protected $fillable = ['name', 'email'];
    
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
