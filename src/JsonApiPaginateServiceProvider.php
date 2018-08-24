<?php

namespace Spatie\JsonApiPaginate;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Spatie\JsonApiPaginate\JeasyuiPaginator;
use Spatie\JsonApiPaginate\JeasyuiSimplePaginator;

class JsonApiPaginateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/json-api-paginate.php' => config_path('json-api-paginate.php'),
            ], 'config');
        }

        $this->registerMacro();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/json-api-paginate.php', 'json-api-paginate');
    }

    protected function registerMacro()
    {
        Builder::macro(config('json-api-paginate.method_name'), function (array $params = []) {

            if (array_has($params, 'paginate')) {
                if (!$params['paginate']) {
                    return $this->get();
                }
            }

            $maxResult = $params["max_result"] ?? config('json-api-paginate.max_result');
            $defaultSize = $params["default_size"] ?? config('json-api-paginate.default_size');
            $showTotal = $params["show_total"] ?? config('json-api-paginate.show_total');
            $pageParameter = config('json-api-paginate.page_parameter');
            $rowParameter = config('json-api-paginate.row_parameter');
            $perPage = (int) request()->input($rowParameter, $defaultSize);

            if ($perPage > $maxResult) {
                $perPage = $maxResult;
            }

            $page = Paginator::resolveCurrentPage($pageParameter);
            $perPage = $perPage ?: $this->model->getPerPage();

            $options = [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageParameter,
            ];

            $createPaginator = function ($showTotal, $page, $perPage, $options) {
                if ($showTotal) {

                    $items = ($total = $this->toBase()->getCountForPagination())
                    ? $this->forPage($page, $perPage)->get(['*'])
                    : $this->model->newCollection();

                    return Container::getInstance()->makeWith(JeasyuiPaginator::class, compact(
                        'items', 'total', 'perPage', 'currentPage', 'options'
                    ));

                } else {

                    $this->skip(($page - 1) * $perPage)->take($perPage + 1);
                    $items = $this->get(['*']);

                    return Container::getInstance()->makeWith(JeasyuiSimplePaginator::class, compact(
                        'items', 'perPage', 'currentPage', 'options'
                    ));

                }

            };

            $paginator = $createPaginator($showTotal, $page, $perPage, $options);

            $paginator->setPageName($pageParameter)
                ->appends(array_except(request()->input(), $pageParameter));

            if (!is_null(config('json-api-paginate.base_url'))) {
                $paginator->setPath(config('json-api-paginate.base_url'));
            }

            return $paginator;

        });
    }
}
