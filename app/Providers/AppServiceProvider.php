<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\ClientRepositoryInterface::class,
            \App\Repositories\ClientRepository::class
        );

        $this->app->bind(
            \App\Repositories\CategoryRepositoryInterface::class,
            \App\Repositories\CategoryRepository::class
        );

        $this->app->bind(
            \App\Repositories\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\ProductAssignmentRepositoryInterface::class,
            \App\Repositories\ProductAssignmentRepository::class
        );

        $this->app->bind(
            \App\Repositories\ClientSaleRepositoryInterface::class,
            \App\Repositories\ClientSaleRepository::class
        );

        $this->app->bind(
            \App\Repositories\PaymentRepositoryInterface::class,
            \App\Repositories\PaymentRepository::class
        );

        $this->app->bind(
            \App\Repositories\ReportRepositoryInterface::class,
            \App\Repositories\ReportRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant 'Super Admin' role all permissions
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Gate::policy(User::class, UserPolicy::class);
    }
}
