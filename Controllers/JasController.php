<?php
require_once __DIR__ . '/../model/JasModel.php';

class JasController
{
    private JasModel $model;

    public function __construct()
    {
        $this->model = new JasModel();
    }

    // Tampilkan katalog jas
    public function index()
    {
        $dataJas = $this->model->all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Jas/JasView.php';
    }

    // Tampilkan detail jas
    public function detail()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $jas = $this->model->find($id);

        if (!$jas) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data jas tidak ditemukan!'
            ];
            header('Location: index.php?action=jas');
            exit;
        }

        include __DIR__ . '/../View/Jas/detail.php';
    }

    // Form tambah jas baru
    public function create()
    {
        $jas = null;
        $pageTitle = 'Tambah Koleksi Jas Baru';
        $formAction = 'index.php?action=jas_store';

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Jas/edit.php';
    }

    // Simpan jas baru (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=jas');
            exit;
        }

        $nama      = trim($_POST['Nama_jas'] ?? '');
        $kategori  = trim($_POST['kategori'] ?? 'Formal');
        $ukuran    = trim($_POST['Ukuran_jas'] ?? '');
        $warna     = trim($_POST['Warna_jas'] ?? '');
        $harga_raw = trim($_POST['Harga_sewa'] ?? '');
        $stok_raw  = trim($_POST['Stok'] ?? '');
        $foto      = trim($_POST['foto'] ?? '');

        if ($nama === '' || $ukuran === '' || $warna === '') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama Jas, Ukuran, dan Warna wajib diisi!'
            ];
            header('Location: index.php?action=jas_create');
            exit;
        }

        // Validasi warna hanya huruf
        if (preg_match('/\d/', $warna)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Warna jas hanya boleh berupa huruf dan tidak boleh mengandung angka!'
            ];
            header('Location: index.php?action=jas_create');
            exit;
        }

        // Validasi harga sewa hanya angka
        if (!preg_match('/^[0-9]+$/', $harga_raw) || (float)$harga_raw <= 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Harga sewa hanya boleh berisi angka dan harus lebih besar dari 0!'
            ];
            header('Location: index.php?action=jas_create');
            exit;
        }

        // Validasi stok hanya angka
        if (!preg_match('/^[0-9]+$/', $stok_raw) || (int)$stok_raw < 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Jumlah stok hanya boleh berisi angka dan tidak boleh negatif!'
            ];
            header('Location: index.php?action=jas_create');
            exit;
        }

        $harga = (float)$harga_raw;
        $stok  = (int)$stok_raw;

        $this->model->create([
            'Nama_jas'   => $nama,
            'kategori'   => $kategori,
            'foto'       => $foto,
            'Ukuran_jas' => $ukuran,
            'Warna_jas'  => $warna,
            'Harga_sewa' => $harga,
            'Stok'       => $stok
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Koleksi jas baru berhasil ditambahkan!'
        ];

        header('Location: index.php?action=jas');
        exit;
    }

    // Form edit jas
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $jas = $this->model->find($id);

        if (!$jas) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data jas tidak ditemukan!'
            ];
            header('Location: index.php?action=jas');
            exit;
        }

        $pageTitle = 'Edit Jas #' . $id;
        $formAction = 'index.php?action=jas_update';

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Jas/edit.php';
    }

    // Simpan perubahan data jas (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=jas');
            exit;
        }

        $id        = (int)($_POST['id_jas'] ?? 0);
        $nama      = trim($_POST['Nama_jas'] ?? '');
        $kategori  = trim($_POST['kategori'] ?? 'Formal');
        $ukuran    = trim($_POST['Ukuran_jas'] ?? '');
        $warna     = trim($_POST['Warna_jas'] ?? '');
        $harga_raw = trim($_POST['Harga_sewa'] ?? '');
        $stok_raw  = trim($_POST['Stok'] ?? '');
        $foto      = trim($_POST['foto'] ?? '');

        if ($id <= 0 || $nama === '' || $ukuran === '' || $warna === '') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Semua kolom bertanda bintang wajib diisi!'
            ];
            header('Location: index.php?action=jas_edit&id=' . $id);
            exit;
        }

        // Validasi warna hanya huruf
        if (preg_match('/\d/', $warna)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Warna jas hanya boleh berupa huruf dan tidak boleh mengandung angka!'
            ];
            header('Location: index.php?action=jas_edit&id=' . $id);
            exit;
        }

        // Validasi harga sewa hanya angka
        if (!preg_match('/^[0-9]+$/', $harga_raw) || (float)$harga_raw <= 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Harga sewa hanya boleh berisi angka dan harus lebih besar dari 0!'
            ];
            header('Location: index.php?action=jas_edit&id=' . $id);
            exit;
        }

        // Validasi stok hanya angka
        if (!preg_match('/^[0-9]+$/', $stok_raw) || (int)$stok_raw < 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Jumlah stok hanya boleh berisi angka dan tidak boleh negatif!'
            ];
            header('Location: index.php?action=jas_edit&id=' . $id);
            exit;
        }

        $harga = (float)$harga_raw;
        $stok  = (int)$stok_raw;

        $this->model->update($id, [
            'Nama_jas'   => $nama,
            'kategori'   => $kategori,
            'foto'       => $foto,
            'Ukuran_jas' => $ukuran,
            'Warna_jas'  => $warna,
            'Harga_sewa' => $harga,
            'Stok'       => $stok
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data jas berhasil diperbarui!'
        ];

        header('Location: index.php?action=jas');
        exit;
    }

    // Hapus data jas
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $success = $this->model->delete($id);
            $_SESSION['flash'] = [
                'type' => $success ? 'success' : 'error',
                'message' => $success ? 'Data jas berhasil dihapus!' : 'Jas tidak dapat dihapus karena masih terkait data transaksi order!'
            ];
        }

        header('Location: index.php?action=jas');
        exit;
    }
}
