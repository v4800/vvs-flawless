<?php

use App\Providers\AdminDashboardServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    AdminDashboardServiceProvider::class,
];
