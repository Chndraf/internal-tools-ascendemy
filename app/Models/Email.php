<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    protected $fillable = ['email', 'author', 'title', 'doi', 'country_id', 'keyword_id'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }
}
