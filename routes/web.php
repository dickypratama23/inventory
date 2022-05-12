<?php

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
    return redirect()->route('login');
});

Route::get('/tutupan', 'HitungUlangController@tutupan')->name('tutupan');

Route::get('/hitung', 'HitungUlangController@hitung_ulang')->name('hitung');
Route::get('/begbal', 'HitungUlangController@begbal')->name('begbal');

Route::get('/updated-activity', 'TelegramBotController@updatedActivity');
Route::get('/send-message', 'TelegramBotController@sendMessage');

Route::get('/send_ins/{id}', 'TelegramBotController@telegram_ins')->name('send_ins');
Route::get('/send_out/{id}', 'TelegramBotController@telegram_out')->name('send_out');

Route::get('/login', 'LoginController@index')->name('login');
Route::get('/login_new', 'LoginController@login_new');
Route::post('/login', 'LoginController@login')->name('login');
Route::post('/logins', 'LoginController@logins');
Route::get('/logout', 'LoginController@logout')->name('logout');
Route::get('/forgot', 'LoginController@forgot')->name('forgot');
Route::post('/otp', 'LoginController@otp')->name('otp');
Route::post('/otps', 'LoginController@otps')->name('otps');

Route::get('/ttd', 'LoginController@ttd')->name('ttd');
Route::post('/ttd/{id}', 'LoginController@ttd_reg')->name('ttd.reg');

Route::get('/apis/barang', 'APIController@barang');
Route::get('/apis/department', 'APIController@department');
Route::get('/apis/pinjam', 'APIController@pinjam');

Route::get('/hitungulang', 'TransInController@hitung_ulang')->name('hitung_ulang');
Route::get('/browser', 'LoginController@browser_400')->name('browser_400');

Route::get('/ListSign', 'SignController@ListSign')->name('ListSign');
Route::get('/ListSign/{id}', 'SignController@Sign')->name('Sign');
Route::post('/signed/{id}', 'SignController@Signed')->name('Signed');

Route::get('/kirimemail', 'TempController@index');

Route::get('/excel_pengeluaran', 'HomeController@excel_pengeluaran');



///////////////////////////////
// MANAGEMENT
///////////////////////////////

Route::get('/managementExport', 'KategoriController@export_excel'); //export excel

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', 'KategoriController@index');
    Route::post('/', 'KategoriController@save');
    Route::get('/{id}', 'KategoriController@edit');
    Route::put('/{id}', 'KategoriController@update');
    Route::delete('/{id}', 'KategoriController@destroy');
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', 'BarangController@index');
    Route::post('/', 'BarangController@save');
    Route::get('/{id}', 'BarangController@edit');
    Route::put('/{id}', 'BarangController@update');
    Route::delete('/{id}', 'BarangController@destroy');
});

Route::group(['prefix' => 'CAD'], function () {
    Route::get('/', 'BarangController@cad_index');
    Route::post('/', 'BarangController@cad_save');
    Route::get('/{id}', 'BarangController@cad_edit');
    Route::put('/{id}', 'BarangController@cad_update');
    Route::delete('/{id}', 'BarangController@cad_destroy');
    Route::get('/RETURN/{id}', 'BarangController@cad_return')->name('cad.return');

    Route::get('/apis/barang', 'APIController@barang_cad');
});

Route::group(['prefix' => 'Assembly'], function () {
    Route::get('/', 'BarangController@assembly');
});

///////////////////////////////

///////////////////////////////
// TRANSAKSI
///////////////////////////////

Route::group(['prefix' => 'transin'], function () {
    Route::get('/export_excel', 'TransInController@export_excel'); //export excel
    Route::get('/filter', 'TransInController@filter')->name('transin.filter'); //filter

    Route::get('/', 'TransInController@index')->name('transin.index');//REPORT
    Route::get('/new', 'TransInController@create')->name('transin.create');
    Route::post('/', 'TransInController@save')->name('transin.store');
    Route::get('/{id}', 'TransInController@add')->name('transin.add');
    Route::put('/{id}', 'TransInController@update')->name('transin.update');
    Route::get('/{id}/delete', 'TransInController@deleteBarang')->name('transin.delete_barang');
    Route::post('/{id}/selesai', 'TransInController@selesai')->name('transin.selesai');
    Route::get('/{id}/print', 'TransInController@generateTransIn')->name('transin.print');

    Route::post('/upload', 'TransInController@proses_upload')->name('transin.upload');

    Route::get('/{id}/sign', 'TransInController@sign')->name('transin.sign');
    Route::post('/{id}/signOK', 'TransInController@signOK')->name('transin.signOK');

    Route::get('/apis/department', 'APIController@department');
    Route::get('/apis/barang', 'APIController@barang');
});

