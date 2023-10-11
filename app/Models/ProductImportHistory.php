<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImportHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'imported_at',
        'log'
    ];
}
