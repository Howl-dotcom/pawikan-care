use App\Http\Controllers\NestController;
Route::post('/nests', [NestController::class,'store']);
