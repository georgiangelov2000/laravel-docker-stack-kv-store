<?php
declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Contracts\Services\StackServiceInterface;
use App\Exceptions\EmptyStackException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StackPushRequest;
use App\Http\Resources\StackPopResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StackController extends Controller
{
    public function __construct(private readonly StackServiceInterface $service) {}

    public function push(string $name, StackPushRequest $request): JsonResponse
    {
        $id = $this->service->push($name, $request->input('value'));
        return response()->json(['ok' => true, 'id' => $id], Response::HTTP_CREATED);
    }

    public function pop(string $name): StackPopResource|JsonResponse
    {
        try {
            $value = $this->service->pop($name);
            return new StackPopResource($value);
        } catch (EmptyStackException $e) {
            return response()->json(['ok' => true, 'value' => null], 200);
        }
    }

    public function size(string $name): JsonResponse
    {
        return response()->json(['ok' => true, 'size' => $this->service->size($name)]);
    }
}
