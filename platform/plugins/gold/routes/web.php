<?php

Route::group(['namespace' => 'Botble\Gold\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::get('gold-price', [
            'as'   => 'gold-price',
            'uses' => 'GoldController@UpdateProductPriceNew',
        ]);

        Route::group(['prefix' => 'gold', 'as' => 'gold.'], function () {
            Route::resource('', 'GoldController')->parameters(['' => 'gold']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'GoldController@deletes',
                'permission' => 'gold.destroy',
            ]);
        });
    });

});
