<?php
declare(strict_types=1);

namespace App\Contracts\Services;

interface KvServiceInterface
{
    public function set(string $key, $value, ?int $ttlSeconds = null): void;
    public function getOrNull(string $key);
    public function delete(string $key): void;
}
