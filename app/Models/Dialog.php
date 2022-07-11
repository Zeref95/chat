<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'is_group',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
