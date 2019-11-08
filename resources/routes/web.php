<?php


//== For file upload
Route::namespace('File')->prefix('file')->group(function () {
    Route::post('/upload','FileController@uploadFile');
    Route::get('/list','FileController@listFiles');
});
