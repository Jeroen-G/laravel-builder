<?php

Route::get('builder', function () {
    return view('builder::index');
});

Route::group(['prefix' => 'builder'], function() {
	Route::get('single', function () {
		$files = glob(base_path('stubs').'/*');
		$func = function ($value) {
			return pathinfo($value, PATHINFO_FILENAME);
		};
		$stubs = array_map($func, $files);

    	return view('builder::single')->with('stubs', $stubs);
	});

	Route::get('resource', function () {
    	return view('builder::resource');
	});

	Route::post('single', 'JeroenG\LaravelBuilder\gui\BuilderController@storeSingle');
	Route::post('resource', 'JeroenG\LaravelBuilder\gui\BuilderController@storeResource');
});