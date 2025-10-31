<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StackItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'stack_items';
    protected $fillable = ['stack_name', 'payload', 'pushed_at'];
    protected $casts = [
        'payload'   => 'array',   // JSON <-> array
        'pushed_at' => 'datetime',
    ];
}
