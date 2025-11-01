<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\StackItemRepositoryInterface;
use App\Contracts\Services\StackServiceInterface;
use App\Exceptions\EmptyStackException;
use Illuminate\Support\Facades\DB;

class StackService implements StackServiceInterface
{
    public function __construct(
        private readonly StackItemRepositoryInterface $repo
    ) {}

    /**
     * Push a new value onto the stack.
     *
     * @param  string  $value
     * @return int  The ID of the created record.
     */
    public function push(string $value): int
    {
        return $this->repo->create($value)->id;
    }

    /**
     * Pop the top value or return null if the stack is empty.
     *
     * @return string|null
     */
    public function popOrNull(): ?string
    {
        $payload = null;

        DB::transaction(function () use (&$payload): void {
            $top = $this->repo->findTopForUpdate();
            if ($top !== null) {
                $payload = (string) $top->payload;
                $this->repo->deleteById($top->id);
            }
        });

        return $payload;
    }

    /**
     * Pop the top value or throw an exception if empty.
     *
     * @return string
     *
     * @throws EmptyStackException
     */
    public function pop(): string
    {
        $value = $this->popOrNull();

        if ($value === null) {
            throw new EmptyStackException('Stack is empty.');
        }

        return $value;
    }
}
