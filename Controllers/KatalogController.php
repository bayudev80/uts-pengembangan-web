<?php
require_once __DIR__ . '/../model/JasModel.php';
require_once __DIR__ . '/../model/CostumerModel.php';
require_once __DIR__ . '/../model/OrderModel.php';

class KatalogController
{
    private JasModel $jasModel;
    private CustomerModel $customerModel;
    private OrderModel $orderModel;

    public function __construct()
    {
        $this->jasModel = new JasModel();
        $this->customerModel = new CustomerModel();
        $this->orderModel = new OrderModel();
    }

    // Tampilkan katalog jas untuk customer
    public function index()
    {
        $search = trim($_GET['cari'] ?? '');
        $selectedKategori = trim($_GET['kategori'] ?? '');
        $jasList = $this->jasModel->getAvailable($search, $selectedKategori);
        $totalJas = count($jasList);
        $categories = $this->jasModel->getCategories();
        $allJasOptions = $this->jasModel->getAvailable();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $bookingSuccess = $_SESSION['booking_success'] ?? null;
        unset($_SESSION['booking_success']);

        include __DIR__ . '/../View/katalog/index.php';
    }

    // Proses formulir sewa jas oleh customer (POST)
    public function sewa()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=katalog');
            exit;
        }

        $nama              = trim($_POST['nama_customer'] ?? '');
        $hp                = trim($_POST['no_hp'] ?? '');
        $id_jas            = (int)($_POST['id_jas'] ?? 0);
        $ukuran_sewa       = trim($_POST['ukuran_sewa'] ?? '');
        $tanggal_sewa      = trim($_POST['tanggal_sewa'] ?? '');
        $tanggal_kembali   = trim($_POST['tanggal_kembali'] ?? '');
        $tipe_pembayaran   = strtolower(trim($_POST['tipe_pembayaran'] ?? 'lunas'));
        $metode_pembayaran = strtolower(trim($_POST['metode_pembayaran'] ?? 'toko'));

        if (!in_array($tipe_pembayaran, ['lunas', 'dp'])) {
            $tipe_pembayaran = 'lunas';
        }
        if (!in_array($metode_pembayaran, ['qris', 'transfer', 'toko'])) {
            $metode_pembayaran = 'toko';
        }

        // Validasi kelengkapan form dasar
        if (empty($nama) || empty($hp) || empty($tanggal_sewa) || empty($tanggal_kembali) || $id_jas <= 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Harap lengkapi seluruh kolom formulir penyewaan!'
            ];
            header('Location: index.php?action=katalog#form-sewa');
            exit;
        }

        // Validasi nama hanya huruf
        if (preg_match('/\d/', $nama)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nama customer hanya boleh berupa huruf dan tidak boleh mengandung angka!'
            ];
            header('Location: index.php?action=katalog#form-sewa');
            exit;
        }

        // Validasi nomor HP hanya angka
        if (!preg_match('/^[0-9]+$/', $hp)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Nomor HP hanya boleh berisi angka!'
            ];
            header('Location: index.php?action=katalog#form-sewa');
            exit;
        }

        // Upload foto kartu identitas jaminan (KTP / SIM / Kartu Pelajar)
        $fotoIdentitasPath = '';
        if (!empty($_FILES['foto_identitas']['name']) && $_FILES['foto_identitas']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['foto_identitas']['tmp_name'];
            $fileName = $_FILES['foto_identitas']['name'];
            $fileSize = $_FILES['foto_identitas']['size'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowedExts, true)) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Format foto kartu identitas harus berupa file gambar JPG, JPEG, PNG, atau WEBP!'
                ];
                header('Location: index.php?action=katalog#form-sewa');
                exit;
            }

            if ($fileSize > 5 * 1024 * 1024) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Ukuran foto kartu identitas terlalu besar! Maksimal 5 MB.'
                ];
                header('Location: index.php?action=katalog#form-sewa');
                exit;
            }

            $uploadDir = __DIR__ . '/../uploads/identitas/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = 'id_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $destPath)) {
                $fotoIdentitasPath = 'uploads/identitas/' . $newFileName;
            }
        }

        // Cek customer berdasarkan nomor HP
        $existingCust = $this->customerModel->findByKtpOrPhone('', $hp);

        // Jika tidak upload foto baru, gunakan foto identitas yang tersimpan sebelumnya (jika ada)
        if (empty($fotoIdentitasPath)) {
            if ($existingCust && !empty($existingCust['KTP'])) {
                $fotoIdentitasPath = $existingCust['KTP'];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Silakan upload foto kartu identitas (KTP / SIM / Kartu Pelajar) sebagai jaminan sewa!'
                ];
                header('Location: index.php?action=katalog#form-sewa');
                exit;
            }
        }

        // Validasi urutan tanggal
        if ($tanggal_kembali < $tanggal_sewa) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Tanggal pengembalian tidak boleh lebih awal dari tanggal sewa!'
            ];
            header('Location: index.php?action=katalog#form-sewa');
            exit;
        }

        // Validasi ketersediaan stok jas
        $jas = $this->jasModel->find($id_jas);
        if (!$jas || (int)$jas['Stok'] <= 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Maaf, stok untuk model jas pilihan Anda saat ini sedang habis!'
            ];
            header('Location: index.php?action=katalog#form-sewa');
            exit;
        }

        // Simpan / update data customer dengan foto identitas
        if ($existingCust) {
            $id_customer = (int)$existingCust['id_customer'];
            $this->customerModel->update($id_customer, [
                'nama_customer' => $nama,
                'no_hp'         => $hp,
                'KTP'           => $fotoIdentitasPath
            ]);
        } else {
            $id_customer = $this->customerModel->insertGetId([
                'nama_customer' => $nama,
                'no_hp'         => $hp,
                'KTP'           => $fotoIdentitasPath
            ]);
        }

        // Hitung total sewa dan denda
        $total = $this->orderModel->calculateTotal($id_jas, $tanggal_sewa, $tanggal_kembali);

        // Skema pembayaran (DP 50% atau Lunas 100%)
        $nominalBayar = ($tipe_pembayaran === 'dp') ? (float)ceil($total * 0.5) : (float)$total;
        $statusPembayaran = ($metode_pembayaran === 'toko') ? 'belum_bayar' : 'menunggu_konfirmasi';

        // Buat pesanan baru
        $ukuranFix = !empty($ukuran_sewa) ? $ukuran_sewa : ($jas['Ukuran_jas'] ?? 'M');
        $orderId = $this->orderModel->createGetId([
            'id_customer'       => $id_customer,
            'id_jas'            => $id_jas,
            'ukuran_sewa'       => $ukuranFix,
            'id_user'           => null,
            'tanggal_sewa'      => $tanggal_sewa,
            'tanggal_kembali'   => $tanggal_kembali,
            'status'            => 'diproses',
            'total_harga'       => $total,
            'tipe_pembayaran'   => $tipe_pembayaran,
            'metode_pembayaran' => $metode_pembayaran,
            'nominal_bayar'     => $nominalBayar,
            'status_pembayaran' => $statusPembayaran
        ]);

        if ($orderId > 0) {
            // Kurangi stok jas
            $this->jasModel->decreaseStock($id_jas, 1);

            $msg = "Selamat! Penyewaan jas <strong>" . htmlspecialchars($jas['Nama_jas']) . "</strong> (Ukuran: " . htmlspecialchars($ukuranFix) . ") berhasil diproses. "
                 . "Total tagihan: <strong>" . formatRupiah($total) . "</strong> (" . strtoupper($tipe_pembayaran) . ": " . formatRupiah($nominalBayar) . "). Tim kami akan segera menghubungi WhatsApp Anda (" . htmlspecialchars($hp) . ").";
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => $msg
            ];

            // Data pop up notifikasi pelanggan
            $_SESSION['booking_success'] = [
                'order_id'          => $orderId,
                'nama_customer'     => $nama,
                'no_hp'             => $hp,
                'nama_jas'          => $jas['Nama_jas'],
                'ukuran_jas'        => $ukuranFix,
                'warna_jas'         => $jas['Warna_jas'] ?? '',
                'tanggal_sewa'      => $tanggal_sewa,
                'tanggal_kembali'   => $tanggal_kembali,
                'total_harga'       => $total,
                'tipe_pembayaran'   => $tipe_pembayaran,
                'metode_pembayaran' => $metode_pembayaran,
                'nominal_bayar'     => $nominalBayar,
                'sisa_bayar'        => max(0, $total - $nominalBayar),
                'status_pembayaran' => $statusPembayaran
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Gagal memproses penyewaan. Silakan coba lagi.'
            ];
        }

        header('Location: index.php?action=katalog#form-sewa');
        exit;
    }
}

