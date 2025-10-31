<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\KvServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\KvSetRequest;
use App\Http\Requests\KvDeleteRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class KvController extends Controller
{
    public function __construct(private readonly KvServiceInterface $service) {}

    // POST /api/v1/kv/set
    public function set(KvSetRequest $request): JsonResponse
    {
        $this->service->set(
            $request->input('key'),
            $request->input('value'),
            $request->input('ttl')
        );

        return response()->json(['ok' => true], Response::HTTP_CREATED);
    }

    // POST /api/v1/kv/get
    public function get(string $key): JsonResponse
    {
        $value = $this->service->getOrNull($key);
        return response()->json(['ok' => true, 'value' => $value]);
    }


    // DELETE /api/v1/kv/delete
    public function delete(KvDeleteRequest $request): JsonResponse
    {
        $this->service->delete($request->input('key'));
        return response()->json(['ok' => true]);
    }
}
