<?php
use think\facade\Route;
Route::get('/login', 'Login/index');
Route::get('/logout', 'Login/logout');
Route::get('/codeimg/<id>', 'Login/captcha');
//Route::post('/login/verify/', 'Login/checkUser');
//Route::get('/clearcache', 'Login/clearCache');