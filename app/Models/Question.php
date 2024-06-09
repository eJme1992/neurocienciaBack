<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = ['survey_id', 'text', 'type','name','page'];
    
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
    
    public function options()
    {
        return $this->hasMany(Option::class);
    }
    
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
