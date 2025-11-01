<?php
declare(strict_types=1);

namespace App\Contracts\Services;

interface StackServiceInterface
{
    /** Returns created row id */
    public function push(string $value): int;

    /** Pop and return top payload; null if empty */
    public function popOrNull();

    /** Strict pop; throws if empty */
    public function pop();
}
