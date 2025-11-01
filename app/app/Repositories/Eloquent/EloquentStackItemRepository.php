<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\StackItemRepositoryInterface;
use App\Models\StackItem;

class EloquentStackItemRepository implements StackItemRepositoryInterface
{
    /**
     * Create a new stack item with its payload and timestamp.
     *
     * @param  string  $payload
     * @return StackItem
     */
    public function create(string $payload): StackItem
    {
        return StackItem::create([
            'payload'    => $payload,
            'pushed_at'  => now(),
        ]);
    }

    /**
     * Find the top (most recently pushed) stack item and lock it for update.
     *
     * @return StackItem|null
     */
    public function findTopForUpdate(): ?StackItem
    {
        // LIFO: highest id; lock to prevent concurrent pop collisions
        return StackItem::orderByDesc('id')
            ->lockForUpdate()
            ->first();
    }

    /**
     * Delete a stack item by its primary key ID.
     *
     * @param  int  $id
     * @return void
     */
    public function deleteById(int $id): void
    {
        StackItem::whereKey($id)->delete();
    }
}
