<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
	return redirect()->route('login');
});


Route::get('/v2/register', function () {
	return view('v2register');
});

Route::get('/home', function () {
	if (session('status')) {
		if (Auth::user()->roles[0]->title == 'Spatial Administrator' || Auth::user()->roles[0]->title == 'Spatial Staff') {
			return redirect()->route('2024.spatial.home')->with('status', session('status'));
		}else{
			return redirect()->route('admin.home')->with('status', session('status'));
		}
	}
	if (Auth::user()->roles[0]->title == 'Spatial Administrator' || Auth::user()->roles[0]->title == 'Spatial Staff') {
		return redirect()->route('2024.spatial.home')->with('status', session('status'));
	}else{
		return redirect()->route('2024.admin.home')->with('status', session('status'));
	}
});


Auth::routes(['register' => true]); // menghidupkan registration

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
	// landing
	Route::get('/', 'HomeController@index')->name('home');
	// Dashboard
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	Route::get('/dashboard/monitoring', 'DashboardController@monitoring')->name('dashboard.monitoring');
	Route::get('/dashboard/map', 'DashboardController@map')->name('dashboard.map');
	Route::get('/dashboard/newmap', 'DashboardController@newmap')->name('dashboard.newmap');

	Route::get('/dashboard/monitoringrealisasi/{periodetahun}', 'DashboardController@monitoringrealisasi')->name('dashboard.monitoringrealisasi');
	Route::get('/monitoringDataRealisasi/{periodetahun}', 'DashboardDataController@monitoringDataRealisasi')->name('monitoringDataRealisasi');

	Route::get('mapDataAll', 'UserMapDashboard@index')->name('mapDataAll');
	Route::get('mapDataByYear/{periodeTahun}', 'UserMapDashboard@ByYears')->name('mapDataByYear');
	Route::get('mapDataById/{id}', 'UserMapDashboard@show')->name('mapDataById');

	//export data lokasi
	Route::get('/getCompaniesByYear/{year}', 'LocationExportController@getCompaniesByYear')->name('getCompaniesByYear');
	Route::get('/getLocationByIjin/{noIjin}', 'LocationExportController@getLocationByIjin')->name('getLocationByIjin');
	Route::get('location/export', 'LocationExportController@index')->name('locationexport');
	Route::get('realisasi/{year}/company', 'LocationExportController@getRealisasiCompany')->name('getRealisasiCompany');

	//data pemetaan
	Route::group(['prefix' => 'map', 'as' => 'map.'], function () {
		Route::get('getAllMap', 'AdminMapController@index')->name('getAllMap');
		Route::get('getAllMapByYears/{periodeTahun}', 'AdminMapController@ByYears')->name('getAllMapByYears');
		Route::get('getLocationData/{id}', 'AdminMapController@index')->name('getLocationData');
		Route::get('getAllMapData/{periodeTahun}', 'AdminMapController@getMapByYears')->name('getAllMapData');
		Route::get('getSingleMarker/{id}', 'AdminMapController@singleMarker')->name('getSingleMarker');
	});

	//dashboard data for admin
	Route::get('monitoringDataByYear/{periodetahun}', 'DashboardDataController@monitoringDataByYear')->name('monitoringDataByYear');

	//dashboard data for verifikator
	Route::get('verifikatorMonitoringDataByYear/{periodetahun}', 'DashboardDataController@verifikatorMonitoringDataByYear')->name('verifikatormonitoringDataByYear');

	//dashboard data for user
	Route::get('usermonitoringDataByYear/{periodeTahun}', 'DashboardDataController@userMonitoringDataByYear')->name('userMonitoringDataByYear');
	Route::get('rekapRiphData', 'DashboardDataController@rekapRiphData')->name('get.rekap.riph');

	//sklReads
	Route::post('sklReads', 'SklReadsController@sklReads')->name('sklReads');

	// Permissions
	Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
	Route::resource('permissions', 'PermissionsController');

	// Roles
	Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
	Route::resource('roles', 'RolesController');

	// Users
	Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
	Route::resource('users', 'UsersController');

	// Audit Logs
	Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
	Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

	Route::get('profile', 'ProfileController@index')->name('profile.show');
	Route::post('profile', 'ProfileController@store')->name('profile.store');
	Route::post('profile/{id}', 'ProfileController@update')->name('profile.update');
	Route::get('profile/pejabat', 'AdminProfileController@index')->name('profile.pejabat');
	Route::post('profile/pejabat/store', 'AdminProfileController@store')->name('profile.pejabat.store');

	//google map api
	Route::get('gmapapi', 'ForeignApiController@edit')->name('gmapapi.edit');
	Route::put('gmapapi/update', 'ForeignApiController@update')->name('gmapapi.update');

	//posts
	Route::put('posts/{post}/restore', 'PostsController@restore')->name('posts.restore');
	Route::resource('posts', 'PostsController');
	Route::get('allblogs', 'PostsController@allblogs')->name('allblogs');
	Route::post('posts/{post}/star', 'StarredPostController@star')->name('posts.star');
	Route::delete('posts/{post}/unstar', 'StarredPostController@unstar')->name('posts.unstar');

	//posts categories
	Route::resource('categories', 'CategoryController');

	//messenger
	Route::get('messenger', 'MessengerController@index')->name('messenger.index');
	Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
	Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
	Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
	Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
	Route::post('messenger/{topic}/update', 'MessengerController@updateTopic')->name('messenger.updateTopic');
	Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
	Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
	Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
	Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');

	//verifikasi
	Route::get('dir_check_b', 'MessengerController@showReply')->name('verifikasi.dir_check_b');
	Route::get('dir_check_c', 'MessengerController@showReply')->name('verifikasi.dir_check_c');

	Route::get('riphAdmin', 'RiphAdminController@index')->name('riphAdmin.index');
	Route::get('riphAdmin/create', 'RiphAdminController@create')->name('riphAdmin.create');
	Route::post('riphAdmin/storefetched', 'RiphAdminController@storefetched')->name('riphAdmin.storefetched');
	Route::post('riphAdmin', 'RiphAdminController@store')->name('riphAdmin.store');
	Route::get('riphAdmin/{riphAdmin}/edit', 'RiphAdminController@edit')->name('riphAdmin.edit');
	Route::put('riphAdmin/{riphAdmin}', 'RiphAdminController@update')->name('riphAdmin.update');
	Route::delete('riphAdmin/{riphAdmin}', 'RiphAdminController@destroy')->name('riphAdmin.destroy');

	//daftar pejabat penandatangan SKL
	Route::get('daftarpejabats', 'PejabatController@index')->name('pejabats');
	Route::get('pejabat/create', 'PejabatController@create')->name('pejabat.create');
	Route::post('pejabat/store', 'PejabatController@store')->name('pejabat.store');
	Route::get('pejabat/{id}/show', 'PejabatController@show')->name('pejabat.show');
	Route::get('pejabat/{id}/edit', 'PejabatController@edit')->name('pejabat.edit');
	Route::put('pejabat/{id}/update', 'PejabatController@update')->name('pejabat.update');
	Route::delete('pejabat/{id}/delete', 'PejabatController@destroy')->name('pejabat.delete');
	Route::put('pejabat/{id}/activate', 'PejabatController@activate')->name('pejabat.activate');

	//daftar varietas
	Route::get('varietas', 'VarietasController@index')->name('varietas');
	Route::get('varietas/create', 'VarietasController@create')->name('varietas.create');
	Route::get('varietas/{id}/edit', 'VarietasController@edit')->name('varietas.edit');
	Route::get('varietas/{id}/show', 'VarietasController@show')->name('varietas.show');
	Route::post('varietas/store', 'VarietasController@store')->name('varietas.store');
	Route::put('varietas/{id}/update', 'VarietasController@update')->name('varietas.update');
	Route::delete('varietas/{id}/delete', 'VarietasController@destroy')->name('varietas.delete');
	Route::patch('varietas/{id}/restore', 'VarietasController@restore')->name('varietas.restore');

	//user task
	Route::group(['prefix' => 'task', 'as' => 'task.'], function () {

		Route::get('pull', 'PullRiphController@index')->name('pull');
		Route::get('getriph', 'PullRiphController@pull')->name('pull.getriph');
		Route::post('pull', 'PullRiphController@store')->name('pull.store');


		Route::get('commitment', 'CommitmentController@index')->name('commitment');
		Route::group(['prefix' => 'commitment', 'as' => 'commitment.'], function () {
			Route::get('{id}/show', 'CommitmentController@show')->name('show');
			Route::delete('{pullriph}', 'CommitmentController@destroy')->name('destroy');

			//pengisian data realisasi
			Route::get('{id}/realisasi', 'CommitmentController@realisasi')->name('realisasi');
			Route::post('{id}/realisasi/storeUserDocs', 'CommitmentController@storeUserDocs')->name('realisasi.storeUserDocs');

			Route::get('{id}/penangkar', 'PenangkarRiphController@mitra')->name('penangkar');
			Route::post('{id}/penangkar/store', 'PenangkarRiphController@store')->name('penangkar.store');


		});
		Route::delete('commitmentmd', 'CommitmentController@massDestroy')->name('commitment.massDestroy');

		//master penangkar
		Route::get('penangkar', 'MasterPenangkarController@index')->name('penangkar');
		Route::group(['prefix' => 'penangkar', 'as' => 'penangkar.'], function () {
			Route::get('create', 'MasterPenangkarController@create')->name('create');
			Route::post('store', 'MasterPenangkarController@store')->name('store');
			Route::get('{id}/edit', 'MasterPenangkarController@edit')->name('edit');
			Route::put('{id}/update', 'MasterPenangkarController@update')->name('update');
			Route::delete('{id}/delete', 'MasterPenangkarController@destroy')->name('delete');
		});

		Route::delete('mitra/{id}/delete', 'PenangkarRiphController@destroy')->name('mitra.delete');

		// daftar pks

		Route::get('pks/{id}/edit', 'PksController@edit')->name('pks.edit');
		Route::put('pks/{id}/update', 'PksController@update')->name('pks.update');

		//daftar anggota
		Route::get('pks/{id}/daftaranggota', 'PksController@anggotas')->name('pks.anggotas');
		// daftar lokasi tanam per anggota
		Route::get('pks/{pksId}/anggota/{anggotaId}/list_lokasi', 'PksController@listLokasi')->name('pks.anggota.listLokasi');
		//page tambah lokasi tanam
		Route::get('pks/{pksId}/anggota/{anggotaId}/add_lokasi', 'PksController@addLokasiTanam')->name('pks.anggota.addLokasiTanam');
		//edit lokasi tanam
		Route::get('pks/{pksId}/anggota/{anggotaId}/lokasi/{id}/edit', 'PksController@editLokasiTanam')->name('pks.anggota.editLokasiTanam');
		Route::get('pks/{pksId}/anggota/{anggotaId}/lokasi/{id}/foto', 'PksController@fotoLokasi')->name('pks.anggota.fotoLokasi');
		Route::delete('deleteFotoTanam/{id}', 'PksController@deleteFotoTanam')->name('deleteFotoTanam');
		Route::delete('deleteFotoProduksi/{id}', 'PksController@deleteFotoProduksi')->name('deleteFotoProduksi');
		Route::delete('deleteLokasiTanam/{id}', 'PksController@deleteLokasiTanam')->name('deleteLokasiTanam');

		Route::post('storeLokasiTanam', 'PksController@storeLokasiTanam')->name('storeLokasiTanam');
		Route::put('updateLokasiTanam/{id}/update', 'PksController@updateLokasiTanam')->name('updateLokasiTanam');
		Route::put('storeRealisasiProduksi/{id}', 'PksController@storeRealisasiProduksi')->name('storeRealisasiProduksi');

		Route::post('upload/dropZoneTanam', 'PksController@dropZoneTanam')->name('dropZoneTanam');
		Route::post('upload/dropZoneProduksi', 'PksController@dropZoneProduksi')->name('dropZoneProduksi');

		//saprodi
		Route::get('pks/{id}/saprodi', 'PksController@saprodi')->name('pks.saprodi');
		Route::post('pks/{id}/saprodi', 'SaprodiController@store')->name('saprodi.store');
		route::get('pks/{pksId}/saprodi/{id}/edit', 'SaprodiController@edit')->name('saprodi.edit');
		route::put('pks/{pksId}/saprodi/{id}', 'SaprodiController@update')->name('saprodi.update');
		route::delete('saprodi/{id}', 'SaprodiController@destroy')->name('saprodi.delete');
		Route::get('saprodi', 'SaprodiController@index')->name('saprodi.index');

		// Route::get('pks/create/{noriph}/{poktan}', 'PksController@create')->name('pks.create');
		// Route::delete('pksmd', 'PksController@massDestroy')->name('pks.massDestroy');

		//realisasi lokasi tanam & produksi
		Route::get('realisasi/lokasi/{lokasiId}', 'LokasiController@show')->name('lokasi.tanam');
		Route::post('realisasi/lokasi/{id}/update', 'LokasiController@update')->name('lokasi.tanam.update');
		Route::put('realisasi/lokasi/{id}/storeTanam', 'LokasiController@storeTanam')->name('lokasi.tanam.store');
		Route::put('realisasi/lokasi/{id}/storeProduksi', 'LokasiController@storeProduksi')->name('lokasi.produksi.store');

		Route::get('pengajuan', 'PengajuanController@index')->name('pengajuan.index');

		//new pengajuan tanam
		Route::get('commitment/{id}/formavt', 'PengajuanController@ajuVerifTanam')->name('commitment.avt');
		Route::get('commitment/{id}/formavt/lokasi', 'PengajuanController@ajuVerifTanam')->name('commitment.avt.lokasi');
		Route::post('commitment/{id}/formavt/store', 'PengajuanController@ajuVerifTanamStore')->name('commitment.avt.store');
		Route::get('commitment/{id}/pengajuan/tanam/show', 'PengajuanController@showAjuTanam')->name('pengajuan.tanam.show');

		//new pengajuan produksi
		Route::get('commitment/{id}/formavp', 'PengajuanController@ajuVerifProduksi')->name('commitment.avp');
		Route::get('commitment/{id}/formavp/lokasi', 'PengajuanController@ajuVerifProduksi')->name('commitment.avp.lokasi');
		Route::post('commitment/{id}/formavp/store', 'PengajuanController@ajuVerifProduksiStore')->name('commitment.avp.store');
		Route::get('commitment/{id}/pengajuan/produksi/show', 'PengajuanController@showAjuProduksi')->name('pengajuan.produksi.show');

		//new pengajuan skl
		Route::get('commitment/{id}/formavskl', 'PengajuanController@ajuVerifSkl')->name('commitment.avskl');
		Route::post('commitment/{id}/formavskl/store', 'PengajuanController@ajuVerifSklStore')->name('commitment.avskl.store');
		Route::get('commitment/{id}/formavskl/lokasi', 'PengajuanController@ajuVerifSkl')->name('commitment.avskl.lokasi');
		Route::get('commitment/{id}/pengajuan/skl/show', 'PengajuanController@showAjuSkl')->name('pengajuan.skl.show');


		Route::get('submission/{id}/show', 'PengajuanController@show')->name('submission.show');
		Route::delete('pengajuan/destroy', 'PengajuanController@massDestroy')->name('pengajuan.massDestroy');

		//daftar seluruh skl yang telah terbit (lama & baru)
		Route::get('skl/arsip', function () {
			return redirect()->route('skl.arsip');
		})->name('skl.arsip');
	});

	//template
	Route::group(['prefix' => 'template', 'as' => 'template.'], function () {
		Route::get('index', 'FileManagementController@index')->name('index');
		Route::get('create', 'FileManagementController@create')->name('create');
		Route::post('store', 'FileManagementController@store')->name('store');
		Route::post('{id}/edit', 'FileManagementController@edit')->name('edit');
		Route::put('{id}/update', 'FileManagementController@update')->name('update');
		Route::get('{id}/download', 'FileManagementController@download')->name('download');
		Route::delete('{id}/delete', 'FileManagementController@destroy')->name('delete');
	});

	Route::get('lokasiTanamByCommitment/{id}', 'DataLokasiTanamController@lokasiTanamByCommitment')->name('lokasiTanamByCommitment');
	Route::get('listLokasi/{id}', 'DataLokasiTanamController@listLokasi')->name('ajutanam.listlokasi');
	Route::get('produksi/listLokasi/{id}', 'DataLokasiTanamController@listLokasiTanamProduksi')->name('ajuproduksi.listlokasi');
});