Route::group(['prefix' => 'transout'], function () {
    Route::get('/export_excel', 'TransOutController@export_excel'); //export excel
    Route::get('/filter', 'TransOutController@filter')->name('transout.filter'); //filter
    Route::get('/assembly/{id}', 'TransOutController@assembly')->name('transout.assembly');
    Route::get('/gl/{id}', 'TransOutController@gl')->name('transout.gl');

    Route::get('/', 'TransOutController@index')->name('transout.index');//REPORT
    Route::get('/new', 'TransOutController@create')->name('transout.create');
    Route::post('/', 'TransOutController@save')->name('transout.store');
    Route::get('/{id}', 'TransOutController@add')->name('transout.add');
    Route::put('/{id}', 'TransOutController@update')->name('transout.update');
    Route::get('/{id}/delete', 'TransOutController@deleteBarang')->name('transout.delete_barang');
    Route::post('/{id}/selesai', 'TransOutController@selesai')->name('transout.selesai');
    Route::get('/{id}/print', 'TransOutController@generateTransOut')->name('transout.print');

    Route::post('/upload', 'TransOutController@proses_upload')->name('transout.upload');

    Route::get('/{id}/sign', 'TransOutController@sign')->name('transout.sign');
    Route::post('/{id}/signOK', 'TransOutController@signOK')->name('transout.signOK');

    Route::get('/apis/department', 'APIController@department');
    Route::get('/apis/barang', 'APIController@barang');
    Route::get('/apis/karyawan', 'APIController@karyawan');
});

Route::group(['prefix' => 'peminjaman'], function () {
    Route::get('/export_excel', 'Peminjaman@export_excel'); //export excel

    Route::get('/', 'Peminjaman@index')->name('peminjaman.index'); //REPORT
    Route::get('/pnjm', 'Peminjaman@bigData')->name('peminjaman.bigData'); //REPORT
    Route::get('/new', 'Peminjaman@create')->name('peminjaman.create');
    Route::post('/', 'Peminjaman@save')->name('peminjaman.store');
    Route::get('/{id}', 'Peminjaman@add')->name('peminjaman.add');
    Route::put('/{id}', 'Peminjaman@update')->name('peminjaman.update');
    Route::get('/{id}/delete', 'Peminjaman@deleteBarang')->name('peminjaman.delete_barang');
    Route::post('/{id}/selesai', 'Peminjaman@selesai')->name('peminjaman.selesai');
    Route::get('/{id}/print', 'Peminjaman@generatePeminjaman')->name('peminjaman.print');

    Route::post('/upload', 'Peminjaman@proses_upload')->name('peminjaman.upload');

    Route::get('/{id}/sign', 'Peminjaman@sign')->name('peminjaman.sign');
    Route::post('/{id}/signOK', 'Peminjaman@signOK')->name('peminjaman.signOK');

    Route::get('/apis/department', 'APIController@department');
    Route::get('/apis/barang', 'APIController@barang_cad');
    Route::get('/apis/karyawan', 'APIController@karyawan');
});

Route::group(['prefix' => 'GO'], function () {
    Route::get('/', 'GOController@index')->name('go.index');
    Route::post('/', 'GOController@save')->name('go.store');
});

///////////////////////////////////////
////////    STOP
///////////////////////////////////////
Route::group(['prefix' => 'alokasi'], function () {
    Route::get('/export_excel', 'AlokasiController@export_excel'); //export excel

    Route::get('/', 'AlokasiController@index')->name('alokasi.index');//REPORT
    Route::get('/new', 'AlokasiController@create')->name('alokasi.create');
    Route::post('/', 'AlokasiController@save')->name('alokasi.store');
    Route::get('/{id}', 'AlokasiController@add')->name('alokasi.add');
    Route::put('/{id}', 'AlokasiController@update')->name('alokasi.update');
    Route::get('/{id}/delete', 'AlokasiController@deleteBarang')->name('alokasi.delete_barang');
    Route::post('/{id}/selesai', 'AlokasiController@selesai')->name('alokasi.selesai');
    Route::get('/{id}/print', 'AlokasiController@generateTransOut')->name('alokasi.print');

    Route::get('/{id}/sign', 'AlokasiController@sign')->name('alokasi.sign');
    Route::post('/{id}/signOK', 'AlokasiController@signOK')->name('alokasi.signOK');

    Route::get('/apis/department', 'APIController@department');
    Route::get('/apis/barang', 'APIController@barang');
    Route::get('/apis/karyawan', 'APIController@karyawan');
    Route::get('/apis/Opr', 'APIController@Opr');
});

///////////////////////////////////////
////////    STOP
///////////////////////////////////////

