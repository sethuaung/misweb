<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        $this->mapReportRoutes();
        $this->mapPaymentRoutes();
        $this->mapCustomerRoutes();
        $this->mapSupplyRoutes();
        $this->mapAccountingRoutes();
        $this->mapSaleRoutes();
        $this->loanPaymentRoutes();
        $this->mapRepaymentRoutes();
        $this->mapApiMRoutes();
        $this->mapErpRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }
    protected function mapErpRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/erp.php'));
    }



    protected function mapReportRoutes()
    {
        Route::prefix('report')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/report.php'));
    }


    protected function mapCustomerRoutes()
    {
        Route::prefix('report')// product report
        ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/customer.php'));
    }

    protected function mapSupplyRoutes()
    {
        Route::prefix('report')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/supply.php'));
    }


    protected function mapAccountingRoutes()
    {
        Route::prefix('report')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/accounting.php'));
    }

    protected function mapPaymentRoutes()
    {
        Route::namespace('App\Http\Controllers\Admin')
            ->group(base_path('routes/payment.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapApiMRoutes()
    {
        Route::prefix('api_m')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_m.php'));
    }
    protected function mapSaleRoutes()
    {
        Route::prefix('report')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/sale.php'));
    }

     protected function loanPaymentRoutes()
    {
        Route::prefix('report')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/loan_payment.php'));
    }

     protected function mapRepaymentRoutes()
    {
        Route::prefix('report')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/repayment.php'));
    }
}