Route::group(['prefix' => 'verification', 'as' => 'verification.', 'namespace' => 'Verifikator', 'middleware' => ['auth']], function () {

	//verifikasi data lokasi tanam
	Route::get('{noIjin}/lokasitanam', 'LokasiTanamController@index')->name('lokasitanam');

	Route::get('{noIjin}/lokasitanam/{lokasiId}', 'LokasiTanamController@listLokasibyPetani')->name('listLokasibyPetani');
	Route::get('{id}/summary', 'VerifSklController@dataCheck')->name('data.summary');

	//new verifikasi tanam
	Route::get('tanam', 'VerifTanamController@index')->name('tanam');
	Route::group(['prefix' => 'tanam', 'as' => 'tanam.'], function () {
		Route::get('{id}/check', 'VerifTanamController@check')->name('check');
		// Route::get('{noIjin}/daftar_lokasi_tanam', 'LokasiTanamController@daftarTanam')->name('daftarTanam');
		Route::put('{id}/storeCheck', 'VerifTanamController@storeCheck')->name('storeCheck');
		Route::get('{id}/show', 'VerifTanamController@show')->name('show');
		Route::get('{id}/showlocation', 'LokasiTanamController@showLocation')->name('showLocation');
		Route::post('{id}/checkBerkas', 'VerifTanamController@checkBerkas')->name('checkBerkas');
		Route::get('{noIjin}/poktan/{poktan_id}/check', 'VerifTanamController@verifPks')->name('check.pks');
		Route::put('pks/{id}/store', 'VerifTanamController@verifPksStore')->name('check.pks.store');
		Route::put('{id}/checkPksSelesai', 'VerifTanamController@checkPksSelesai')->name('checkPksSelesai');
		// Route::get('{noIjin}/lokasi/{anggota_id}', 'VerifTanamController@lokasicheck')->name('lokasicheck');
	});

	//new verifikasi produksi
	Route::get('produksi', 'VerifProduksiController@index')->name('produksi');
	Route::group(['prefix' => 'produksi', 'as' => 'produksi.'], function () {
		Route::get('{id}/check', 'VerifProduksiController@check')->name('check');
		Route::post('{id}/storeCheck', 'VerifProduksiController@storeCheck')->name('storeCheck');
		Route::get('{id}/show', 'VerifProduksiController@show')->name('show');
		Route::post('{id}/checkBerkas', 'VerifProduksiController@checkBerkas')->name('checkBerkas');
		Route::get('{noIjin}/poktan/{poktan_id}/check', 'VerifProduksiController@verifPks')->name('check.pks');
		Route::put('pks/{id}/store', 'VerifProduksiController@verifPksStore')->name('check.pks.store');
		Route::post('{id}/checkPksSelesai', 'VerifProduksiController@checkPksSelesai')->name('checkPksSelesai');
		Route::get('{id}/showlocation', 'LokasiTanamController@showLocation')->name('showLocation');
		//unused
		Route::put('{id}/store', 'VerifProduksiController@store')->name('store');
	});

	//new verifikasi skl
	Route::get('skl', 'VerifSklController@index')->name('skl');
	Route::group(['prefix' => 'skl', 'as' => 'skl.'], function () {
		Route::get('{id}/check', 'VerifSklController@check')->name('check');
		Route::post('{id}/checkBerkas', 'VerifSklController@checkBerkas')->name('checkBerkas');
		Route::get('{noIjin}/poktan/{poktan_id}/check', 'VerifSklController@verifPks')->name('check.pks');
		Route::put('pks/{id}/store', 'VerifSklController@verifPksStore')->name('check.pks.store');
		Route::post('{id}/checkPksSelesai', 'VerifSklController@checkPksSelesai')->name('checkPksSelesai');
		Route::get('{id}/showlocation', 'LokasiTanamController@showLocation')->name('showLocation');
		Route::post('{id}/storeCheck', 'VerifSklController@storeCheck')->name('storeCheck');
		Route::get('{id}/verifSklShow', 'VerifSklController@verifSklShow')->name('verifSklShow');

		//rekomendasi penerbitan
		Route::post('{id}/recomend', 'VerifSklController@recomend')->name('recomend');

		//daftar rekomendasi skl untuk pejabat
		Route::get('recomendations', 'VerifSklController@recomendations')->name('recomendations');
		Route::group(['prefix' => 'recomendation', 'as' => 'recomendation.'], function () {
			//detail rekomendasi untuk pejabat
			Route::get('{id}/show', 'VerifSklController@showrecom')->name('show');
			//preview draft skl untuk pejabat
			Route::get('{id}/draft', 'VerifSklController@draftSKL')->name('draft');
			//fungsi untuk pejabat menyetujui penerbitan.
			Route::put('{id}/approve', 'VerifSklController@approve')->name('approve');
		});

		//daftar skl diterbitkan
		Route::get('recomendations', 'VerifSklController@recomendations')->name('recomendations');
	});

	// Route::get('{noIjin}/lokasi/{anggota_id}', 'VerifTanamController@lokasicheck')->name('lokasicheck');


	Route::get('skl/{id}/show', 'SklController@show')->name('skl.show');

	//ke bawah ini mungkin di hapus
	Route::get('skl/publishes', 'SklController@publishes')->name('skl.publishes');
	Route::get('skl/published/{id}/print', 'SklController@published')->name('skl.published');
});

