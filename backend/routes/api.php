<?php

use App\Http\Controllers\Api\AttemptController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::post('/students', [
    StudentController::class,
    'store'
]);

Route::get('/exams', [
    ExamController::class,
    'index'
]);

Route::get('/exams/{exam}', [
    ExamController::class,
    'show'
]);

Route::post('/attempts', [
    AttemptController::class,
    'store'
]);

Route::get('/attempts/{attempt}', [
    AttemptController::class,
    'show'
]);

Route::post('/attempts/{attempt}/answers', [
    AttemptController::class,
    'answer'
]);

Route::post('/attempts/{attempt}/flag', [
    AttemptController::class,
    'flag'
]);

Route::post('/attempts/{attempt}/submit', [
    AttemptController::class,
    'submit'
]);

Route::get('/attempts/{attempt}/result', [
    AttemptController::class,
    'result'
]);