Route::group(['prefix' => 'opr'], function () {
    Route::get('/', 'OPRController@index')->name('opr.index');//REPORT
    Route::get('/new', 'OPRController@create')->name('opr.create');
    Route::post('/', 'OPRController@save')->name('opr.store');
    Route::get('/{id}', 'OPRController@add')->name('opr.add');
    Route::put('/{id}', 'OPRController@update')->name('opr.update');
    Route::get('/{id}/delete', 'OPRController@deleteBarang')->name('opr.delete_barang');
    Route::post('/{id}/selesai', 'OPRController@selesai')->name('opr.selesai');
    Route::get('/{id}/print', 'OPRController@generateTransOut')->name('opr.print');

    Route::get('/{id}/sign', 'OPRController@sign')->name('opr.sign');
    Route::post('/{id}/signOK', 'OPRController@signOK')->name('opr.signOK');

    Route::get('/apis/department', 'APIController@department');
    Route::get('/apis/barang', 'APIController@barang');
    Route::get('/apis/karyawan', 'APIController@karyawan');
    Route::get('/apis/Opr', 'APIController@Opr');
});

///////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////

Route::group(['prefix' => 'bap'], function () {
    Route::get('/', 'BapController@index')->name('bap.report'); //REPORT
    Route::get('/new', 'BapController@create')->name('bap.create');
    Route::post('/', 'BapController@save')->name('bap.store');
    Route::get('/{id}', 'BapController@add')->name('bap.add');
    Route::put('/{id}', 'BapController@update')->name('bap.update');
    Route::get('/{id}/delete', 'BapController@deleteBarang')->name('bap.delete_barang');
    Route::post('/{id}/selesai', 'BapController@selesai')->name('bap.selesai');
    Route::get('/{id}/print', 'BapController@generateBap')->name('bap.print');
    Route::get('/{id}/detail', 'BapController@detail')->name('bap.detail');

    Route::post('/upload', 'BapController@proses_upload')->name('bap.upload');

    Route::get('/apis/department', 'APIController@department');
    Route::get('/apis/barang', 'APIController@barang');
    Route::get('/apis/karyawan', 'APIController@karyawan');
    Route::get('/apis/Opr', 'APIController@Opr');
});

///////////////////////////////

///////////////////////////////
// UNTUK OPR
///////////////////////////////

Route::group(['prefix' => 'OPR'], function () {
    Route::get('/BTBTOKO', 'TujuanTokoController@btbtoko')->name('btbtoko');
    Route::post('/', 'TujuanTokoController@btbtokosave')->name('btbtoko.store');
    Route::get('/{id}', 'TujuanTokoController@btbtokoadd')->name('btbtoko.add');
    Route::put('/{id}', 'TujuanTokoController@btbtokoupdate')->name('btbtoko.update');
    Route::get('/{id}/delete', 'TujuanTokoController@btbtokodeleteBarang')->name('btbtoko.delete_barang');


    Route::get('/BTBOPR', 'TujuanTokoController@btbopr')->name('btbopr');

    Route::get('/apis/department', 'APIController@department');
    Route::get('/apis/barang', 'APIController@barang');
    Route::get('/apis/karyawan', 'APIController@karyawan');
});

///////////////////////////////

///////////////////////////////
// SERVICE
///////////////////////////////

Route::group(['prefix' => 'service'], function () {
    Route::get('/serviceExport', 'ServiceController@serviceExport'); //export excel
    Route::get('/service/export_excel', 'ServiceController@export_excel'); //export excel

    Route::get('/report_service2', 'ServiceController@report')->name('service.report'); //REPORT
    Route::get('/report_service', 'ServiceController@report2')->name('service.report2'); //REPORT2
    Route::post('/report_service2/filter', 'ServiceController@report2_filter')->name('service.report2.filter');//REPORT2

    Route::get('/', 'ServiceController@index')->name('service.index');
    Route::get('/list', 'ServiceController@list')->name('service.list');
    Route::get('/terima/{id}', 'ServiceController@terima')->name('service.terima');
    Route::get('/ambil', 'ServiceController@ambil')->name('service.ambil');
    Route::get('/ho_list', 'ServiceController@ho_list')->name('service.ho_list');
    Route::post('/note', 'ServiceController@note')->name('service.note');
    Route::get('/{id}/print', 'ServiceController@print')->name('service.print');

    Route::post('/upload', 'ServiceController@proses_upload')->name('service.upload');

    Route::get('/{id}', 'ServiceController@save')->name('service.store');
    Route::get('/service/{id}/{kode}', 'ServiceController@service')->name('service.add');
    Route::put('/{id}', 'ServiceController@update')->name('service.update');
    Route::get('/{id}/delete', 'ServiceController@deleteBarang')->name('service.delete_barang');
    Route::post('/{id}/{kode}/selesai', 'ServiceController@selesai')->name('service.selesai');
    Route::post('/ambil/selesai', 'ServiceController@ambil_selesai');
    Route::get('/generate/docno', 'ServiceController@generateDocno')->name('generateDocno');
    Route::get('/docno/detail', 'ServiceController@docnoDetail')->name('service.ho.detail');
    Route::get('/docno/cetak', 'ServiceController@docnoCetak')->name('service.ho.cetak');

    Route::get('/{id}/sign', 'ServiceController@sign')->name('service.sign');
    Route::post('/{id}/signOK', 'ServiceController@signOK')->name('service.signOK');

    Route::get('/ho/{id}', 'ServiceController@ho')->name('service.ho');

    Route::get('/service/{id}/apis/barang', 'APIController@spare_part');
    Route::get('apis/karyawan', 'APIController@karyawan');
});