Route::group(['prefix' => 'skl', 'as' => 'skl.', 'namespace' => 'Verifikator', 'middleware' => ['auth']], function () {
	// daftar rekomendasi (index rekomendasi dan skl untuk verifikator)
	Route::get('recomended/list', 'VerifSklController@recomended')->name('recomended.list');
	Route::get('{id}/print', 'VerifSklController@printReadySkl')->name('print'); //form view skl untuk admin
	Route::put('{id}/upload', 'VerifSklController@Upload')->name('upload'); //fungsi upload untuk admin
	Route::get('arsip', 'VerifSklController@arsip')->name('arsip');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
	// Change password
	if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
		Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
		Route::post('password', 'ChangePasswordController@update')->name('password.update');
		Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
		Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
	}
});

Route::group(['prefix' => 'wilayah', 'as' => 'wilayah.', 'namespace' => 'Wilayah', 'middleware' => ['auth']], function () {
	Route::get('getAllProvinsi', 'GetWilayahController@getAllProvinsi')->name('getAllProvinsi');
	Route::get('getAllKabupaten', 'GetWilayahController@getAllKabupaten')->name('getAllKabupaten');
	Route::get('getKabupatenByProvinsi/{provinsiId}', 'GetWilayahController@getKabupatenByProvinsi')->name('getKabupatenByProvinsi');
	Route::get('getKecamatanByKabupaten/{id}', 'GetWilayahController@getKecamatanByKabupaten')->name('getKecamatanByKabupaten');
	Route::get('getDesaByKec/{kecamatanId}', 'GetWilayahController@getDesaByKecamatan')->name('getDesaByKecamatan');
	Route::get('getDesaById/{id}', 'GetWilayahController@getDesaById')->name('getDesaById');
	Route::get('getKecById/{id}', 'GetWilayahController@getKecById')->name('getKecById');
	Route::get('getKabById/{id}', 'GetWilayahController@getKabById')->name('getKabById');
	Route::get('getProvById/{id}', 'GetWilayahController@getProvById')->name('getProvById');
});

