<?php
use App\Http\Controllers\Api\BoardController; use App\Http\Controllers\Api\CardController; use App\Http\Controllers\Api\SearchController; use Illuminate\Support\Facades\Route;
Route::middleware(['auth:sanctum','throttle:api'])->group(function(){
 Route::apiResource('boards', BoardController::class);
 Route::post('boards/{board}/cards',[CardController::class,'store']);
 Route::patch('cards/{card}',[CardController::class,'update']);
 Route::patch('cards/{card}/move',[CardController::class,'move']);
 Route::delete('cards/{card}',[CardController::class,'destroy']);
 Route::get('search', SearchController::class);
 Route::get('dashboard', fn(Illuminate\Http\Request $r)=>['total_tasks'=>App\Models\Card::count(),'open_tasks'=>App\Models\Card::where('status','!=','done')->count(),'completed_tasks'=>App\Models\Card::where('status','done')->count(),'overdue_tasks'=>App\Models\Card::whereDate('due_date','<',now())->where('status','!=','done')->count(),'recent_activities'=>App\Models\Activity::latest()->limit(20)->get()]);
});
