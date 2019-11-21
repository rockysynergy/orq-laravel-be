<?php

namespace Orq\Laravel\Starter;

use Illuminate\Support\Facades\Route;

class OrqStarter
{
        /**
     * Generates login routes
     */
    public static function apiLoginRoutes(): void
    {
        Route::namespace("\\Orq\\Laravel\\Starter\\Controllers")->group(function () {
            Route::post('wxlogin', 'AuthController@wxlogin');
        });
    }

    /**
     * Generates login routes
     */
    public static function webLoginRoutes(): void
    {
        Route::redirect('/', '/login', 301);
        Route::namespace("\\Orq\\Laravel\\Starter\\Controllers")->group(function () {
            Route::get('/login', 'LoginController@showLoginForm')->name('login');
            Route::post('/login', 'LoginController@login');
            Route::post('/logout', 'LoginController@logout')->name('logout');
        });
    }

    /**
     * Generates login routes
     */
    public static function webFileRoutes(): void
    {
        Route::namespace("\\Orq\\Laravel\\Starter\\Controllers")->group(function () {
            Route::post('/upload','FileController@uploadFile');
            Route::get('/list','FileController@listFiles');
        });
    }

    /**
     * Generates web routes for this package
     */
    public static function webAdminRoutes(): void
    {
        Route::namespace("\\Orq\\Laravel\\Starter\\Controllers\\Admin")->group(function () {
                // 后台管理员
               Route::get('/admin/index', 'AdminController@index')->name('AdminIndex');
               Route::get('/admin/new', 'AdminController@new')->name('AdminNew');
               Route::get('/admin/edit', 'AdminController@edit')->name('AdminEdit');
               Route::post('/admin/save', 'AdminController@save')->name('AdminSave');
               Route::post('/admin/deactivate', 'AdminController@deactivate')->name('AdminDeactivate');
               Route::post('/admin/restore', 'AdminController@restore')->name('AdminRestore');
               Route::post('/admin/update', 'AdminController@update')->name('AdminUpdate');
        });
    }
}
