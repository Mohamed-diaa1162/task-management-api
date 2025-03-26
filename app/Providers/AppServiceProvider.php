<?php

namespace App\Providers;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\{Arr, ServiceProvider};
use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Support\Facades\{DB, URL, Vite, Gate,Cache};

final class AppServiceProvider extends ServiceProvider
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
        $this->buildMacros();
        $this->configureUrl();
        $this->configureVite();
        $this->configureModels();
        $this->configureCommands();
    }

     /**
     * Configures the database commands to prohibit destructive commands
     * if the application is in production mode.
     *
     * @return void
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());
    }

    private function configureModels(): void
    {
        Model::unguard();

        Model::shouldBeStrict();
    }

    private function configureUrl(): void
    {
        if (app()->isProduction())  URL::forceScheme('https');
    }

    private function configureVite(): void
    {
        Vite::usePrefetchStrategy('aggressive');
    }

    private function buildMacros(): void
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            return ((object)$this)->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        # Check if the attribute is not an expression and contains a dot (indicating a related model)
                        !($attribute instanceof Expression) && str_contains((string) $attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            # Split the attribute into a relation and related attribute
                            [$relation, $relatedAttribute] = explode('.', (string) $attribute);

                            # Perform a 'LIKE' search on the related model's attribute
                            $query->orWhereHas($relation, function (Builder $query) use ($relatedAttribute, $searchTerm) {
                                $query->where($relatedAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        # Perform a 'LIKE' search on the current model's attribute
                        # also attribute can be an expression
                        fn(Builder $query) => $query->orWhere($attribute, 'LIKE', "%$searchTerm%")
                    );
                }
            });
        });


        Builder::macro('paginateOrAll', function () {
            return ((object)$this)
                ->when(request()->has('orderByColumn'), fn($q) => $q->orderBy(request()->string('orderByColumn'), request()->string('orderByDirection', 'desc')))
                ->when(request()->has('all'), fn($q) => $q->get(), fn($q) => $q->paginate(request()->integer('per_page', 12)));
        });

        Cache::macro('arrayForget', function () {
            foreach (func_get_args() as $key) {
                if (is_array($key)) {
                    foreach ($key as $k) {
                        Cache::forget($k);
                    }
                } else {
                    Cache::forget($key);
                }
            }
        });
    }
}
