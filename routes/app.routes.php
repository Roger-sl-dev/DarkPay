
<?php
use Illuminate\Session\Middleware\StartSession;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Payments\RefundController;
use App\Http\Controllers\Payments\TransactionController;
use App\Http\Controllers\Product\CategoriaController;
use App\Http\Controllers\Product\CheckoutController;
use App\Http\Controllers\Product\CupomController;
use App\Http\Controllers\Product\OrderBumpController;
use App\Http\Controllers\Product\OrderController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\UpsellController;
use Illuminate\Support\Facades\Route;


Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('produtos', ProductController::class);
Route::apiResource('order', OrderController::class);
Route::apiResource('cupons', CupomController::class);
Route::apiResource('transactions', TransactionController::class);
Route::post('/transactions/{id}/refund', [RefundController::class, 'refund']);
Route::apiResource('checkouts', CheckoutController::class);
Route::apiResource('upsell-offers', UpsellController::class);
Route::apiResource('order-bump-offers', OrderBumpController::class);



Route::post('/register', [AuthController::class, 'register']);

Route::middleware([StartSession::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});






