<?php
require_once __DIR__ . '/../model/OrderModel.php';

class OrderController
{
    private OrderModel $model;

    public function __construct()
    {
        $this->model = new OrderModel();
    }

    // Tampilkan daftar order
    public function index()
    {
        $orders = $this->model->all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Order/OrderView.php';
    }

    // Tampilkan detail order
    public function detail()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $order = $this->model->find($id);

        if (!$order) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data order tidak ditemukan!'
            ];
            header('Location: index.php?action=order');
            exit;
        }

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Order/detail.php';
    }

    // Form tambah order baru
    public function create()
    {
        $order = null;
        $pageTitle = 'Tambah Order Baru';
        $formAction = 'index.php?action=order_store';

        $customers = $this->model->getCustomers();
        $dataJas = $this->model->getJas();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Order/edit.php';
    }

    // Simpan order baru (POST)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=order');
            exit;
        }

        $idCustomer     = (int)($_POST['id_customer'] ?? 0);
        $idJas          = !empty($_POST['id_jas']) ? (int)$_POST['id_jas'] : null;
        $ukuranSewa     = trim($_POST['ukuran_sewa'] ?? '');
        $tanggalSewa    = trim($_POST['tanggal_sewa'] ?? '');
        $tanggalKembali = trim($_POST['tanggal_kembali'] ?? '');
        $status         = trim($_POST['status'] ?? 'diproses');
        $total_raw      = trim($_POST['total_harga'] ?? '');

        if ($idCustomer <= 0 || empty($tanggalSewa) || empty($tanggalKembali)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Customer, Tanggal Sewa, dan Tanggal Kembali wajib diisi!'
            ];
            header('Location: index.php?action=order_create');
            exit;
        }

        // Validasi tanggal pengembalian
        if ($tanggalKembali < $tanggalSewa) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Tanggal pengembalian tidak boleh lebih awal dari tanggal sewa!'
            ];
            header('Location: index.php?action=order_create');
            exit;
        }

        // Validasi total biaya sewa hanya angka
        if (!preg_match('/^[0-9]+$/', $total_raw) || (float)$total_raw < 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Total biaya sewa hanya boleh berisi angka dan tidak boleh negatif!'
            ];
            header('Location: index.php?action=order_create');
            exit;
        }

        $totalHarga = (float)$total_raw;
        $tipeBayar  = trim($_POST['tipe_pembayaran'] ?? 'lunas');
        $metode     = trim($_POST['metode_pembayaran'] ?? 'toko');
        $statusByr  = trim($_POST['status_pembayaran'] ?? 'menunggu_konfirmasi');
        $nominal    = !empty($_POST['nominal_bayar']) ? (float)$_POST['nominal_bayar'] : (($tipeBayar === 'dp') ? ceil($totalHarga * 0.5) : $totalHarga);

        $created = $this->model->create([
            'id_customer'       => $idCustomer,
            'id_jas'            => $idJas,
            'ukuran_sewa'       => $ukuranSewa,
            'id_user'           => null,
            'tanggal_sewa'      => $tanggalSewa,
            'tanggal_kembali'   => $tanggalKembali,
            'status'            => $status,
            'total_harga'       => $totalHarga,
            'tipe_pembayaran'   => $tipeBayar,
            'metode_pembayaran' => $metode,
            'nominal_bayar'     => $nominal,
            'status_pembayaran' => $statusByr
        ]);

        if ($created && $idJas > 0) {
            require_once __DIR__ . '/../model/JasModel.php';
            $jasModel = new JasModel();
            $jasModel->decreaseStock($idJas, 1);
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Order berhasil ditambahkan!'
        ];

        header('Location: index.php?action=order');
        exit;
    }

    // Form edit order
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $order = $this->model->find($id);

        if (!$order) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data order tidak ditemukan!'
            ];
            header('Location: index.php?action=order');
            exit;
        }

        $pageTitle = 'Edit Order #' . $id;
        $formAction = 'index.php?action=order_update';

        $customers = $this->model->getCustomers();
        $dataJas = $this->model->getJas();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/Order/edit.php';
    }

    // Simpan perubahan order (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=order');
            exit;
        }

        $id             = (int)($_POST['id_order'] ?? 0);
        $idCustomer     = (int)($_POST['id_customer'] ?? 0);
        $idJas          = !empty($_POST['id_jas']) ? (int)$_POST['id_jas'] : null;
        $ukuranSewa     = trim($_POST['ukuran_sewa'] ?? '');
        $tanggalSewa    = trim($_POST['tanggal_sewa'] ?? '');
        $tanggalKembali = trim($_POST['tanggal_kembali'] ?? '');
        $status         = trim($_POST['status'] ?? 'diproses');
        $total_raw      = trim($_POST['total_harga'] ?? '');

        if ($id <= 0 || $idCustomer <= 0 || empty($tanggalSewa) || empty($tanggalKembali)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Kolom Customer dan Tanggal tidak boleh kosong!'
            ];
            header('Location: index.php?action=order_edit&id=' . $id);
            exit;
        }

        // Validasi tanggal pengembalian
        if ($tanggalKembali < $tanggalSewa) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Tanggal pengembalian tidak boleh lebih awal dari tanggal sewa!'
            ];
            header('Location: index.php?action=order_edit&id=' . $id);
            exit;
        }

        // Validasi total biaya sewa hanya angka
        if (!preg_match('/^[0-9]+$/', $total_raw) || (float)$total_raw < 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Total biaya sewa hanya boleh berisi angka dan tidak boleh negatif!'
            ];
            header('Location: index.php?action=order_edit&id=' . $id);
            exit;
        }

        $totalHarga = (float)$total_raw;
        $tipeBayar  = trim($_POST['tipe_pembayaran'] ?? 'lunas');
        $metode     = trim($_POST['metode_pembayaran'] ?? 'toko');
        $statusByr  = trim($_POST['status_pembayaran'] ?? 'menunggu_konfirmasi');
        $nominal    = !empty($_POST['nominal_bayar']) ? (float)$_POST['nominal_bayar'] : (($tipeBayar === 'dp') ? ceil($totalHarga * 0.5) : $totalHarga);

        $this->model->update($id, [
            'id_customer'       => $idCustomer,
            'id_jas'            => $idJas,
            'ukuran_sewa'       => $ukuranSewa,
            'id_user'           => null,
            'tanggal_sewa'      => $tanggalSewa,
            'tanggal_kembali'   => $tanggalKembali,
            'status'            => $status,
            'total_harga'       => $totalHarga,
            'tipe_pembayaran'   => $tipeBayar,
            'metode_pembayaran' => $metode,
            'nominal_bayar'     => $nominal,
            'status_pembayaran' => $statusByr
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data order berhasil diperbarui!'
        ];

        header('Location: index.php?action=order');
        exit;
    }

    // Verifikasi dan ubah status transaksi order
    public function updateStatus()
    {
        $id = (int)($_POST['id_order'] ?? $_GET['id'] ?? 0);
        $status = strtolower(trim($_POST['status'] ?? $_GET['status'] ?? ''));
        $redirect = trim($_GET['redirect'] ?? $_POST['redirect'] ?? '');

        $validStatuses = ['diproses', 'disewa', 'selesai', 'dikembalikan'];

        if ($id <= 0 || !in_array($status, $validStatuses, true)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'ID order atau status transaksi tidak valid!'
            ];
            header('Location: index.php?action=order');
            exit;
        }

        $order = $this->model->find($id);
        if (!$order) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Pesanan order tidak ditemukan!'
            ];
            header('Location: index.php?action=order');
            exit;
        }

        $success = $this->model->updateStatus($id, $status);

        $statusLabels = [
            'diproses'     => 'Diproses (Menunggu Verifikasi)',
            'disewa'       => 'Disewa (Terverifikasi & Aktif)',
            'selesai'      => 'Selesai',
            'dikembalikan' => 'Dikembalikan'
        ];

        if ($success) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Status pesanan #{$id} berhasil diubah menjadi: " . ($statusLabels[$status] ?? $status) . "!"
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Gagal memperbarui status pesanan #{$id}."
            ];
        }

        if ($redirect === 'detail') {
            header('Location: index.php?action=order_detail&id=' . $id);
        } else {
            header('Location: index.php?action=order');
        }
        exit;
    }

    // Update status pembayaran oleh admin
    public function updatePaymentStatus()
    {
        $id = (int)($_POST['id_order'] ?? 0);
        $statusPembayaran = trim($_POST['status_pembayaran'] ?? '');
        $redirect = trim($_GET['redirect'] ?? $_POST['redirect'] ?? '');

        $valid = ['menunggu_konfirmasi', 'dp_dibayar', 'lunas', 'belum_bayar'];
        if ($id > 0 && in_array($statusPembayaran, $valid, true)) {
            $this->model->updatePaymentStatus($id, $statusPembayaran);
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Status pembayaran pesanan #{$id} berhasil diperbarui!"
            ];
        }

        if ($redirect === 'detail') {
            header('Location: index.php?action=order_detail&id=' . $id);
        } else {
            header('Location: index.php?action=order');
        }
        exit;
    }

    // Hapus data order
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $order = $this->model->find($id);
            $success = $this->model->delete($id);

            if ($success) {
                // Jika order aktif dibatalkan/dihapus, kembalikan stok jas ke katalog
                if ($order && !empty($order['id_jas']) && in_array(strtolower($order['status'] ?? ''), ['diproses', 'disewa'])) {
                    require_once __DIR__ . '/../model/JasModel.php';
                    $jasModel = new JasModel();
                    $jasModel->increaseStock((int)$order['id_jas'], 1);
                }

                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Order berhasil dihapus dan stok jas dipulihkan!'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Order tidak dapat dihapus karena sudah memiliki riwayat pengembalian jas!'
                ];
            }
        }

        header('Location: index.php?action=order');
        exit;
    }
}
