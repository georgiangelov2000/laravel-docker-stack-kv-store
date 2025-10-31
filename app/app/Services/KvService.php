<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\KvRepositoryInterface;
use App\Contracts\Services\KvServiceInterface;

class KvService implements KvServiceInterface
{
    public function __construct(private readonly KvRepositoryInterface $repo) {}

    public function set(string $key, $value, ?int $ttlSeconds = null): void
    {
        $this->repo->upsert($key, $value, $ttlSeconds);
    }

    public function getOrNull(string $key)
    {
        $item = $this->repo->findValid($key);
        return $item?->v;
    }

    public function delete(string $key): void
    {
        $this->repo->delete($key);
    }
}
