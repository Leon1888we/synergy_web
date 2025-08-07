php use App\Http\Controllers\BookController; use App\Http\Controllers\RentalController;

Auth::routes();

Route::middleware('auth')->group(function () { // Пользовательские маршруты Route::get('/', [BookController::class, 'index'])->name('home'); Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show'); Route::post('/books/{book}/rent', [RentalController::class, 'rent'])->name('books.rent');

// Административные маршруты
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::resource('books', AdminBookController::class);
    Route::get('rentals', [AdminRentalController::class, 'index'])->name('admin.rentals.index');
    Route::post('rentals/{rental}/remind', [AdminRentalController::class, 'sendReminder'])->name('admin.rentals.remind');
});

});