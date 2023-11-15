Route::get('/user',[UserController::class,'list']);
Route::get('/user/{id}',[UserController::class,'profile']);
Route::get('/user/edit/{id}',[UserController::class,'edit']);
Route::post('/user/edit/{id}',[UserController::class,'update'])
    ->name('user.update');

Route::get('/user/delete/{id}',
    [UserController::class,'delete']);
Route::get('/user/remove/{id}',
    [UserController::class,'remove']);