///////////////////////////////

///////////////////////////////
// APPROVE
///////////////////////////////

Route::group(['prefix' => 'approval'], function () {
    Route::get('/', 'ApprovalController@approval_out')->name('approval.out');
    Route::get('/out/{id}', 'ApprovalController@approve_out')->name('approve.out');
    Route::get('/rej/{id}', 'ApprovalController@reject_out')->name('reject.out');

    Route::get('/bap/{id}', 'ApprovalController@approve_bap')->name('approve.bap');

    Route::get('/lent/out/{id}', 'ApprovalController@approve_lent')->name('approve.lent');
    Route::get('/lent/rej/{id}', 'ApprovalController@reject_lent')->name('reject.lent');

    Route::get('/service/out/{id}/{sid}/{bi}', 'ApprovalController@approve_service')->name('approve.service');
    Route::get('/service/rej/{id}/{sid}/{bi}', 'ApprovalController@reject_service')->name('reject.service');
});

///////////////////////////////

Route::get('/lppExport', 'LPPController@lppExport'); //export excel

///////////////////////////////
// LPP
///////////////////////////////

Route::group(['prefix' => 'lpp'], function () {
    Route::get('/', 'LPPController@index')->name('lpp.index');
    Route::get('/alokasi', 'LPPController@alokasi')->name('lpp.alokasi');
});

///////////////////////////////

///////////////////////////////
// PP
///////////////////////////////

Route::group(['prefix' => 'pp'], function () {
    Route::get('/', 'PermintaanController@index')->name('lpp.index');
    Route::get('/baru', 'PermintaanController@index_baru')->name('lpp.index');
    Route::post('/exp/baru', 'PermintaanController@expBaru')->name('exp.baru');
    Route::post('/baru/proses', 'PermintaanController@proses')->name('lpp.baru.proses');
    Route::get('/so/acc/{no_pp}/{id_barang}', 'PermintaanController@so')->name('lpp.so.acc');

    

    Route::get('/detail/{no_pp}', 'PermintaanController@pp_detail')->name('lpp.pp_detail');
    Route::get('/buat', 'PermintaanController@buat')->name('lpp.buat');
    Route::get('/buats/{no_pp}', 'PermintaanController@buat2')->name('lpp.buat2');

    Route::post('/update', 'PermintaanController@update')->name('pp.update');
    Route::post('/', 'PermintaanController@save')->name('pp.store');
    Route::get('/{no_pp}/{id_barang}/delete', 'PermintaanController@deleteBarang')->name('pp.delete_barang');
    Route::post('/{no_pp}/selesai', 'PermintaanController@selesai')->name('pp.selesai');

    Route::get('/histori/penerimaan', 'PermintaanController@histori_penerimaan')->name('pp.histori.penerimaan');

    Route::get('/apis/barang', 'APIController@barang');
    Route::get('/buats/apis/barang', 'APIController@barang');


    Route::get('/permintaan', 'LPPController@permintaan')->name('lpp.permintaan');
    Route::get('/permintaan/proses/{tipe}', 'LPPController@permintaan_proses')->name('lpp.permintaan.proses');
    


});

///////////////////////////////

///////////////////////////////
// RESUME
///////////////////////////////

Route::group(['prefix' => 'resume'], function () {
    Route::get('/', 'ResumeController@index')->name('resume.index');
    Route::get('/service_tutupan', 'ResumeController@service_tutupan')->name('resume.service_tutupan');
});

///////////////////////////////

///////////////////////////////
// STOCK OPR
///////////////////////////////

Route::group(['prefix' => 'lppOpr'], function () {
    Route::get('/hitung', 'OPRController@hitung')->name('opr.hitung');
});

///////////////////////////////

///////////////////////////////
// PAGE UNTUK TES AJA
///////////////////////////////

Route::get('/tespage', function () {
    return view('tespage');
});


///////////////////////////////

Route::get('/histori', 'HistoriController@histori')->name('histori'); //HISTORI
Route::post('/histori', 'HistoriController@histori')->name('histori_filter'); //HISTORI

//UNTUK GL ACC
Route::get('/GL/ACC', 'ResumeController@gl_acc')->name('gl.acc');
Route::get('/REG/PROCESS/{id}', 'ResumeController@gl_process')->name('gl.process');

