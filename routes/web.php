<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PeminjamanController;

/*
|--------------------------------------------------------------------------
| AUTH - User
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'loginUser'])->name('login');
Route::post('/login', [AuthController::class, 'loginUserPost']);

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registerPost']);


/*|--------------------------------------------------------------------------
| AUTH - admin
|--------------------------------------------------------------------------*/

Route::get('/login-admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
Route::post('/login-admin', [AuthController::class, 'loginAdminPost']);




/*|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------*/

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', [BookController::class, 'home']);


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/


Route::get('/loans', [PeminjamanController::class, 'index'])->name('loans.index');

/*
|--------------------------------------------------------------------------
| BOOK USER
|--------------------------------------------------------------------------
*/

Route::get('/books', [BookController::class, 'index'])->name('books.index');

Route::get('/books/{id}', [BookController::class, 'show']);


/*
|--------------------------------------------------------------------------
| PEMINJAMAN USER
|--------------------------------------------------------------------------
*/

Route::get('/pinjam/{id}', [PeminjamanController::class, 'pinjam'])
    ->middleware('auth');

Route::post('/pinjam', [PeminjamanController::class, 'submitPinjam'])
    ->middleware('auth');

Route::post('/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])
    ->middleware('auth');

Route::get('/admin/konfirmasi/{id}', [PeminjamanController::class, 'konfirmasi'])
    ->middleware(['auth', 'admin']);

Route::get('/peminjaman-saya', [PeminjamanController::class, 'saya'])
    ->middleware('auth');

Route::get('/denda-saya', [BookController::class, 'denda'])
    ->middleware('auth')
    ->name('user.denda');


/*
|--------------------------------------------------------------------------
| Favorite
|--------------------------------------------------------------------------
*/

Route::get('/favorite/{id}', [BookController::class, 'toggleFavorite'])
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| ADMIN - BOOK
|--------------------------------------------------------------------------
*/

Route::get('/admin/books', [BookController::class, 'admin'])
    
    ->middleware(['auth', 'admin']);

Route::post('/admin/books', [BookController::class, 'store'])
    ->middleware(['auth', 'admin']);

Route::get('/admin/books/edit/{id}', [BookController::class, 'edit'])
    ->middleware(['auth', 'admin']);

Route::put('/admin/books/update/{id}', [BookController::class, 'update'])
    ->middleware(['auth', 'admin']);

Route::get('/admin/books/delete/{id}', [BookController::class, 'delete'])
    ->middleware(['auth', 'admin']);


/*
|--------------------------------------------------------------------------
| ADMIN - PEMINJAMAN
|--------------------------------------------------------------------------
*/

Route::get('/admin/peminjaman', [PeminjamanController::class, 'admin'])
    ->name('admin.peminjaman')
    ->middleware(['auth', 'admin']);


Route::get('/admin/peminjaman/export', [PeminjamanController::class, 'exportPdf'])
    ->name('admin.peminjaman.export')
    ->middleware(['auth', 'admin']);    

Route::get('/admin/approve/{id}', [PeminjamanController::class, 'approve'])
    ->name('admin.approve')
    ->middleware(['auth', 'admin']);
    
Route::get('/admin/reject/{id}', [PeminjamanController::class, 'Reject'])
    ->name('admin.reject')
    ->middleware(['auth', 'admin']);



/*
|--------------------------------------------------------------------------
| ADMIN - DENDA
|--------------------------------------------------------------------------
*/
Route::get('/admin/denda', [App\Http\Controllers\DendaController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.denda');

Route::post('/admin/denda', [App\Http\Controllers\DendaController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('admin.denda.store');

Route::get('/admin/denda/lunas/{id}', [App\Http\Controllers\DendaController::class, 'lunas'])
    ->middleware(['auth', 'admin'])
    ->name('admin.denda.lunas');

Route::delete('/admin/denda/{id}', [App\Http\Controllers\DendaController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('admin.denda.destroy');





/*
|--------------------------------------------------------------------------
| ADMIN - kategori
|--------------------------------------------------------------------------
*/

Route::get('/admin/kategori', [AdminController::class, 'kategori']);
Route::post('/admin/kategori', [AdminController::class, 'storeKategori'])
    ->middleware(['auth', 'admin']);


Route::post('/rating/{id}', [BookController::class, 'rating'])
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| ADMIN - USER & MEMBER 
|--------------------------------------------------------------------------
*/

Route::post('/admin/create', [AdminController::class, 'createAdmin']);

Route::get('/admin/users', [AdminController::class, 'users'])
    ->middleware(['auth', 'admin']);

Route::get('/admin/member/{id}', [AdminController::class, 'toggleMember'])
    ->middleware(['auth', 'admin']); 
    
    


    // Notifikasi Admin
Route::post('/admin/notifikasi/baca-semua', function() {
    App\Models\Notifikasi::where('dibaca', false)->update(['dibaca' => true]);
    return back();
})->name('admin.notifikasi.baca-semua')->middleware(['auth', 'admin']);



// Notifikasi User
Route::get('/notifikasi', function() {
    $notifs = \App\Models\Notifikasi::where('user_id', auth()->id())
        ->latest()->paginate(10);
    return view('pages.notifikasi', compact('notifs'));
})->middleware('auth');

Route::get('/notifikasi/baca/{id}', function($id) {
    $notif = \App\Models\Notifikasi::where('user_id', auth()->id())->findOrFail($id);
    $notif->dibaca = true;
    $notif->save();
    return back();
})->middleware('auth');

Route::get('/notifikasi/baca-semua', function() {
    \App\Models\Notifikasi::where('user_id', auth()->id())
        ->where('dibaca', false)
        ->update(['dibaca' => true]);
    return back();
})->middleware('auth');


/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/

Route::view('/contact', 'pages.contact');
Route::view('/about', 'pages.about');


Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware(['auth', 'admin']);