Route::group(['prefix' => 'digisign', 'as' => 'digisign.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
	Route::get('index', 'DigitalSign@index')->name('index');
	Route::post('saveQrImage', 'DigitalSign@saveQrImage')->name('saveQrImage');
});

Route::group(['prefix' => 'support', 'as' => 'support.', 'middleware' => ['auth']], function () {
	// Route::group(['prefix' => 'how_to', 'as' => 'howto.', 'namespace' => 'HowTo'], function () {
	// 	Route::get('/',		'HowToController@show')->name('show');
	// });
	Route::group(['prefix' => 'how_to', 'as' => 'howto.', 'namespace' => 'Howto'], function () {
		Route::get('importir',		'HowtoController@importir')->name('importir');
		Route::get('administrator',	'HowtoController@administrator')->name('administrator');
		Route::get('verifikator',	'HowtoController@verifikator')->name('verifikator');
		Route::get('pejabat',		'HowtoController@pejabat')->name('pejabat');
	});
	Route::group(['prefix' => 'faq', 'as' => 'faq.', 'namespace' => 'Faq'], function () {
	});
	Route::group(['prefix' => 'ticket', 'as' => 'ticket.', 'namespace' => 'Ticket'], function () {
	});
});

Route::group(['prefix' => 'test', 'as' => 'test.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
	Route::get('sample/{id}', 'TestController@index')->name('sample');
	Route::get('files', 'ListFileController@index')->name('files');
	Route::delete('files', 'ListFileController@destroy')->name('files.delete');
});


