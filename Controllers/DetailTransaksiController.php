<?php
require_once __DIR__ . '/../model/DetailTransaksiModel.php';

class DetailTransaksiController
{
    private DetailTransaksiModel $model;

    public function __construct()
    {
        $this->model = new DetailTransaksiModel();
    }

    // Daftar semua detail transaksi
    public function index()
    {
        $details = $this->model->all();
        $flash   = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/DetailTransaksi/index.php';
    }

    // Detail satu baris
    public function detail()
    {
        $id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $detail = $this->model->find($id);

        if (!$detail) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data detail transaksi tidak ditemukan!'];
            header('Location: index.php?action=detail_transaksi');
            exit;
        }

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/DetailTransaksi/detail.php';
    }

    // Form tambah
    public function create()
    {
        $item       = null;
        $pageTitle  = 'Tambah Detail Transaksi';
        $formAction = 'index.php?action=detail_transaksi_store';

        $orders = $this->model->getOrders();
        $dataJas = $this->model->getJas();

        // Jika datang dari halaman order tertentu
        $preOrder = isset($_GET['id_order']) ? (int)$_GET['id_order'] : null;

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/DetailTransaksi/edit.php';
    }

    // Simpan detail baru (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=detail_transaksi');
            exit;
        }

        $idOrder   = (int)($_POST['id_order'] ?? 0);
        $idJas     = !empty($_POST['id_jas']) ? (int)$_POST['id_jas'] : null;
        $namaJas   = trim($_POST['nama_jas'] ?? '');
        $ukuran    = trim($_POST['ukuran'] ?? '');
        $warna     = trim($_POST['warna'] ?? '');
        $hargaSewa = (float)($_POST['harga_sewa'] ?? 0);
        $lamaSewa  = max(1, (int)($_POST['lama_sewa'] ?? 1));
        $subtotal  = $hargaSewa * $lamaSewa;
        $keterangan = trim($_POST['keterangan'] ?? '');

        if ($idOrder <= 0 || $namaJas === '' || $ukuran === '' || $hargaSewa <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Order, Nama Jas, Ukuran, dan Harga Sewa wajib diisi!'];
            header('Location: index.php?action=detail_transaksi_create' . ($idOrder > 0 ? '&id_order=' . $idOrder : ''));
            exit;
        }

        $this->model->create([
            'id_order'   => $idOrder,
            'id_jas'     => $idJas,
            'nama_jas'   => $namaJas,
            'ukuran'     => $ukuran,
            'warna'      => $warna ?: null,
            'harga_sewa' => $hargaSewa,
            'lama_sewa'  => $lamaSewa,
            'subtotal'   => $subtotal,
            'keterangan' => $keterangan ?: null,
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Detail transaksi berhasil ditambahkan!'];
        header('Location: index.php?action=detail_transaksi');
        exit;
    }

    // Form edit
    public function edit()
    {
        $id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $item = $this->model->find($id);

        if (!$item) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data tidak ditemukan!'];
            header('Location: index.php?action=detail_transaksi');
            exit;
        }

        $pageTitle  = 'Edit Detail Transaksi #' . $id;
        $formAction = 'index.php?action=detail_transaksi_update';

        $orders  = $this->model->getOrders();
        $dataJas = $this->model->getJas();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/DetailTransaksi/edit.php';
    }

    // Simpan perubahan (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=detail_transaksi');
            exit;
        }

        $id        = (int)($_POST['id_detail'] ?? 0);
        $idOrder   = (int)($_POST['id_order'] ?? 0);
        $idJas     = !empty($_POST['id_jas']) ? (int)$_POST['id_jas'] : null;
        $namaJas   = trim($_POST['nama_jas'] ?? '');
        $ukuran    = trim($_POST['ukuran'] ?? '');
        $warna     = trim($_POST['warna'] ?? '');
        $hargaSewa = (float)($_POST['harga_sewa'] ?? 0);
        $lamaSewa  = max(1, (int)($_POST['lama_sewa'] ?? 1));
        $subtotal  = $hargaSewa * $lamaSewa;
        $keterangan = trim($_POST['keterangan'] ?? '');

        if ($id <= 0 || $idOrder <= 0 || $namaJas === '' || $ukuran === '' || $hargaSewa <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Semua kolom wajib diisi dengan benar!'];
            header('Location: index.php?action=detail_transaksi_edit&id=' . $id);
            exit;
        }

        $this->model->update($id, [
            'id_order'   => $idOrder,
            'id_jas'     => $idJas,
            'nama_jas'   => $namaJas,
            'ukuran'     => $ukuran,
            'warna'      => $warna ?: null,
            'harga_sewa' => $hargaSewa,
            'lama_sewa'  => $lamaSewa,
            'subtotal'   => $subtotal,
            'keterangan' => $keterangan ?: null,
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Detail transaksi berhasil diperbarui!'];
        header('Location: index.php?action=detail_transaksi');
        exit;
    }

    // Hapus
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $success = $this->model->delete($id);
            $_SESSION['flash'] = [
                'type'    => $success ? 'success' : 'error',
                'message' => $success ? 'Detail transaksi berhasil dihapus!' : 'Gagal menghapus data.',
            ];
        }

        header('Location: index.php?action=detail_transaksi');
        exit;
    }
}

