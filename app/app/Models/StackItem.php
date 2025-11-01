<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StackItem extends Model
{
    use HasFactory;

    protected $table = 'stack_items';
    protected $fillable = ['payload'];
    protected $casts = [
        'payload'   => 'array',   // JSON <-> array
    ];
}
