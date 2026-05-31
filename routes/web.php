<?php

use Illuminate\Support\Facades\Route;

Route::prefix('society-event')->group(function () {
    
    Route::get('/', [App\Http\Controllers\WebHomeController::class, 'index']);

    // frontend 
    Route::get('/about', [App\Http\Controllers\WebHomeController::class, 'index'])->name('about');
    Route::get('/home', [App\Http\Controllers\WebHomeController::class, 'index'])->name('home');

    Route::get('/countDataDashboard', [App\Http\Controllers\WebDashboardController::class, 'getCountData'])->name('countDataDashboard');
    Route::get('/listDataDashboard', [App\Http\Controllers\WebDashboardController::class, 'getListData'])->name('listDataDashboard');
    Route::get('/topikDashboard', [App\Http\Controllers\WebDashboardController::class, 'getTopik'])->name('topikDashboard');
    Route::get('/tautanDashboard', [App\Http\Controllers\WebDashboardController::class, 'getTautan'])->name('tautanDashboard');
    Route::get('/sliderDashboard', [App\Http\Controllers\WebDashboardController::class, 'getSlider'])->name('sliderDashboard');

    Route::get('/organisasiBottom', [App\Http\Controllers\WebDashboardController::class, 'getOrganisasi'])->name('organisasiBottom');
    Route::post('/survey-submit', [App\Http\Controllers\WebDashboardController::class, 'submit'])->name('survey-submit');

    Route::get('list-organisasi', [App\Http\Controllers\WebOrganisasiController::class, 'index'])->name('list-organisasi');
    Route::get('/detail-organisasi/{kode_organisasi}', [App\Http\Controllers\WebOrganisasiController::class, 'detailOrganisasi'])->name('detail-organisasi');

    Route::get('list-topik', [App\Http\Controllers\WebTopikController::class, 'index'])->name('list-topik');

    Route::get('/hubungi-kami', [App\Http\Controllers\WebHubungiController::class, 'index'])->name('hubungi-kami');
    Route::post('/hubungiKamiAction', [App\Http\Controllers\WebHubungiController::class, 'hubungiKamiAction'])->name('hubungiKamiAction');

    Route::get('/login', [App\Http\Controllers\WebLoginController::class, 'index'])->name('login');
    Route::get('/register', [App\Http\Controllers\WebLoginController::class, 'register'])->name('register');
    Route::post('/registrasiAction', [App\Http\Controllers\WebLoginController::class, 'registrasiAction'])->name('registrasiAction');
    Route::get('/verifikasiAkun/{token}', [App\Http\Controllers\WebLoginController::class, 'verifikasiAkun'])->name('verifikasiAkun');
    Route::post('/loginAction', [App\Http\Controllers\WebLoginController::class, 'loginAction'])->name('loginAction');
    Route::get('/lupa-password', [App\Http\Controllers\WebLoginController::class, 'lupaPassword'])->name('lupa-password');
    Route::get('/password-baru/{token}', [App\Http\Controllers\WebLoginController::class, 'passwordBaru'])->name('password-baru');
    Route::post('/lupaPasswordAction', [App\Http\Controllers\WebLoginController::class, 'lupaPasswordAction'])->name('lupaPasswordAction');
    Route::post('/ganitPasswordAction', [App\Http\Controllers\WebLoginController::class, 'ganitPasswordAction'])->name('ganitPasswordAction');
    Route::get('/otpLogin/{otp}', [App\Http\Controllers\WebLoginController::class, 'otpLogin'])->name('otpLogin');
    Route::post('/verifyOtpAction', [App\Http\Controllers\WebLoginController::class, 'verifyOtpAction'])->name('verifyOtpAction');

    Route::get('/list', [App\Http\Controllers\WebDataController::class, 'index'])->name('list');
    Route::get('/listData', [App\Http\Controllers\WebDataController::class, 'getDataList'])->name('listData');
    Route::get('/detail-data/{url_data}', [App\Http\Controllers\WebDataController::class, 'detailData'])->name('detail-data');
    Route::get('/data-json/{url_data}', [App\Http\Controllers\WebDataController::class, 'dataJson'])->name('data-json');
    Route::post('/permohonanAction', [App\Http\Controllers\WebDataController::class, 'permohonanAction'])->name('permohonanAction');
    Route::get('/detail-dcat/{url_data}', [App\Http\Controllers\WebDataController::class, 'dataDcat'])->name('detail-dcat');
    Route::get('/file-preview/{fileName}', [App\Http\Controllers\WebDataController::class, 'filePreview'])->where('fileName', '.*')->name('file-preview');
    Route::get('/filePreviewShow/{sifat}/{file}', [App\Http\Controllers\WebDataController::class, 'filePreviewShow'])->where('file', '.*')->name('show-file');

    Route::get('/preview-csv/{kode}/{id}', [App\Http\Controllers\WebDataController::class, 'previewCsv'])->name('preview.csv');

    Route::get('/pushDataset/{kode}', [App\Http\Controllers\RabbitMQController::class, 'pushDataset'])->name('pushDataset');

    Route::get('/unduh-terbuka/{kode_data}', [App\Http\Controllers\WebDataController::class, 'unduhTerbuka'])->name('unduh-terbuka');
    Route::post('/log-preview', [App\Http\Controllers\WebDataController::class, 'logPreview'])->name('log.preview');

    Route::get('/tentang-kami', [App\Http\Controllers\WebTentangController::class, 'index'])->name('tentang-kami');

    Route::get('/monitoring-permohonan', [App\Http\Controllers\WebMonitoringController::class, 'index'])->name('monitoring-permohonan');
    Route::post('/monitoringAction', [App\Http\Controllers\WebMonitoringController::class, 'monitoringAction'])->name('monitoringAction');

    Route::get('profile-user', [App\Http\Controllers\WebProfilController::class, 'index'])->name('profile-user');
    Route::post('updateProfilUserAction', [App\Http\Controllers\WebProfilController::class, 'updateProfilUserAction'])->name('updateProfilUserAction');
    Route::post('updatePasswordUserAction', [App\Http\Controllers\WebProfilController::class, 'updatePasswordUserAction'])->name('updatePasswordUserAction');
    Route::get('ganti-password-user', [App\Http\Controllers\WebProfilController::class, 'gantiPasswordUser'])->name('ganti-password-user');
    Route::get('riwayat-user', [App\Http\Controllers\WebProfilController::class, 'riwayatUser'])->name('riwayat-user');

    Route::get('/refresh-captcha', function () {
        return response()->json(['captcha' => captcha_img()]);
    })->name('refresh.captcha');

    Route::get('/test-captcha', function() {
        return captcha_img();
    });
    // end front end 

    Route::get('admin-panel', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin-panel');
    // route menu  referensi dashboard
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('ref-sponsor', [App\Http\Controllers\ReffSponsorController::class, 'index'])->name('ref-sponsor');
    Route::get('/getTableSponsor', [App\Http\Controllers\ReffSponsorController::class, 'getTableSponsor'])->name('getTableSponsor');
    Route::get('tambah-ref-sponsor', [App\Http\Controllers\ReffSponsorController::class, 'tambah'])->name('tambah-ref-sponsor');
    Route::get('/editSponsor/{id_sponsor}', [App\Http\Controllers\ReffSponsorController::class, 'editSponsor'])->name('editSponsor');
    Route::post('/addSponsorAction', [App\Http\Controllers\ReffSponsorController::class, 'addSponsorAction'])->name('addSponsorAction');
    Route::post('/updateSponsorAction', [App\Http\Controllers\ReffSponsorController::class, 'updateSponsorAction'])->name('updateSponsorAction');
    Route::post('/deleteSponsorAction', [App\Http\Controllers\ReffSponsorController::class, 'deleteSponsorAction'])->name('deleteSponsorAction');

    Route::get('ref-role', [App\Http\Controllers\ReffRoleController::class, 'index'])->name('ref-role');
    Route::get('/getTableRole', [App\Http\Controllers\ReffRoleController::class, 'getTableRole'])->name('getTableRole');
    Route::get('/editRole/{id_role}', [App\Http\Controllers\ReffRoleController::class, 'editRole'])->name('editRole');
    Route::get('tambah-ref-role', [App\Http\Controllers\ReffRoleController::class, 'tambah'])->name('tambah-ref-role');
    Route::post('/addRoleAction', [App\Http\Controllers\ReffRoleController::class, 'addRoleAction'])->name('addRoleAction');
    Route::post('/updateRoleAction', [App\Http\Controllers\ReffRoleController::class, 'updateRoleAction'])->name('updateRoleAction');
    Route::post('/deleteRoleAction', [App\Http\Controllers\ReffRoleController::class, 'deleteRoleAction'])->name('deleteRoleAction');
    Route::get('/menuRole/{id_role}', [App\Http\Controllers\ReffRoleController::class, 'menuRole'])->name('menuRole');
    Route::post('/addAksesMenuAction', [App\Http\Controllers\ReffRoleController::class, 'addAksesMenuAction'])->name('addAksesMenuAction');
    Route::get('/getTableMenuRole', [App\Http\Controllers\ReffRoleController::class, 'getTableMenuRole'])->name('getTableMenuRole');
    Route::post('/deleteMenuRoleAction', [App\Http\Controllers\ReffRoleController::class, 'deleteMenuRoleAction'])->name('deleteMenuRoleAction');
    Route::get('/editAksesMenu/{id_akses_menu}', [App\Http\Controllers\ReffRoleController::class, 'editAksesMenu'])->name('editAksesMenu');
    Route::post('/updateAksesMenuAction', [App\Http\Controllers\ReffRoleController::class, 'updateAksesMenuAction'])->name('updateAksesMenuAction');
    
    Route::get('ref-menu', [App\Http\Controllers\ReffMenuController::class, 'index'])->name('ref-menu');
    Route::get('/getTableMenu', [App\Http\Controllers\ReffMenuController::class, 'getTableMenu'])->name('getTableMenu');
    Route::get('/editMenu/{id_menu}', [App\Http\Controllers\ReffMenuController::class, 'editMenu'])->name('editMenu');
    Route::get('tambah-ref-menu', [App\Http\Controllers\ReffMenuController::class, 'tambah'])->name('tambah-ref-menu');
    Route::post('/addMenuAction', [App\Http\Controllers\ReffMenuController::class, 'addMenuAction'])->name('addMenuAction');
    Route::post('/deleteMenuAction', [App\Http\Controllers\ReffMenuController::class, 'deleteMenuAction'])->name('deleteMenuAction');
    Route::post('/updateMenuAction', [App\Http\Controllers\ReffMenuController::class, 'updateMenuAction'])->name('updateMenuAction');

    Route::get('ref-pengguna', [App\Http\Controllers\ReffUserController::class, 'index'])->name('ref-pengguna');
    Route::get('/getTableUser', [App\Http\Controllers\ReffUserController::class, 'getTableUser'])->name('getTableUser');
    Route::get('tambah-ref-pengguna', [App\Http\Controllers\ReffUserController::class, 'tambah'])->name('tambah-ref-pengguna');
    Route::post('/addUserAction', [App\Http\Controllers\ReffUserController::class, 'addUserAction'])->name('addUserAction');
    Route::get('/editUser/{id_user}', [App\Http\Controllers\ReffUserController::class, 'editUser'])->name('editUser');
    Route::post('/updateUserAction', [App\Http\Controllers\ReffUserController::class, 'updateUserAction'])->name('updateUserAction');
    Route::post('/deleteUserAction', [App\Http\Controllers\ReffUserController::class, 'deleteUserAction'])->name('deleteUserAction');

    Route::get('login-backend', [App\Http\Controllers\LoginController::class, 'index'])->name('login-backend');
    Route::post('/login-backend-action', [App\Http\Controllers\LoginController::class, 'loginBackendAction'])->name('login-backend-action');
    Route::post('logout-backend-action', [App\Http\Controllers\LoginController::class, 'logoutBackendAction'])->name('logout-backend-action');
    Route::get('/otpAdminPanelLogin/{otp}', [App\Http\Controllers\LoginController::class, 'otpAdminPanelLogin'])->name('otpAdminPanelLogin');
    Route::post('/verifyOtpAdminPanelAction', [App\Http\Controllers\LoginController::class, 'verifyOtpAdminPanelAction'])->name('verifyOtpAdminPanelAction');

    Route::get('ref-topik', [App\Http\Controllers\ReffTopikController::class, 'index'])->name('ref-topik');
    Route::get('/getTableTopik', [App\Http\Controllers\ReffTopikController::class, 'getTableTopik'])->name('getTableTopik');
    Route::get('/editTopik/{id_topik}', [App\Http\Controllers\ReffTopikController::class, 'editTopik'])->name('editTopik');
    Route::get('tambah-ref-topik', [App\Http\Controllers\ReffTopikController::class, 'tambah'])->name('tambah-ref-topik');
    Route::post('/addTopikAction', [App\Http\Controllers\ReffTopikController::class, 'addTopikAction'])->name('addTopikAction');
    Route::post('/deleteTopikAction', [App\Http\Controllers\ReffTopikController::class, 'deleteTopikAction'])->name('deleteTopikAction');
    Route::post('/updateTopikAction', [App\Http\Controllers\ReffTopikController::class, 'updateTopikAction'])->name('updateTopikAction');

    Route::get('tautan', [App\Http\Controllers\TautanController::class, 'index'])->name('tautan');
    Route::get('tambah-tautan', [App\Http\Controllers\TautanController::class, 'tambah'])->name('tambah-tautan');
    Route::get('/getTableTautan', [App\Http\Controllers\TautanController::class, 'getTableTautan'])->name('getTableTautan');
    Route::post('/addTautanAction', [App\Http\Controllers\TautanController::class, 'addTautanAction'])->name('addTautanAction');
    Route::get('/editTautan/{id_organisasi}', [App\Http\Controllers\TautanController::class, 'editTautan'])->name('editTautan');
    Route::post('/updateTautanAction', [App\Http\Controllers\TautanController::class, 'updateTautanAction'])->name('updateTautanAction');
    Route::post('/deleteTautanAction', [App\Http\Controllers\TautanController::class, 'deleteTautanAction'])->name('deleteTautanAction');

    Route::get('setting', [App\Http\Controllers\SettingController::class, 'index'])->name('setting');
    Route::post('/updateSettingAction', [App\Http\Controllers\SettingController::class, 'updateSettingAction'])->name('updateSettingAction');
    Route::post('/addSliderAction', [App\Http\Controllers\SettingController::class, 'addSliderAction'])->name('addSliderAction');
    Route::post('/addSliderTextAction', [App\Http\Controllers\SettingController::class, 'addSliderTextAction'])->name('addSliderTextAction');
    Route::post('/deleteSliderAction', [App\Http\Controllers\SettingController::class, 'deleteSliderAction'])->name('deleteSliderAction');
    Route::get('getTableSlider', [App\Http\Controllers\SettingController::class, 'getTableSlider'])->name('getTableSlider');
    Route::get('getTableSliderText', [App\Http\Controllers\SettingController::class, 'getTableSliderText'])->name('getTableSliderText');
    Route::get('/editSlider/{id_slider}', [App\Http\Controllers\SettingController::class, 'editSlider'])->name('editSlider');
    Route::post('/updateSliderAction', [App\Http\Controllers\SettingController::class, 'updateSliderAction'])->name('updateSliderAction');

    // route menu midtrans configurations
    Route::get('midtrans-config', [App\Http\Controllers\MidtransConfigController::class, 'index'])->name('midtrans-config');
    Route::post('/updateMidtransConfigAction', [App\Http\Controllers\MidtransConfigController::class, 'updateMidtransConfigAction'])->name('updateMidtransConfigAction');
    Route::post('/testMidtransConnectionAction', [App\Http\Controllers\MidtransConfigController::class, 'testConnectionAction'])->name('testMidtransConnectionAction');
    Route::post('/getMidtransStatusAction', [App\Http\Controllers\MidtransConfigController::class, 'getTransactionStatusAction'])->name('getMidtransStatusAction');
    Route::post('/approveMidtransAction', [App\Http\Controllers\MidtransConfigController::class, 'approveTransactionAction'])->name('approveMidtransAction');
    Route::post('/cancelMidtransAction', [App\Http\Controllers\MidtransConfigController::class, 'cancelTransactionAction'])->name('cancelMidtransAction');
    Route::post('/refundMidtransAction', [App\Http\Controllers\MidtransConfigController::class, 'refundTransactionAction'])->name('refundMidtransAction');
    Route::post('/expireMidtransAction', [App\Http\Controllers\MidtransConfigController::class, 'expireTransactionAction'])->name('expireMidtransAction');
    Route::post('/createMidtransSnapTokenAction', [App\Http\Controllers\MidtransConfigController::class, 'createSnapTokenAction'])->name('createMidtransSnapTokenAction');
    Route::post('/createMidtransChargeAction', [App\Http\Controllers\MidtransConfigController::class, 'createChargeAction'])->name('createMidtransChargeAction');
    Route::get('/getTableMidtransTransaksi', [App\Http\Controllers\MidtransConfigController::class, 'getTableTransaksi'])->name('getTableMidtransTransaksi');
    Route::post('/syncMidtransTransaksiAction', [App\Http\Controllers\MidtransConfigController::class, 'syncTransaksiAction'])->name('syncMidtransTransaksiAction');
    Route::post('/fetchMidtransTransactionsAction', [App\Http\Controllers\MidtransConfigController::class, 'fetchMidtransTransactionsAction'])->name('fetchMidtransTransactionsAction');

    Route::get('profile', [App\Http\Controllers\ProfilController::class, 'index'])->name('profile');
    Route::post('updateProfilAction', [App\Http\Controllers\ProfilController::class, 'updateProfilAction'])->name('updateProfilAction');
    Route::post('updatePasswordAction', [App\Http\Controllers\ProfilController::class, 'updatePasswordAction'])->name('updatePasswordAction');
    Route::get('ganti-password', [App\Http\Controllers\ProfilController::class, 'gantiPassword'])->name('ganti-password');

    // route menu event
    Route::get('event', [App\Http\Controllers\EventController::class, 'index'])->name('event');
    Route::get('/getTableEvent', [App\Http\Controllers\EventController::class, 'getTableEvent'])->name('getTableEvent');
    Route::get('tambah-event', [App\Http\Controllers\EventController::class, 'tambah'])->name('tambah-event');
    Route::get('/editEvent/{kode_event}', [App\Http\Controllers\EventController::class, 'editEvent'])->name('editEvent');
    Route::post('/addEventAction', [App\Http\Controllers\EventController::class, 'addEventAction'])->name('addEventAction');
    Route::post('/updateEventAction', [App\Http\Controllers\EventController::class, 'updateEventAction'])->name('updateEventAction');
    Route::post('/deleteEventAction', [App\Http\Controllers\EventController::class, 'deleteEventAction'])->name('deleteEventAction');
    Route::get('/paketEvent/{kode_event}', [App\Http\Controllers\EventController::class, 'paketEvent'])->name('paketEvent');
    Route::get('/programEvent/{kode_event}', [App\Http\Controllers\EventController::class, 'programEvent'])->name('programEvent');
    Route::get('/kolaborasiEvent/{kode_event}', [App\Http\Controllers\EventController::class, 'kolaborasiEvent'])->name('kolaborasiEvent');

    Route::get('/getTablePaketEvent', [App\Http\Controllers\EventController::class, 'getTablePaketEvent'])->name('getTablePaketEvent');
    Route::get('/tambahPaketEvent/{kode_paket}', [App\Http\Controllers\EventController::class, 'tambahPaketEvent'])->name('tambahPaketEvent');
    Route::get('/editPaketEvent/{kode_paket}', [App\Http\Controllers\EventController::class, 'editPaketEvent'])->name('editPaketEvent');
    Route::post('/addPaketEventAction', [App\Http\Controllers\EventController::class, 'addPaketEventAction'])->name('addPaketEventAction');
    Route::post('/editPaketEventAction', [App\Http\Controllers\EventController::class, 'editPaketEventAction'])->name('editPaketEventAction');
    Route::post('/deletePaketEventAction', [App\Http\Controllers\EventController::class, 'deletePaketEventAction'])->name('deletePaketEventAction');
    
    Route::get('/getTableProgramEvent', [App\Http\Controllers\EventController::class, 'getTableProgramEvent'])->name('getTableProgramEvent');
    Route::get('/tambahProgramEvent/{kode_program}', [App\Http\Controllers\EventController::class, 'tambahProgramEvent'])->name('tambahProgramEvent');
    Route::get('/editProgramEvent/{kode_program}', [App\Http\Controllers\EventController::class, 'editProgramEvent'])->name('editProgramEvent');
    Route::post('/addProgramEventAction', [App\Http\Controllers\EventController::class, 'addProgramEventAction'])->name('addProgramEventAction');

    // route menu event registrasi peserta
    Route::get('event-registrasi', [App\Http\Controllers\EventRegistrasiController::class, 'index'])->name('event-registrasi');
    Route::get('/getTableRegistrasi', [App\Http\Controllers\EventRegistrasiController::class, 'getTableRegistrasi'])->name('getTableRegistrasi');
    Route::post('/updateStatusRegistrasiAction', [App\Http\Controllers\EventRegistrasiController::class, 'updateStatusRegistrasiAction'])->name('updateStatusRegistrasiAction');
    Route::post('/deleteRegistrasiAction', [App\Http\Controllers\EventRegistrasiController::class, 'deleteRegistrasiAction'])->name('deleteRegistrasiAction');
    Route::get('/detailPaperRegistrasi/{id_registrasi}', [App\Http\Controllers\EventRegistrasiController::class, 'detailPaperRegistrasi'])->name('detailPaperRegistrasi');

    // route menu event paper
    Route::get('event-paper', [App\Http\Controllers\EventPaperController::class, 'index'])->name('event-paper');
    Route::get('/getTablePaper', [App\Http\Controllers\EventPaperController::class, 'getTablePaper'])->name('getTablePaper');
    Route::get('/getTablePaperByRegistrasi', [App\Http\Controllers\EventPaperController::class, 'getTablePaperByRegistrasi'])->name('getTablePaperByRegistrasi');
    Route::get('/editPaper/{id_paper}', [App\Http\Controllers\EventPaperController::class, 'editPaper'])->name('editPaper');
    Route::post('/updatePaperAction', [App\Http\Controllers\EventPaperController::class, 'updatePaperAction'])->name('updatePaperAction');
    Route::post('/updateStatusPaperAction', [App\Http\Controllers\EventPaperController::class, 'updateStatusPaperAction'])->name('updateStatusPaperAction');
    Route::post('/deletePaperAction', [App\Http\Controllers\EventPaperController::class, 'deletePaperAction'])->name('deletePaperAction');

});
