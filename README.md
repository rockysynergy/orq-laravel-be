
# Get Started
1. use composesr install the package
2. publish the config, route and views
3. customize them according to your specific needs
4. Look at the config files to update settings(Mostly for WxAppId) in the .env file.
5. Add the Routes as below

```PHP
Route::namespace('Api')->prefix('api')->name('api.')->group(function () {
     \Orq\Laravel\Starter\OrqStarter::webRoutes();
});
```
5. included spatie/laravel-permission in composer.json for use backend admin
6. to use wxlogin, please set up laravel/passport