//Route untuk simethris 2024
Route::group(['prefix' => '2024', 'as' => '2024.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
	Route::group(['namespace' => 'Thn2024'], function () {

		Route::group(['prefix' => 'datafeeder', 'as' => 'datafeeder.'], function () {
			Route::get('/getAllSkls', 'DataFeederController@getAllSkls')->name('getAllSkls');
			Route::get('/getAllMyCommitment', 'DataFeederController@getAllMyCommitment')->name('getAllMyCommitment');
			Route::get('/getPksById/{id}', 'DataFeederController@getPksById')->name('getPksById');
			Route::get('/getPksByIjin/{noIjin}', 'DataFeederController@getPksByIjin')->name('getPksByIjin');
			Route::get('/getLokasiByPks/{noIjin}/{poktanId}', 'DataFeederController@getLokasiByPks')->name('getLokasiByPks');
			Route::get('/getLokasiByIjin/{noIjin}', 'DataFeederController@getLokasiByIjin')->name('getLokasiByIjin');
			Route::get('/timeline/{noIjin}', 'DataFeederController@timeline')->name('timeline');
			Route::get('/getLokasiByIjinNik/{noIjin}/{nik}', 'DataFeederController@getLokasiByIjinNik')->name('getLokasiByIjinNik');
			Route::get('/getSpatialByKecamatan/{kecId}', 'DataFeederController@getSpatialByKecamatan')->name('getSpatialByKecamatan');
			Route::get('/getSpatialByKode/{spatial}', 'DataFeederController@getSpatialByKode')->name('getSpatialByKode');
			Route::get('/getAllSpatials', 'DataFeederController@getAllSpatials')->name('getAllSpatials');
			Route::get('/getAllPoktan', 'DataFeederController@getAllPoktan')->name('getAllPoktan');
			Route::get('/getAllCpcl', 'DataFeederController@getAllCpcl')->name('getAllCpcl');
			Route::get('/{kecId}/getAllCpclByKec', 'DataFeederController@getAllCpclByKec')->name('getAllCpclByKec');
			Route::get('/{nik}/getCpclByNik', 'DataFeederController@getCpclByNik')->name('getCpclByNik');
			Route::get('/getDataPengajuan/{noIjin}', 'DataFeederController@getDataPengajuan')->name('getDataPengajuan');
			Route::get('/getRequestVerif', 'DataFeederController@getRequestVerif')->name('getRequestVerif');
			Route::get('/getRequestSkl', 'DataFeederController@getRequestSkl')->name('getRequestSkl');
			Route::get('/getRequestVerifTanam', 'DataFeederController@getRequestVerifTanam')->name('getRequestVerifTanam');
			Route::get('/getRequestVerifProduksi', 'DataFeederController@getRequestVerifProduksi')->name('getRequestVerifProduksi');
			Route::get('/getVerifTanamHistory/{noIjin}', 'DataFeederController@getVerifTanamHistory')->name('getVerifTanamHistory');
			Route::get('/getVerifProdHistory/{noIjin}', 'DataFeederController@getVerifProdHistory')->name('getVerifProdHistory');
			Route::get('/getVerifSklHistory/{noIjin}', 'DataFeederController@getVerifSklHistory')->name('getVerifSklHistory');
			Route::get('/getLocationSampling/{noIjin}', 'DataFeederController@getLocationSampling')->name('getLocationSampling');
			Route::get('/getVerifTanamByIjin/{noIjin}', 'DataFeederController@getVerifTanamByIjin')->name('getVerifTanamByIjin');
			Route::get('/getVerifProduksiByIjin/{noIjin}', 'DataFeederController@getVerifProduksiByIjin')->name('getVerifProduksiByIjin');
			Route::get('/getspatial', 'DataFeederController@getspatial')->name('getspatial');
			Route::post('/responseGetLocByRad', 'DataFeederController@responseGetLocByRad')->name('responseGetLocByRad');

			//wilayah provinsi s.d desa
			Route::get('/getAllProvinsi', 'DataFeederController@getAllProvinsi')->name('getAllProvinsi');
			Route::get('/getKabByProv/{prov}', 'DataFeederController@getKabByProv')->name('getKabByProv');
			Route::get('/getKecByKab/{kab}', 'DataFeederController@getKecByKab')->name('getKecByKab');
			Route::get('/getKelByKec/{kec}', 'DataFeederController@getKelByKec')->name('getKelByKec');

			Route::get('/getLocDataByIjinBySpatial/{noIjin}/{spatial}', 'DataFeederController@getLocDataByIjinBySpatial')->name('getLocDataByIjinBySpatial');
			Route::post('/postLocDataByIjinBySpatial', 'DataFeederController@postLocDataByIjinBySpatial')->name('postLocDataByIjinBySpatial');
			Route::get('/responseGetLocationInKabupaten', 'DataFeederController@responseGetLocationInKabupaten')->name('responseGetLocationInKabupaten');
			Route::get('/responseGetLocationByKode', 'DataFeederController@responseGetLocationByKode')->name('responseGetLocationByKode');
			Route::post('/responseGetSpatialDetail', 'DataFeederController@responseGetSpatialDetail')->name('responseGetSpatialDetail');
			Route::get('/responseGetSpatialMoreDetail/{spatial}', 'DataFeederController@responseGetSpatialMoreDetail')->name('responseGetSpatialMoreDetail');
			Route::get('/getInvalidNik', 'DataFeederController@getInvalidNik')->name('getInvalidNik');

			Route::get('/postLocDataByIjinBySpatial', 'DataFeederController@postLocDataByIjinBySpatial')->name('postLocDataByIjinBySpatial');

			//Logbook generator
			Route::get('/logbookReport/{noIjin}', 'LogbookController@index')->name('logbookReport');
			Route::get('/generateLogbook/{noIjin}', 'LogbookController@generateLogbook')->name('generateLogbook');

			//ini jangan dijalankan lagi
			Route::get('/filemanagement', 'DataFeederController@filemanagement')->name('filemanagement');
		});

		//route untuk adminisrator
		Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
			Route::get('/', 'HomeController@index')->name('home');

			//halaman pengajuan verifikasi dari importir
			Route::group(['prefix' => 'pengajuan', 'as' => 'pengajuan.'], function () {
				//route daftar pengajuan dan assignment.
				Route::get('/', 'PengajuanController@indexpengajuan');
				Route::get('/penugasan/{noIjin}/{tcode}', 'PengajuanController@assignment')->name('assignment');
				Route::post('/storeAssignment/{noIjin}/{tcode}', 'PengajuanController@storeAssignment')->name('storeAssignment');
				Route::post('/reAssignment/{noIjin}/{tcode}', 'PengajuanController@reAssignment')->name('reAssignment');
				Route::delete('/delete/{tcode}/assignment', 'PengajuanController@deleteAssignment')->name('deleteAssignment');
				Route::group(['prefix' => 'tanam', 'as' => 'tanam.'], function () {
					Route::post('/penugasan/saveSelectedLocations', 'VerifTanamController@saveSelectedLocations')->name('saveSelectedLocations');
					Route::get('/', 'VerifTanamController@indexpengajuan');
				});
			});
			//pengajuan SKL
			Route::group(['prefix' => 'permohonan', 'as' => 'permohonan.'], function () {
				Route::group(['prefix' => 'skl', 'as' => 'skl.'], function () {
					Route::get('/', 'VerifSklController@index')->name('index');
					Route::get('/{noIjin}/{tcode}', 'VerifSklController@check')->name('check');
					Route::post('/{noIjin}/{tcode}', 'VerifSklController@storeVerifSkl')->name('storeVerifSkl');
					Route::post('/return/{noIjin}/{tcode}', 'VerifSklController@returnVerif')->name('returnVerif');
					Route::post('/draft/{noIjin}/{tcode}', 'VerifSklController@draftSkl')->name('draftSkl');

					Route::get('/generateRepReqSkl/{noIjin}/{tcode}', 'VerifSklController@generateRepReqSkl')->name('generateRepReqSkl');
				});
			});

			Route::group(['prefix' => 'skl', 'as' => 'skl.'], function () {
				Route::get('/', 'SklController@index')->name('index');
				Route::post('/upload/{noIjin}/{tcode}', 'VerifSklController@uploadSkl')->name('uploadSkl');
				Route::post('/upload/{noIjin}/{tcode}', 'VerifSklController@uploadSkl')->name('uploadSkl');
				Route::post('/publish/{noIjin}/{tcode}', 'VerifSklController@publishSkl')->name('publishSkl');
			});
		});

		//route untuk pejabat
		Route::group(['prefix' => 'pejabat', 'as' => 'pejabat.'], function () {
			Route::get('/', 'HomeController@index')->name('home');

			Route::group(['prefix' => 'skl', 'as' => 'skl.'], function () {
				Route::get('/', 'HomeController@index')->name('index');
				Route::group(['prefix' => 'rekomendasi', 'as' => 'rekomendasi.'], function () {
					Route::get('/', 'PejabatController@index')->name('index');
					Route::get('/check/{noIjin}/{tcode}', 'PejabatController@approvalForm')->name('check');
					Route::post('/approval/{noIjin}/{tcode}', 'PejabatController@approvalStatus')->name('approvalStatus');
				});
			});
		});

		//route untuk verifikator
		Route::group(['prefix' => 'verifikator', 'as' => 'verifikator.'], function () {
			Route::get('/', 'HomeController@index')->name('home')->middleware('screen.redirect');
			Route::get('/mobile', 'HomeController@indexMobile')->name('mobile');

			Route::group(['prefix' => 'mobile', 'as' => 'mobile.'], function () {
				Route::get('/markers', 'VerifTanamController@findmarker')->name('findmarker');
				Route::get('/markers/verifikasi/{noIjin}/{spatial}', 'VerifTanamController@veriflokasimobile')->name('veriflokasimobile');
			});

			Route::group(['prefix' => 'tanam', 'as' => 'tanam.'], function () {
				Route::get('/', 'VerifTanamController@index')->name('home');
				Route::get('/check/berkas/{noIjin}/{tcode}', 'VerifTanamController@check')->name('check');
				Route::get('/check/pks/{noIjin}/{tcode}', 'VerifTanamController@checkpks')->name('checkpks');
				Route::get('/check/timeline/{noIjin}/{tcode}', 'VerifTanamController@checktimeline')->name('checktimeline');
				Route::get('/check/lokasi/{noIjin}/{tcode}', 'VerifTanamController@checkdaftarlokasi')->name('checkdaftarlokasi');
				Route::get('/check/final/{noIjin}/{tcode}', 'VerifTanamController@checkfinal')->name('checkfinal');
				Route::post('/saveCheckBerkas/{noIjin}', 'VerifTanamController@saveCheckBerkas')->name('saveCheckBerkas');
				Route::post('/verifPksStore/{noIjin}/{kodePoktan}', 'VerifTanamController@verifPksStore')->name('verifPksStore');
				Route::post('/markStatus/{noIjin}/{tcode}/{status}', 'VerifTanamController@markStatus')->name('markStatus');
				Route::post('/storelokasicheck/{noIjin}/{tcode}', 'VerifTanamController@storelokasicheck')->name('storelokasicheck');
				Route::get('/check/{noIjin}/{verifikasi}/{tcode}', 'VerifTanamController@verifLokasiByIjinBySpatial')->name('verifLokasiByIjinBySpatial');
				Route::post('/storePhaseCheck/{noIjin}/{spatial}', 'VerifTanamController@storePhaseCheck')->name('storePhaseCheck');
				Route::post('/storeFinalCheck/{noIjin}/{tcode}', 'VerifTanamController@storeFinalCheck')->name('storeFinalCheck');
				Route::get('/result/{noIjin}/{tcode}', 'VerifTanamController@result')->name('result');
				Route::get('/generateReport/{noIjin}/{tcode}', 'VerifTanamController@generateReport')->name('generateReport');
			});

			Route::group(['prefix' => 'produksi', 'as' => 'produksi.'], function () {
				Route::get('/', 'VerifProduksiController@index')->name('home');
				Route::get('/check/berkas/{noIjin}/{tcode}', 'VerifProduksiController@check')->name('check');
				Route::get('/check/pks/{noIjin}/{tcode}', 'VerifProduksiController@checkpks')->name('checkpks');
				Route::get('/check/timeline/{noIjin}/{tcode}', 'VerifProduksiController@checktimeline')->name('checktimeline');
				Route::get('/check/lokasi/{noIjin}/{tcode}', 'VerifProduksiController@checkdaftarlokasi')->name('checkdaftarlokasi');
				Route::get('/check/final/{noIjin}/{tcode}', 'VerifProduksiController@checkfinal')->name('checkfinal');
				Route::post('/saveCheckBerkas/{noIjin}', 'VerifProduksiController@saveCheckBerkas')->name('saveCheckBerkas');
				Route::post('/verifPksStore/{noIjin}/{kodePoktan}', 'VerifProduksiController@verifPksStore')->name('verifPksStore');
				Route::post('/markStatus/{noIjin}/{tcode}/{status}', 'VerifProduksiController@markStatus')->name('markStatus');
				Route::post('/storelokasicheck/{noIjin}/{tcode}', 'VerifProduksiController@storelokasicheck')->name('storelokasicheck');
				Route::get('/check/{noIjin}/{verifikasi}/{tcode}', 'VerifProduksiController@verifLokasiByIjinBySpatial')->name('verifLokasiByIjinBySpatial');
				Route::post('/storePhaseCheck/{noIjin}/{spatial}', 'VerifProduksiController@storePhaseCheck')->name('storePhaseCheck');
				Route::post('/storeFinalCheck/{noIjin}/{tcode}', 'VerifProduksiController@storeFinalCheck')->name('storeFinalCheck');
				Route::get('/result/{noIjin}/{tcode}', 'VerifProduksiController@result')->name('result');
				Route::get('/generateReport/{noIjin}/{tcode}', 'VerifProduksiController@generateReport')->name('generateReport');
			});
		});

		//route untuk pelaku usaha
		Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
			Route::get('/', 'HomeController@index')->name('home');

			Route::group(['prefix' => 'mobile', 'as' => 'mobile.'], function () {
				Route::get('/markers', 'CommitmentController@findmarker')->name('findmarker');
				Route::get('/markers/realisasi/{noIjin}/{spatial}', 'CommitmentController@realisasimobile')->name('realisasi');
			});

			//pullriph
			Route::group(['prefix' => 'pull', 'as' => 'pull.'], function () {
				Route::get('/', 'PullRiphController@index')->name('index');
				Route::get('/checkYear', 'PullRiphController@checkYear')->name('checkYear');
				Route::get('/getriph', 'PullRiphController@pull')->name('getriph');
				Route::post('/store', 'PullRiphController@store')->name('store');
			});

			//commitment
			Route::group(['prefix' => 'commitment', 'as' => 'commitment.'], function () {
				Route::get('/', 'CommitmentController@index')->name('index');
				Route::get('{noIjin}/show', 'CommitmentController@show')->name('show');
				Route::put('pks/{id}/update', 'CommitmentController@updatePks')->name('updatepks');
				Route::delete('{pullriph}', 'CommitmentController@destroy')->name('destroy');

				//pengisian data realisasi
				Route::get('{noIjin}/realisasi', 'CommitmentController@realisasi')->name('realisasi');
				Route::post('{noIjin}/storeUserDocs', 'CommitmentController@storeUserDocs')->name('storeUserDocs');

				Route::group(['prefix' => 'pks', 'as' => 'pks.'], function () {
					Route::get('/{noIjin}/{poktanId}/create', 'PksController@createPks')->name('create');
					Route::post('/store', 'PksController@storePks')->name('storePks');
				});
				Route::get('daftarlokasi/{noIjin}/{poktanId}', 'PksController@daftarLokasi')->name('daftarLokasi');
				Route::get('addrealisasi/{noIjin}/{spatial}', 'PksController@addrealisasi')->name('addrealisasi');
				Route::post('storefoto/{noIjin}/{spatial}', 'PksController@storeFoto')->name('storefoto');
				Route::post('storerealisasi/{noIjin}/{spatial}', 'PksController@storerealisasi')->name('storerealisasi');
				Route::delete('deleteOriginLocalRealisasi/{spatial}', 'PksController@deleteOriginLocalRealisasi')->name('deleteOriginLocalRealisasi');

				Route::delete('/commitmentmd', 'CommitmentController@massDestroy')->name('massDestroy');

				//form pengajuan verifikasi
				Route::get('{noIjin}/formavt', 'AjuVerifTanamController@index')->name('formavt');
				Route::get('{noIjin}/formavp', 'AjuVerifProdController@index')->name('formavp');
				Route::get('{noIjin}/formavskl', 'AjuVerifSKLController@index')->name('formavskl');

				Route::post('{noIjin}/pengajuan/store', 'PengajuanController@submitPengajuan')->name('submitPengajuan');
				Route::post('{noIjin}/permohonan/store', 'AjuVerifSKLController@submitPengajuanSkl')->name('submitPengajuanSkl');
				Route::post('{noIjin}/permohonan/update', 'AjuVerifSKLController@reSubmitPengajuanSkl')->name('reSubmitPengajuanSkl');
				Route::get('{noIjin}/permohonan/generateRepReqSkl', 'AjuVerifSKLController@generateRepReqSkl')->name('generateRepReqSkl');
			});

			Route::group(['prefix' => 'skl', 'as' => 'skl.'], function () {
				Route::get('/skl', 'SklController@mySkls')->name('mySkls');
			});


			//berkas file management
			Route::group(['prefix' => 'files', 'as' => 'files.'], function () {
			});

		});

		//route untuk tim spatial
		Route::group(['prefix' => 'spatial', 'as' => 'spatial.'], function () {
			Route::get('/', 'HomeController@indexMobile')->name('home');
			Route::get('/list', 'SpatialController@index')->name('index');
			Route::get('/spatialList', 'SpatialController@spatialList')->name('spatialList');
			Route::get('/createsingle', 'SpatialController@createsingle')->name('createsingle');
			Route::post('/storesingle', 'SpatialController@storesingle')->name('storesingle');
			Route::get('/{id}/show', 'SpatialController@show')->name('edit');
			Route::post('/updatesingle', 'SpatialController@updatesingle')->name('updatesingle');
			Route::post('/updateStatus/{kodeSpatial}', 'SpatialController@updateStatus')->name('updateStatus');
			Route::post('/batchUpdateStatus', 'SpatialController@batchUpdateStatus')->name('batchUpdateStatus');

			Route::get('/master-wilayah', 'MasterWilayahController@index')->name('wilayah');
			Route::get('/master-wilayah/updateFromBPS', 'MasterWilayahController@updateFromBPS')->name('updateFromBPS');
			Route::get('/master-wilayah/updateProvinsiFromBPS', 'MasterWilayahController@updateProvinsiFromBPS')->name('updateProvinsiFromBPS');
			Route::get('/master-wilayah/updateKabupatenFromBPS/{provinsiId}', 'MasterWilayahController@updateKabupatenFromBPS')->name('updateKabupatenFromBPS');
			Route::get('/master-wilayah/updateKecamatanFromBPS/{provinsiId}', 'MasterWilayahController@updateKecamatanFromBPS')->name('updateKecamatanFromBPS');
			Route::get('/master-wilayah/updateDesaFromBPS/{provinsiId}', 'MasterWilayahController@updateDesaFromBPS')->name('updateDesaFromBPS');
			Route::get('/master-wilayah/updateAllDesaFromBPS', 'MasterWilayahController@updateAllDesaFromBPS')->name('updateAllDesaFromBPS');

			Route::get('/simulator', 'SpatialController@simulatorJarak')->name('simulatorJarak');
		});

		//route untuk cpcl
		Route::group(['prefix' => 'cpcl', 'as' => 'cpcl.'], function () {
			Route::get('/', 'HomeController@index')->name('home');
			Route::group(['prefix' => 'poktan', 'as' => 'poktan.'], function () {
				Route::get('/list', 'MasterPoktanController@index')->name('index');
				Route::get('/registrasi', 'MasterPoktanController@create')->name('create');
				Route::get('/{id}/edit', 'MasterPoktanController@edit')->name('edit');
				// Route::get('/updateIdProvinsi', 'MasterPoktanController@updateIdProvinsi')->name('updateIdProvinsi');
			});

			Route::group(['prefix' => 'anggota', 'as' => 'anggota.'], function () {
				Route::get('/list', 'MasterCpclController@index')->name('index');
				Route::get('/registrasi', 'MasterCpclController@create')->name('create');
				Route::get('/{nik}/show', 'MasterCpclController@show')->name('show');
				Route::get('/{nik}/edit', 'MasterCpclController@edit')->name('edit');
			});
		});
	});
});








//hold dulu bagian ini ke bawah
Route::group(['prefix' => 'mobile', 'as' => 'mobile.', 'namespace' => 'Mobile'], function () {
	Route::get('/login', 'LoginController@index')->name('login');
	Route::post('/login', 'LoginController@login')->name('login');
});
Route::group(['prefix' => 'mobile', 'as' => 'mobile.', 'namespace' => 'Mobile', 'middleware' => ['auth']], function () {
	Route::post('/logout', 'LoginController@logout')->name('logout');
	Route::get('/', 'HomeController@index')->name('home');

	//verifikasi tanam
	Route::group(['prefix' => 'verifikasi', 'as' => 'verifikasi.'], function () {
		Route::group(['prefix' => 'tanam', 'as' => 'tanam.'], function () {
			Route::get('/', 'VerifikasiTanamController@index');
			Route::get('/tanam/maps/{noIjin}', 'VerifikasiTanamController@verifikasiMap')->name('maps');
			Route::get('/tanam/maps/{noIjin}/{spatial}', 'VerifikasiTanamController@verifikasilokasitanam')->name('lokasi');
		});
	});
});
