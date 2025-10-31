<?php
declare(strict_types=1);

namespace App\Contracts\Services;

interface StackServiceInterface
{
    /** Returns created row id */
    public function push(string $stack, $value): int;

    /** Pop and return top payload; null if empty */
    public function popOrNull(string $stack);

    /** Strict pop; throws if empty */
    public function pop(string $stack);

    public function size(string $stack): int;
}
