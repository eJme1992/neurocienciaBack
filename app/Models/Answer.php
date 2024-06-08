<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

protected $table = 'answers';

    protected $fillable = ['user_id', 'survey_id', 'question_id', 'option_id', 'answer', 'time_spent'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
    
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
