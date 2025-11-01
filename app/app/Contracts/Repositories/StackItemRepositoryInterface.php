<?php
declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\StackItem;

interface StackItemRepositoryInterface
{
    public function create(string $payload): StackItem;

    /** Lock newest by stack (LIFO) for safe pop; returns null if empty */
    public function findTopForUpdate(): ?StackItem;

    public function deleteById(int $id): void;

}
