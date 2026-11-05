<?php

// Inisialisasi session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load Controller
require_once __DIR__ . '/Controllers/SayaController.php';
require_once __DIR__ . '/Controllers/ClassController.php';
require_once __DIR__ . '/Controllers/CustomerController.php';
require_once __DIR__ . '/Controllers/adminController.php';
require_once __DIR__ . '/Controllers/JasController.php';
require_once __DIR__ . '/Controllers/OrderController.php';
require_once __DIR__ . '/Controllers/PengembalianController.php';
require_once __DIR__ . '/Controllers/KatalogController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/DashboardController.php';
require_once __DIR__ . '/Controllers/DetailTransaksiController.php';
require_once __DIR__ . '/Controllers/SayaController.php';
require_once __DIR__ . '/Controllers/ClassController.php';
require_once __DIR__ . '/Controllers/CustomerController.php';
require_once __DIR__ . '/Controllers/adminController.php';
require_once __DIR__ . '/Controllers/JasController.php';
require_once __DIR__ . '/Controllers/OrderController.php';
require_once __DIR__ . '/Controllers/PengembalianController.php';
require_once __DIR__ . '/Controllers/KatalogController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/DashboardController.php';

// Routing request
$action = strtolower($_GET['action'] ?? $_GET['page'] ?? 'dashboard');

// Proteksi akses panel admin
$publicActions = ['katalog', 'katalog_customer', 'katalog-customer', 'home', 'sewa', 'sewa_proses', 'sewa-proses', 'login', 'login_proses', 'login-proses'];
if (!in_array($action, $publicActions, true)) {
    if (empty($_SESSION['admin_user'])) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Silakan login terlebih dahulu untuk mengakses panel admin!'
        ];
        header('Location: index.php?action=login');
        exit;
    }
}

