<?php
require_once __DIR__ . '/../model/adminModel.php';

class adminController
{
    private adminModel $model;

    public function __construct()
    {
        $this->model = new adminModel();
    }

    // Tampilkan daftar admin
    public function index()
    {
        $admins = $this->model->all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/admin/admin.php';
    }

    // Tampilkan detail admin
    public function detail()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $admin = $this->model->find($id);

        if (!$admin) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data admin tidak ditemukan!'
            ];
            header('Location: index.php?action=admin');
            exit;
        }

        include __DIR__ . '/../View/admin/detail.php';
    }

    // Form tambah admin baru
    public function create()
    {
        $admin = null;
        $pageTitle = 'Tambah Admin Baru';
        $formAction = 'index.php?action=admin_store';

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/admin/edit.php';
    }

    // Simpan admin baru (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin');
            exit;
        }

        $nama  = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nama === '' || $email === '') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama dan Email admin wajib diisi!'
            ];
            header('Location: index.php?action=admin_create');
            exit;
        }

        // Validasi nama hanya huruf
        if (preg_match('/\d/', $nama)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama admin hanya boleh berupa huruf dan tidak boleh mengandung angka!'
            ];
            header('Location: index.php?action=admin_create');
            exit;
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Format alamat email tidak valid!'
            ];
            header('Location: index.php?action=admin_create');
            exit;
        }

        $this->model->create([
            'nama'  => $nama,
            'email' => $email
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Admin baru berhasil ditambahkan!'
        ];

        header('Location: index.php?action=admin');
        exit;
    }

    // Form edit admin
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $admin = $this->model->find($id);

        if (!$admin) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data admin tidak ditemukan!'
            ];
            header('Location: index.php?action=admin');
            exit;
        }

        $pageTitle = 'Edit Admin #' . $id;
        $formAction = 'index.php?action=admin_update';

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/admin/edit.php';
    }

    // Simpan perubahan admin (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin');
            exit;
        }

        $id    = (int)($_POST['id_admin'] ?? 0);
        $nama  = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($id <= 0 || $nama === '' || $email === '') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Semua kolom wajib diisi!'
            ];
            header('Location: index.php?action=admin_edit&id=' . $id);
            exit;
        }

        // Validasi nama hanya huruf
        if (preg_match('/\d/', $nama)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama admin hanya boleh berupa huruf dan tidak boleh mengandung angka!'
            ];
            header('Location: index.php?action=admin_edit&id=' . $id);
            exit;
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Format alamat email tidak valid!'
            ];
            header('Location: index.php?action=admin_edit&id=' . $id);
            exit;
        }

        $this->model->update($id, [
            'nama'  => $nama,
            'email' => $email
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data admin berhasil diperbarui!'
        ];

        header('Location: index.php?action=admin');
        exit;
    }

    // Hapus data admin
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $success = $this->model->delete($id);
            $_SESSION['flash'] = [
                'type' => $success ? 'success' : 'error',
                'message' => $success ? 'Data admin berhasil dihapus!' : 'Gagal menghapus data admin.'
            ];
        }

        header('Location: index.php?action=admin');
        exit;
    }
}
