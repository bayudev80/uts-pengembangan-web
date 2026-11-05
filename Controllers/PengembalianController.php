<?php
require_once __DIR__ . '/../model/PengembalianModel.php';

class PengembalianController
{
    private PengembalianModel $model;

    public function __construct()
    {
        $this->model = new PengembalianModel();
    }

    // Tampilkan daftar pengembalian
    public function index()
    {
        $dataPengembalian = $this->model->all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Pengembalian/PengembalianView.php';
    }

    // Tampilkan detail pengembalian
    public function detail()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $pengembalian = $this->model->find($id);

        if (!$pengembalian) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data pengembalian tidak ditemukan!'
            ];
            header('Location: index.php?action=pengembalian');
            exit;
        }

        include __DIR__ . '/../View/Pengembalian/detail.php';
    }

    // Form input pengembalian jas baru
    public function create()
    {
        $pengembalian = null;
        $pageTitle = 'Catat Pengembalian Jas';
        $formAction = 'index.php?action=pengembalian_store';

        $orders = $this->model->getOrdersForReturn();
        $preselectedOrderId = isset($_GET['id_order']) ? (int)$_GET['id_order'] : 0;

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Pengembalian/edit.php';
    }

    // Simpan pengembalian baru (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $idOrder             = (int)($_POST['id_order'] ?? 0);
        $tanggalDikembalikan = trim($_POST['tanggal_dikembalikan'] ?? '');
        $kondisiJas          = trim($_POST['kondisi_jas'] ?? 'baik');
        $denda_raw           = trim($_POST['denda'] ?? '0');
        $catatan             = trim($_POST['catatan'] ?? '');

        if ($idOrder <= 0 || empty($tanggalDikembalikan)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'ID Order dan Tanggal Dikembalikan wajib diisi!'
            ];
            header('Location: index.php?action=pengembalian_create');
            exit;
        }

        // Validasi denda hanya angka
        if (!preg_match('/^[0-9]+$/', $denda_raw) || (float)$denda_raw < 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nominal denda hanya boleh berisi angka dan tidak boleh negatif!'
            ];
            header('Location: index.php?action=pengembalian_create');
            exit;
        }

        $denda = (float)$denda_raw;

        $success = $this->model->create([
            'id_order'             => $idOrder,
            'tanggal_dikembalikan' => $tanggalDikembalikan,
            'kondisi_jas'          => $kondisiJas,
            'denda'                => $denda,
            'catatan'              => $catatan
        ]);

        $_SESSION['flash'] = [
            'type' => $success ? 'success' : 'error',
            'message' => $success ? 'Pengembalian jas berhasil dicatat, status pesanan diperbarui, dan stok jas dipulihkan!' : 'Gagal mencatat pengembalian. Transaksi order tersebut mungkin sudah pernah dicatat pengembaliannya.'
        ];

        header('Location: index.php?action=pengembalian');
        exit;
    }

    // Form edit pengembalian
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $pengembalian = $this->model->find($id);

        if (!$pengembalian) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data pengembalian tidak ditemukan!'
            ];
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $pageTitle = 'Edit Pengembalian #' . $id;
        $formAction = 'index.php?action=pengembalian_update';

        $orders = $this->model->getOrdersForReturn();
        $preselectedOrderId = (int)$pengembalian['id_order'];

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Pengembalian/edit.php';
    }

    // Simpan perubahan pengembalian (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pengembalian');
            exit;
        }

        $id                  = (int)($_POST['id_pengembalian'] ?? 0);
        $idOrder             = (int)($_POST['id_order'] ?? 0);
        $tanggalDikembalikan = trim($_POST['tanggal_dikembalikan'] ?? '');
        $kondisiJas          = trim($_POST['kondisi_jas'] ?? 'baik');
        $denda_raw           = trim($_POST['denda'] ?? '0');
        $catatan             = trim($_POST['catatan'] ?? '');

        if ($id <= 0 || $idOrder <= 0 || empty($tanggalDikembalikan)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'ID Order dan Tanggal Dikembalikan wajib diisi!'
            ];
            header('Location: index.php?action=pengembalian_edit&id=' . $id);
            exit;
        }

        // Validasi denda hanya angka
        if (!preg_match('/^[0-9]+$/', $denda_raw) || (float)$denda_raw < 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nominal denda hanya boleh berisi angka dan tidak boleh negatif!'
            ];
            header('Location: index.php?action=pengembalian_edit&id=' . $id);
            exit;
        }

        $denda = (float)$denda_raw;

        $this->model->update($id, [
            'id_order'             => $idOrder,
            'tanggal_dikembalikan' => $tanggalDikembalikan,
            'kondisi_jas'          => $kondisiJas,
            'denda'                => $denda,
            'catatan'              => $catatan
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data pengembalian berhasil diperbarui!'
        ];

        header('Location: index.php?action=pengembalian');
        exit;
    }

    // Hapus data pengembalian
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $success = $this->model->delete($id);
            $_SESSION['flash'] = [
                'type' => $success ? 'success' : 'error',
                'message' => $success ? 'Data pengembalian berhasil dihapus!' : 'Gagal menghapus data pengembalian.'
            ];
        }

        header('Location: index.php?action=pengembalian');
        exit;
    }
}
