<?php

// Route::get('email', function(){
// 	return new App\Mail\LoginCredencials(App\User::first(), 'asd123');
// });

// DB::listen(function($query){
// 	var_dump($query->sql);
// });

Route::get('/', 'PagesController@home')->name('pages.home');
Route::get('nosotros', 'PagesController@about')->name('pages.about');
Route::get('archivo', 'PagesController@archive')->name('pages.archive');
Route::get('contacto', 'PagesController@contact')->name('pages.contact');


Route::get('blog/{post}', 'PostController@show')->name('posts.show');
Route::get('categorias/{category}', 'CategoriesController@show')->name('categories.show');
Route::get('etiquetas/{tag}', 'TagsController@show')->name('tags.show');



Route::group([

	'prefix' => 'admin', 
	'namespace' => 'admin', 
	'middleware' => 'auth'
], 
	function(){

	Route::get('/', 'AdminController@index')->name('dashboard');

	Route::middleware('role:Admin')
		->put('users/{user}/roles', 'UserRolesController@update')
		->name('admin.users.roles.update');

	Route::middleware('role:Admin')
		->put('users/{user}/permissions', 'UserPermissionsController@update')
		->name('admin.users.permissions.update');

	Route::resource('posts', 'PostController', ['except' => 'show', 'as' => 'admin']);
	Route::resource('users', 'UsersController', ['as' => 'admin']);
	Route::resource('roles', 'RolesController', ['except' => 'show','as' => 'admin']);
	Route::resource('permissions', 'PermissionsController', ['only' => ['index', 'edit', 'update'],'as' => 'admin']);


	// Route::get('posts', 'PostController@index')->name('admin.posts.index');
	// Route::get('posts/create', 'PostController@create')->name('admin.posts.create');
	// Route::post('posts', 'PostController@store')->name('admin.posts.store');
	// Route::get('posts/{post}', 'PostController@edit')->name('admin.posts.edit');
	// Route::put('posts/{post}', 'PostController@update')->name('admin.posts.update');
	// Route::delete('posts/{post}', 'PostController@destroy')->name('admin.posts.destroy');

	Route::post('posts/{post}/photos', 'PhotosController@store')->name('admin.posts.photos.store');
	Route::delete('photos/{photo}', 'PhotosController@destroy')->name('admin.photos.destroy');

	//Rutas de administración
	
});

 // Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');