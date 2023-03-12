<?php

use App\Http\Controllers\API\EnterpriseController;
use App\Http\Controllers\API\PartnerAccountController;
use App\Http\Controllers\API\PartnerInviteController;
use App\Http\Controllers\API\PartnerLeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('partners/invite', [PartnerInviteController::class, 'recvInvite']);
Route::post('partners/accept', [PartnerInviteController::class, 'acceptInvite']);
Route::post('partners/{code}/leads', [PartnerLeadController::class, 'recvLead']);
Route::post('partners/{code}/leads/{hash}/activity', [PartnerLeadController::class, 'recvLeadActivity']);
Route::get('partners/{code}/leads/{hash}', [PartnerLeadController::class, 'getLead']);
Route::get('partners/{code}/leads/{hash}/disconnect', [PartnerLeadController::class, 'disconnectLead']);
Route::get('partners/{code}/leads/{hash}/sold', [PartnerLeadController::class, 'soldLead']);

Route::get('partners/{code}/accounts', [PartnerAccountController::class, 'getAccounts']);
Route::get('partners/{code}/invoices', [PartnerAccountController::class, 'getInvoices']);
Route::post('partners/{code}/commission/request', [PartnerAccountController::class, 'requestCommission']);
Route::get('partners/{code}/commissions', [PartnerAccountController::class, 'getCommissions']);
Route::get('partners/{code}/commissions/{id}', [PartnerAccountController::class, 'getCommission']);

Route::get('enterprise/usage', [EnterpriseController::class, 'getUsage']);
