<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\StackItemRepositoryInterface;
use App\Models\StackItem;

class EloquentStackItemRepository implements StackItemRepositoryInterface
{
    public function create(string $payload): StackItem
    {
        return StackItem::create([
            'payload'    => $payload,
            'pushed_at'  => now(),
        ]);
    }

    public function findTopForUpdate(): ?StackItem
    {
        // LIFO: highest id; lock to prevent concurrent pop collisions
        return StackItem::orderByDesc('id')
            ->lockForUpdate()
            ->first();
    }

    public function deleteById(int $id): void
    {
        StackItem::whereKey($id)->delete();
    }

    public function countByStack(string $stack): int
    {
        return StackItem::where('stack_name', $stack)->count();
    }
}
