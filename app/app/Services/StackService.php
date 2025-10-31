<?php
declare(strict_types=1);

namespace App\Services;
use App\Contracts\Repositories\StackItemRepositoryInterface;
use App\Contracts\Services\StackServiceInterface;
use App\Exceptions\EmptyStackException;
use Illuminate\Support\Facades\DB;

class StackService implements StackServiceInterface
{
    public function __construct(private readonly StackItemRepositoryInterface $repo) {}

    public function push(string $stack, $value): int
    {
        return $this->repo->create($stack, $value)->id;
    }

    public function popOrNull(string $stack)
    {
        $payload = null;

        DB::transaction(function () use ($stack, &$payload) {
            $top = $this->repo->findTopForUpdate($stack);
            if ($top) {
                $payload = $top->payload;
                $this->repo->deleteById($top->id);
            }
        }, 3);

        return $payload;
    }

    public function pop(string $stack)
    {
        $value = $this->popOrNull($stack);
        if ($value === null) {
            throw new EmptyStackException("Stack '{$stack}' is empty.");
        }
        return $value;
    }

    public function size(string $stack): int
    {
        return $this->repo->countByStack($stack);
    }
}
