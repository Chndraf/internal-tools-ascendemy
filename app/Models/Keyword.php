<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keyword extends Model
{
    protected $fillable = ['name'];

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }
}
