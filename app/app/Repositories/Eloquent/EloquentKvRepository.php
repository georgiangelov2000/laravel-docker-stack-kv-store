<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\KvRepositoryInterface;
use App\Models\KvItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EloquentKvRepository implements KvRepositoryInterface
{
    /**
     * Insert or update a key-value pair with optional TTL.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int|null  $ttlSeconds
     * @return KvItem
     */
    public function upsert(string $key, mixed $value, ?int $ttlSeconds): KvItem
    {
        return DB::transaction(function () use ($key, $value, $ttlSeconds) {
            $expiresAt = $ttlSeconds ? Carbon::now()->addSeconds($ttlSeconds) : null;

            $item = KvItem::where('k', $key)->lockForUpdate()->first();

            if ($item !== null) {
                $item->fill([
                    'v' => $value,
                    'expires_at' => $expiresAt,
                ]);

                $item->save();

                return $item->refresh();
            }

            return KvItem::create([
                'k' => $key,
                'v' => $value,
                'expires_at' => $expiresAt,
            ]);
        });
    }


    /**
     * Find a valid (non-expired) key-value record by key.
     *
     * @param  string  $key
     * @return KvItem|null
     */
    public function findValid(string $key): ?KvItem
    {
        return DB::transaction(function () use ($key) {
            $now = Carbon::now();
            $item = KvItem::where('k', $key)->lockForUpdate()->first();

            if ($item === null) {
                return null;
            }

            if ($item->expires_at && $item->expires_at->lte($now)) {
                // Hard-expired: remove and treat as not found
                $item->delete();
                return null;
            }

            return $item;
        });
    }


    /**
     * Delete a key-value pair by key.
     *
     * @param  string  $key
     * @return void
     */
    public function delete(string $key): void
    {
        KvItem::where('k', $key)->delete();
    }


    /**
     * Remove all expired key-value records.
     *
     * @return int  Number of deleted records
     */
    public function purgeExpired(): int
    {
        return KvItem::whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now())
            ->delete();
    }
}
