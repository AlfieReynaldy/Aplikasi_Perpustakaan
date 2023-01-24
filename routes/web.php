<?php

use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PesanController;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Pemberitahuan;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

//Admin
use App\Http\Controllers\admin\AnggotaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/admin')->group(function(){
    Route::get('/dashboard', function(){
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

//ADMIN
//MasterData
//Data Anggota
Route::get('/anggota', [AnggotaController::class, 'anggota'])->name('admin.anggota');
Route::post('/store-anggota', [AnggotaController::class, 'storeAnggota'])->name('admin.tambah_anggota');   
Route::put('/anggota/update/{id}' , [AnggotaController::class, 'updateAnggota'])->name('admin.update.anggota');
Route::delete('/anggota/delete/{id}', [AnggotaController::class, 'hapusAnggota']);   


//USER
Route::prefix('user')->group(function(){
    Route::get('/dashboard', function(){
        $pemberitahuans = Pemberitahuan::where('status', 'aktif')->get();
        $bukus = Buku::all();
        return view('user.dashboard', compact("pemberitahuans", "bukus"));
    })->name('user.dashboard');

//PEMINJAMAN
    Route::get('/peminjaman', function(){
        $peminjamans = Peminjaman::where('user_id', Auth::user()->id)->get();
        return view('user.peminjaman', compact('peminjamans'));
    })->name('user.peminjaman');

//FORM PEMINJAMAN
    Route::get('/peminjaman/form', function(){
        $bukus = Buku::all();

        return view('user.form_peminjaman', compact('bukus'));
    })->name('user.form_peminjaman');

    Route::post('form_peminjaman', function(Request $request){
        $buku_id = $request->buku_id;
        $bukus = Buku::all();

        return view('user.form_peminjaman', compact("bukus", "buku_id"));
    })->name('user.form_peminjaman_dashboard');

    Route::post('submit_peminjaman', function(Request $request){
        $cek_total_peminjaman = Peminjaman::where('user_id', Auth::user()->id)
        ->where('tgl_pengembalian', null)->count();

        //cuman boleh minjem buku 5
        if ($cek_total_peminjaman >= 5) {
            return redirect()->back()
            ->with("status", "danger")
            ->with("message", "Tidak Bisa Meminjam Buku Lebi Dari 5");
        }

        //gaboleh minjem buku yg sama
        $cek_buku = Peminjaman::where('buku_id', $request->buku_id)
        ->where('user_id', Auth::user()->id)
        ->first();
        if ($cek_buku) {
            return redirect()->back()
            ->with("status", "danger")
            ->with("message", "Tidak Bisa Meminjam Buku Dengan Judul Yang Sama");
        }

        //nambah peminjaman
        $peminjaman = Peminjaman::create($request->all());

        //mengurangi jumlah buku baik & rusak saat dipinjam
        $buku = Buku::where('id', $request->buku_id)->first();
        if ($request->kondisi_buku_saat_dipinjam == 'baik') {
            $buku->update([
                'j_buku_baik'=> $buku->j_buku_baik - 1
            ]);
        }
        if ($request->kondisi_buku_saat_dipinjam == 'rusak') {
            $buku->update([
                'j_buku_rusak'=> $buku->j_buku_rusak - 1
            ]);
        }

        if($peminjaman){
            return redirect()->route("user.peminjaman")
                ->with("status", "success")
                ->with("message", "Berhasil Menambah Data");
        }
        return redirect()->back()
            ->with("status", "danger")
            ->with("message", "Gagal menambah data");
    })->name('user.submit_peminjaman');

//PENGEMBALIAN
    Route::get('/pengembalian', [PengembalianController::class, 'form_pengembalian'])->name('user.form_pengembalian');
    Route::post('submit_pengembalian', [PengembalianController::class, 'submit_pengembalian'])->name('user.submit_pengembalian'); 
    
    Route::get('riwayat-pengembalian', [PengembalianController::class, 'riwayat_pengembalian'])->name('user.pengembalian');

//PESAN
    Route::get('/pesan-masuk', [PesanController::class, 'pesan_masuk'])->name('user.pesan_masuk');
    Route::get('/pesan-terkirim', [PesanController::class, 'pesan_terkirim'])->name('user.pesan_terkirim');
    Route::post('/ubah-status', [PesanController::class, 'ubah_status'])->name('user.ubah_status');
    Route::post('/kirim-pesan', [PesanController::class, 'kirim_pesan'])->name('user.kirim_pesan');
    Route::delete('/hapus-pesan', [PesanController::class, 'hapus_pesan'])->name('user.hapus_pesan');

//PROFILE
    Route::get('/profile', function(){
        return view('user.profil');
    })->name('user.profil');

    Route::put('profile', function(Request $request){
        $id = Auth::user()->id;

        $imageName = time().'.'.$request->foto->extension();

        $request->foto->move(public_path('img'), $imageName);

        $user = User::find(Auth::user()->id)->update($request->all());

        $user2 = User::find($id)->update([
            "password" => Hash::make($request->password),
            "foto" => "/img/" . $imageName
        ]);

        if($user && $user2) {
            return redirect()->back()->with("status", "success")->with('message', 
            'Berhasil mengubah profile');
        }
            return redirect()->back()->with("status", "danger")->with('message', 'Gagal mengubah profile');
    })->name('user.profil.update');
    
});