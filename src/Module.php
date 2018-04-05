<?php

namespace AvoRed\Related;

use AvoRed\Ecommerce\Events\ProductAfterSave;
use AvoRed\Related\Http\ViewComposers\RelatedProductComposer;
use AvoRed\Related\Listeners\RelatedProductListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use AvoRed\Banner\Widget\Banner\Widget;
use AvoRed\Framework\Tabs\Tab;

use AvoRed\Framework\Widget\Facade as WidgetFacade;
use AvoRed\Framework\Tabs\Facade as TabFacade;
use AvoRed\Framework\Breadcrumb\Facade as BreadcrumbFacade;

class Module extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();
        //$this->registerWidget();
        $this->registerTab();
        //$this->registerBreadCrumb();
        $this->registerViewComposer();
        $this->registerListener();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Registering AvoRed featured Resource
     * e.g. Route, View, Database  & Translation Path
     *
     * @return void
     */
    protected function registerResources()
    {

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'avored-related');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'avored-related');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register the Product Edit View Composer Class.
     *
     * @return void
     */
    protected function registerViewComposer()
    {
        View::composer('avored-related::related.product.tab', RelatedProductComposer::class);
    }


    /**
     * Register the Product Save Event Listener.
     *
     * @return void
     */
    protected function registerListener()
    {
        Event::listen(ProductAfterSave::class, RelatedProductListener::class);
    }

    /**
     * Register the Product Edit Page Tab.
     *
     * @return void
     */
    protected function registerTab()
    {
        $relatedTab = new Tab();

        $relatedTab->type('product')
            ->label('Related Product')
            ->view('avored-related::related.product.tab');

        TabFacade::add('related-product', $relatedTab);
    }



    /**
     * Register the Widget.
     *
     * @return void
     */
    protected function registerWidget()
    {
        $bannerProduct = new Widget();
        WidgetFacade::make($bannerProduct->identifier(), $bannerProduct);
    }



    /**
     * Register the Admin Breadcrumb.
     *
     * @return void
     */
    protected function registerBreadCrumb()
    {
        BreadcrumbFacade::make('admin.banner.index', function ($breadcrumb) {
                                $breadcrumb->label('Banner')
                                    ->parent('admin.dashboard');
                            });

        BreadcrumbFacade::make('admin.banner.create', function ($breadcrumb) {
                                $breadcrumb->label('Create')
                                    ->parent('admin.dashboard')
                                    ->parent('admin.banner.index');
                            });

        BreadcrumbFacade::make('admin.banner.edit', function ($breadcrumb) {
            $breadcrumb->label('Edit')
                ->parent('admin.dashboard')
                ->parent('admin.banner.index');
        });
    }

}