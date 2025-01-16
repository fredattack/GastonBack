<?php

namespace App\Providers;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use URL;
use Vite;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configure();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        DB::prohibitDestructiveCommands( $this->app->isProduction() );

        if(!$this->app->isProduction()){
            Model::shouldBeStrict();
        }

        Model::unguard();
        if($this->app->isProduction()){
            URL::forceScheme( 'https');
        }

        Vite::usePrefetchStrategy( 'agressive');
    }


}
