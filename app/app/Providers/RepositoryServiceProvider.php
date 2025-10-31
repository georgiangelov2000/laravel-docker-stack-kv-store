<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\StackItemRepositoryInterface;
use App\Repositories\Eloquent\EloquentStackItemRepository;
use App\Contracts\Services\StackServiceInterface;
use App\Services\StackService;
use App\Contracts\Repositories\KvRepositoryInterface;
use App\Repositories\Eloquent\EloquentKvRepository;
use App\Contracts\Services\KvServiceInterface;
use App\Services\KvService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StackItemRepositoryInterface::class, EloquentStackItemRepository::class);
        $this->app->bind(StackServiceInterface::class, StackService::class);
        $this->app->bind(KvRepositoryInterface::class, EloquentKvRepository::class);
        $this->app->bind(KvServiceInterface::class, KvService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
