<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Laravel\Cashier\Cashier;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::calculateTaxes();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Cashier::useSubscriptionModel(Subscription::class);
        Cashier::useSubscriptionItemModel(SubscriptionItem::class);
        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(1)->by($request->user()?->id ?: $request->ip());
        // });
    }
}
