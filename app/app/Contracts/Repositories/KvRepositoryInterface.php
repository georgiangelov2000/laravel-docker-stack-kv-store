<?php
declare(strict_types=1);

namespace App\Contracts\Repositories;
use App\Models\KvItem;

interface KvRepositoryInterface
{
    public function upsert(string $key, $value, ?int $ttlSeconds): KvItem;
    public function findValid(string $key): ?KvItem;  // returns null if not found/expired
    public function delete(string $key): void;
    public function purgeExpired(): int;
}
