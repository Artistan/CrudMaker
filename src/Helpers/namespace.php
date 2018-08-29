<?php

if (!function_exists('app_namespace')) {
    /**
     * get the app namespace for Grafite\CrudMaker
     *
     * @return int|string
     */
    function app_namespace()
    {
        return app('Grafite\CrudMaker\Services\AppService')
            ->getAppNamespace();
    }
}
