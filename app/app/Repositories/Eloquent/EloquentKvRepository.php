<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\KvRepositoryInterface;
use App\Models\KvItem;
use Illuminate\Support\Carbon;

class EloquentKvRepository implements KvRepositoryInterface
{
    public function upsert(string $key, $value, ?int $ttlSeconds): KvItem
    {
        $expiresAt = $ttlSeconds ? Carbon::now()->addSeconds($ttlSeconds) : null;

        return KvItem::updateOrCreate(
            ['k' => $key],
            ['v' => $value, 'expires_at' => $expiresAt]
        );
    }

    public function findValid(string $key): ?KvItem
    {
        $now = Carbon::now();
        $item = KvItem::where('k', $key)->first();

        if (!$item) return null;
        if ($item->expires_at && $item->expires_at->lte($now)) {
            // Hard-expired: remove and treat as not found
            $item->delete();
            return null;
        }
        return $item;
    }

    public function delete(string $key): void
    {
        KvItem::where('k', $key)->delete();
    }

    public function purgeExpired(): int
    {
        return KvItem::whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now())
            ->delete();
    }
}
