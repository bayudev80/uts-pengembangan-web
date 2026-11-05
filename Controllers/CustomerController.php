<?php

require_once __DIR__ . '/../model/CostumerModel.php';

class CustomerController
{
    private CustomerModel $model;

    public function __construct()
    {
        $this->model = new CustomerModel();
    }

    // Tampilkan daftar customer
    public function index()
    {
        $customers = $this->model->all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Customer/CustomerView.php';
    }

    // Tampilkan detail customer
    public function detail()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $customer = $this->model->find($id);

        if (!$customer) {
            header('Location: index.php?action=customer');
            exit;
        }

        include __DIR__ . '/../View/Customer/detail.php';
    }

    // Form tambah customer baru
    public function create()
    {
        $customer = null;
        $pageTitle = 'Tambah Customer';
        $formAction = 'index.php?action=store';

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Customer/edit.php';
    }

    // Simpan customer baru (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=customer');
            exit;
        }

        $nama = trim($_POST['nama_customer'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $ktp = trim($_POST['KTP'] ?? '');

        // Validasi kolom wajib diisi
        if ($nama === '' || $no_hp === '') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama dan nomor HP wajib diisi!'
            ];
            header('Location: index.php?action=create');
            exit;
        }

        // Validasi nama hanya huruf
        if (preg_match('/\d/', $nama)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama customer hanya boleh berupa huruf dan tidak boleh mengandung angka!'
            ];
            header('Location: index.php?action=create');
            exit;
        }

        // Validasi nomor HP hanya angka
        if (!preg_match('/^[0-9]+$/', $no_hp)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nomor HP hanya boleh berisi angka!'
            ];
            header('Location: index.php?action=create');
            exit;
        }

        // Proses upload foto identitas jika ada
        $ktpPath = $ktp;
        if (!empty($_FILES['foto_identitas']['name']) && $_FILES['foto_identitas']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['foto_identitas']['tmp_name'];
            $fileName = $_FILES['foto_identitas']['name'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowedExts, true)) {
                $uploadDir = __DIR__ . '/../uploads/identitas/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFileName = 'id_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                    $ktpPath = 'uploads/identitas/' . $newFileName;
                }
            }
        }

        // Simpan data
        $this->model->create([
            'nama_customer' => $nama,
            'no_hp'         => $no_hp,
            'KTP'           => $ktpPath
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Customer berhasil ditambahkan!'
        ];

        header('Location: index.php?action=customer');
        exit;
    }

    // Form edit customer
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $customer = $this->model->find($id);

        if (!$customer) {
            header('Location: index.php?action=customer');
            exit;
        }

        $pageTitle = 'Edit Customer';
        $formAction = 'index.php?action=update';

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Customer/edit.php';
    }

    // Simpan perubahan customer (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=customer');
            exit;
        }

        $id = isset($_POST['id_customer']) ? (int)$_POST['id_customer'] : 0;
        $nama = trim($_POST['nama_customer'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $ktp = trim($_POST['KTP'] ?? '');

        // Validasi input
        if ($id <= 0 || $nama === '' || $no_hp === '') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama dan nomor HP wajib diisi!'
            ];
            header('Location: index.php?action=edit&id=' . $id);
            exit;
        }

        // Validasi format input
        if (preg_match('/\d/', $nama)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama customer hanya boleh berupa huruf dan tidak boleh mengandung angka!'
            ];
            header('Location: index.php?action=edit&id=' . $id);
            exit;
        }

        if (!preg_match('/^[0-9]+$/', $no_hp)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nomor HP hanya boleh berisi angka!'
            ];
            header('Location: index.php?action=edit&id=' . $id);
            exit;
        }

        // Proses upload foto identitas baru jika ada
        $ktpPath = $ktp;
        if (!empty($_FILES['foto_identitas']['name']) && $_FILES['foto_identitas']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['foto_identitas']['tmp_name'];
            $fileName = $_FILES['foto_identitas']['name'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowedExts, true)) {
                $uploadDir = __DIR__ . '/../uploads/identitas/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFileName = 'id_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                    $ktpPath = 'uploads/identitas/' . $newFileName;
                }
            }
        }

        // Eksekusi update
        $this->model->update($id, [
            'nama_customer' => $nama,
            'no_hp'         => $no_hp,
            'KTP'           => $ktpPath
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data customer berhasil diubah!'
        ];

        header('Location: index.php?action=customer');
        exit;
    }

    // Hapus data customer
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $success = $this->model->delete($id);
            $_SESSION['flash'] = [
                'type' => $success ? 'success' : 'error',
                'message' => $success ? 'Data customer berhasil dihapus.' : 'Customer tidak dapat dihapus karena masih terkait data transaksi!'
            ];
        }

        header('Location: index.php?action=customer');
        exit;
    }
}
