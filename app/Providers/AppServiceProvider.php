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
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DepartmentInterface','App\Http\Controllers\Api\Version_1\Repositories\Hrm\Master\DepartmentRepository');
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DesignationInterface','App\Http\Controllers\Api\Version_1\Repositories\Hrm\Master\DesignationRepository');
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\HrTypeInterface','App\Http\Controllers\Api\Version_1\Repositories\Hrm\Master\HrTypeRepository');
        $this->app->bind('App\Http\Controllers\Api\Version_1\Interface\Hrm\Transaction\ResourceInterface','App\Http\Controllers\Api\Version_1\Repositories\Hrm\Transaction\ResourceRepository');


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
