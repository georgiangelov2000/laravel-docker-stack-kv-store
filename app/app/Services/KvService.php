<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\KvRepositoryInterface;
use App\Contracts\Services\KvServiceInterface;

class KvService implements KvServiceInterface
{
    public function __construct(
        private readonly KvRepositoryInterface $repo
    ) {}

    /**
     * Set or update a key-value pair.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int|null  $ttlSeconds
     * @return void
     */
    public function set(string $key, mixed $value, ?int $ttlSeconds = null): void
    {
        $this->repo->upsert($key, $value, $ttlSeconds);
    }

    /**
     * Get a value by key or return null if it does not exist or expired.
     *
     * @param  string  $key
     * @return mixed|null
     */
    public function getOrNull(string $key): mixed
    {
        $item = $this->repo->findValid($key);
        return $item?->v ?? null;
    }

    /**
     * Delete a key.
     *
     * @param  string  $key
     * @return void
     */
    public function delete(string $key): void
    {
        $this->repo->delete($key);
    }
}
