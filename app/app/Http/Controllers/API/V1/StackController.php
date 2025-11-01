<?php
declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Contracts\Services\StackServiceInterface;
use App\Exceptions\EmptyStackException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StackPushRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class StackController extends Controller
{
    public function __construct(
        private readonly StackServiceInterface $service
    ) {}

    /**
     * Handle request to push a new value onto the stack.
     *
     * @param  StackPushRequest  $request
     * @return JsonResponse
     */
    public function push(StackPushRequest $request): JsonResponse
    {
        $this->service->push($request->input('value'));

        return ApiResponse::created(
            'Item added to stack successfully',
            ['value' => $request->input('value')]
        );
    }


    /**
     * Handle request to pop the top value from the stack.
     *
     * @return JsonResponse
     */
    public function pop(): JsonResponse
    {
        try {
            $value = $this->service->pop();

            return ApiResponse::success(
                'Item popped from stack successfully',
                ['value' => $value]
            );
        } catch (EmptyStackException $e) {
            return ApiResponse::notFound('Stack is empty');
        }
    }
}
