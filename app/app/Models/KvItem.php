<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KvItem extends Model
{
    protected $table = 'kv_items';
    public $timestamps = false;

    protected $fillable = ['k','v','expires_at'];
    protected $casts = [
        'v' => 'array',
        'expires_at' => 'datetime',
    ];
}