switch ($action) {
    // Module: Autentikasi Admin
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'login_proses':
    case 'login-proses':
        $controller = new AuthController();
        $controller->processLogin();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    // Module: Katalog Customer
    case 'katalog':
    case 'katalog_customer':
    case 'katalog-customer':
    case 'home':
        $controller = new KatalogController();
        $controller->index();
        break;

    case 'sewa':
    case 'sewa_proses':
    case 'sewa-proses':
        $controller = new KatalogController();
        $controller->sewa();
        break;

    // Module: Saya
    case 'saya':
        $controller = new SayaController();
        $controller->index();
        break;

    // Module: Class
    case 'class':
    case 'kelas':
        $controller = new ClassController();
        $controller->index();
        break;

    // Module: Admin
    case 'admin':
    case 'tb_admin':
        $controller = new adminController();
        $controller->index();
        break;

    case 'admin_detail':
    case 'admin-detail':
        $controller = new adminController();
        $controller->detail();
        break;

    case 'admin_create':
    case 'admin-create':
    case 'admin_tambah':
        $controller = new adminController();
        $controller->create();
        break;

    case 'admin_store':
    case 'admin-store':
        $controller = new adminController();
        $controller->store();
        break;

    case 'admin_edit':
    case 'admin-edit':
    case 'admin_ubah':
        $controller = new adminController();
        $controller->edit();
        break;

    case 'admin_update':
    case 'admin-update':
        $controller = new adminController();
        $controller->update();
        break;

    case 'admin_delete':
    case 'admin-delete':
    case 'admin_hapus':
        $controller = new adminController();
        $controller->delete();
        break;

    // Module: Jas
    case 'jas':
    case 'tbjas':
    case 'tb_jas':
        $controller = new JasController();
        $controller->index();
        break;

    case 'jas_detail':
    case 'jas-detail':
        $controller = new JasController();
        $controller->detail();
        break;

    case 'jas_create':
    case 'jas-create':
    case 'jas_tambah':
        $controller = new JasController();
        $controller->create();
        break;

    case 'jas_store':
    case 'jas-store':
        $controller = new JasController();
        $controller->store();
        break;

    case 'jas_edit':
    case 'jas-edit':
    case 'jas_ubah':
        $controller = new JasController();
        $controller->edit();
        break;

    case 'jas_update':
    case 'jas-update':
        $controller = new JasController();
        $controller->update();
        break;

    case 'jas_delete':
    case 'jas-delete':
    case 'jas_hapus':
        $controller = new JasController();
        $controller->delete();
        break;

    // Module: Order
    case 'order':
    case 'tb_order':
    case 'tborder':
    case 'pesanan':
        $controller = new OrderController();
        $controller->index();
        break;

    case 'order_detail':
    case 'order-detail':
        $controller = new OrderController();
        $controller->detail();
        break;

    case 'order_create':
    case 'order-create':
    case 'order_tambah':
        $controller = new OrderController();
        $controller->create();
        break;

    case 'order_store':
    case 'order-store':
        $controller = new OrderController();
        $controller->store();
        break;

    case 'order_edit':
    case 'order-edit':
    case 'order_ubah':
        $controller = new OrderController();
        $controller->edit();
        break;

    case 'order_update':
    case 'order-update':
        $controller = new OrderController();
        $controller->update();
        break;

    case 'order_status':
    case 'order-status':
    case 'order_verify':
    case 'order-verify':
    case 'order_update_status':
        $controller = new OrderController();
        $controller->updateStatus();
        break;

    case 'order_payment_status':
    case 'order-payment-status':
        $controller = new OrderController();
        $controller->updatePaymentStatus();
        break;

    case 'order_delete':
    case 'order-delete':
    case 'order_hapus':
        $controller = new OrderController();
        $controller->delete();
        break;

    // Module: Pengembalian
    case 'pengembalian':
    case 'tb_pengembalian':
    case 'tbpengembalian':
    case 'return':
        $controller = new PengembalianController();
        $controller->index();
        break;

    case 'pengembalian_detail':
    case 'pengembalian-detail':
        $controller = new PengembalianController();
        $controller->detail();
        break;

    case 'pengembalian_create':
    case 'pengembalian-create':
    case 'pengembalian_tambah':
        $controller = new PengembalianController();
        $controller->create();
        break;

    case 'pengembalian_store':
    case 'pengembalian-store':
        $controller = new PengembalianController();
        $controller->store();
        break;

    case 'pengembalian_edit':
    case 'pengembalian-edit':
    case 'pengembalian_ubah':
        $controller = new PengembalianController();
        $controller->edit();
        break;

    case 'pengembalian_update':
    case 'pengembalian-update':
        $controller = new PengembalianController();
        $controller->update();
        break;

    case 'pengembalian_delete':
    case 'pengembalian-delete':
    case 'pengembalian_hapus':


    // Module: DetailTransaksi
    case 'detail_transaksi':
        $controller = new DetailTransaksiController();
        $controller->index();
        break;
    case 'detail_transaksi_create':
        $controller = new DetailTransaksiController();
        $controller->create();
        break;
    case 'detail_transaksi_store':
        $controller = new DetailTransaksiController();
        $controller->store();
        break;
    case 'detail_transaksi_detail':
        $controller = new DetailTransaksiController();
        $controller->detail();
        break;
    case 'detail_transaksi_edit':
        $controller = new DetailTransaksiController();
        $controller->edit();
        break;
    case 'detail_transaksi_update':
        $controller = new DetailTransaksiController();
        $controller->update();
        break;
    case 'detail_transaksi_delete':
        $controller = new DetailTransaksiController();
        $controller->delete();
        break;


    // Module: Customer
    case 'create':
    case 'tambah':
    case 'customer_create':
    case 'customer-create':
        $controller = new CustomerController();
        $controller->create();
        break;

    case 'store':
    case 'simpan':
    case 'customer_store':
    case 'customer-store':
        $controller = new CustomerController();
        $controller->store();
        break;

    case 'edit':
    case 'ubah':
    case 'customer_edit':
    case 'customer-edit':
        $controller = new CustomerController();
        $controller->edit();
        break;

    case 'update':
    case 'simpan_ubah':
    case 'customer_update':
    case 'customer-update':
        $controller = new CustomerController();
        $controller->update();
        break;

    case 'delete':
    case 'hapus':
    case 'customer_delete':
    case 'customer-delete':
        $controller = new CustomerController();
        $controller->delete();
        break;

    case 'detail':
    case 'customer_detail':
    case 'customer-detail':
        $controller = new CustomerController();
        $controller->detail();
        break;

    // Module: Customer
    case 'customer':
        $controller = new CustomerController();
        $controller->index();
        break;

    // Module: Dashboard (Default admin landing)
    case 'dashboard':
    default:
        $controller = new DashboardController();
        $controller->index();
        break;
}
