<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\Common\CommonInterface','App\Http\Controllers\Api\Version_1\Repositories\Common\CommonRepository');
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\HRM\Master\DepartmentInterface','App\Http\Controllers\Api\Version_1\Repositories\HRM\Master\DepartmentRepository');
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\HRM\Master\DesignationInterface','App\Http\Controllers\Api\Version_1\Repositories\HRM\Master\DesignationRepository');
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\HRM\Master\HrTypeInterface','App\Http\Controllers\Api\Version_1\Repositories\HRM\Master\HrTypeRepository');
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\HRM\Transaction\ResourceInterface','App\Http\Controllers\Api\Version_1\Repositories\HRM\Transaction\ResourceRepository');


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['person_api_base' => "http://127.0.0.1:8001/api/"]);
    }
}
