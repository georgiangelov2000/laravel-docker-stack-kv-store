<?php
declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Contracts\Services\KvServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\KvSetRequest;
use App\Http\Requests\KvDeleteRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class KvController extends Controller
{
    public function __construct(
        private readonly KvServiceInterface $service
    ) {}

    /**
     * Store or update a key-value pair with an optional TTL.
     *
     * @param  KvSetRequest  $request
     * @return JsonResponse
     */
    public function set(KvSetRequest $request): JsonResponse
    {
        $this->service->set(
            $request->input('key'),
            $request->input('value'),
            $request->input('ttl')
        );

        return ApiResponse::created(
            'Key-value pair stored successfully',
            ['key' => $request->input('key')]
        );
    }

    /**
     * Retrieve a value by its key.
     *
     * @param  string  $key
     * @return JsonResponse
     */
    public function get(string $key): JsonResponse
    {
        $value = $this->service->getOrNull($key);

        if ($value === null) {
            return ApiResponse::notFound('Key not found or expired');
        }

        return ApiResponse::success(
            'Value retrieved successfully',
            ['key' => $key, 'value' => $value]
        );
    }

    /**
     * Delete a key-value pair from the store.
     *
     * @param  KvDeleteRequest  $request
     * @return JsonResponse
     */
    public function delete(KvDeleteRequest $request): JsonResponse
    {
        $key = $request->input('key');
        $this->service->delete($key);

        return ApiResponse::success(
            'Key deleted successfully',
            ['key' => $key]
        );
    }
}
