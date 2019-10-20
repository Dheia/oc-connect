<?php

use Api\Classes\SchemaManager;
use Codecycler\Business\Models\Organisation;

Route::group([
    'prefix' => '/api/v2',
    'namespace' => 'Codecycler\Connect\Http',
], function () {

    Route::resource('status', 'Status');

    Route::resource('record', 'Record');

    Route::resource('collection', 'Collection');

    Route::get('test', function () {
        $records = Organisation::all();

        $sm = SchemaManager::instance()->getAll();

        dd(base_path());

        return $sm;
    });

});
