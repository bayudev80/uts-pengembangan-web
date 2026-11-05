<?php
require_once __DIR__ . '/../koneksi.php';

class OrderModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua data order beserta relasi customer dan jas
    public function all(): array
    {
        $sql = "SELECT 
                    o.*,
                    c.nama_customer,
                    c.no_hp AS no_hp_customer,
                    c.KTP AS ktp_customer,
                    j.Nama_jas,
                    j.Ukuran_jas,
                    j.Warna_jas,
                    j.Harga_sewa,
                    u.username
                FROM tb_order o
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                LEFT JOIN tb_jas j ON o.id_jas = j.id_jas
                LEFT JOIN tb_user u ON o.id_user = u.id
                ORDER BY o.id_order DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari data order berdasarkan ID
    public function find($id): ?array
    {
        $sql = "SELECT 
                    o.*,
                    c.nama_customer,
                    c.no_hp AS no_hp_customer,
                    c.KTP AS ktp_customer,
                    j.Nama_jas,
                    j.Ukuran_jas,
                    j.Warna_jas,
                    j.Harga_sewa,
                    u.username
                FROM tb_order o
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                LEFT JOIN tb_jas j ON o.id_jas = j.id_jas
                LEFT JOIN tb_user u ON o.id_user = u.id
                WHERE o.id_order = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Tambah order baru
    public function create(array $data): bool
    {
        $sql = "INSERT INTO tb_order (id_customer, id_jas, ukuran_sewa, id_user, tanggal_sewa, tanggal_kembali, status, total_harga, tipe_pembayaran, metode_pembayaran, nominal_bayar, status_pembayaran)
                VALUES (:id_customer, :id_jas, :ukuran_sewa, :id_user, :tanggal_sewa, :tanggal_kembali, :status, :total_harga, :tipe_pembayaran, :metode_pembayaran, :nominal_bayar, :status_pembayaran)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_customer'       => $data['id_customer'],
            'id_jas'            => !empty($data['id_jas']) ? $data['id_jas'] : null,
            'ukuran_sewa'       => !empty($data['ukuran_sewa']) ? $data['ukuran_sewa'] : null,
            'id_user'           => !empty($data['id_user']) ? $data['id_user'] : null,
            'tanggal_sewa'      => $data['tanggal_sewa'],
            'tanggal_kembali'   => $data['tanggal_kembali'],
            'status'            => $data['status'] ?? 'diproses',
            'total_harga'       => $data['total_harga'] ?? 0,
            'tipe_pembayaran'   => $data['tipe_pembayaran'] ?? 'lunas',
            'metode_pembayaran' => $data['metode_pembayaran'] ?? 'toko',
            'nominal_bayar'     => $data['nominal_bayar'] ?? ($data['total_harga'] ?? 0),
            'status_pembayaran' => $data['status_pembayaran'] ?? 'menunggu_konfirmasi'
        ]);
    }

    // Update data order
    public function update($id, array $data): bool
    {
        $sql = "UPDATE tb_order SET 
                    id_customer       = :id_customer,
                    id_jas            = :id_jas,
                    ukuran_sewa       = :ukuran_sewa,
                    id_user           = :id_user,
                    tanggal_sewa      = :tanggal_sewa,
                    tanggal_kembali   = :tanggal_kembali,
                    status            = :status,
                    total_harga       = :total_harga,
                    tipe_pembayaran   = :tipe_pembayaran,
                    metode_pembayaran = :metode_pembayaran,
                    nominal_bayar     = :nominal_bayar,
                    status_pembayaran = :status_pembayaran
                WHERE id_order = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'                => $id,
            'id_customer'       => $data['id_customer'],
            'id_jas'            => !empty($data['id_jas']) ? $data['id_jas'] : null,
            'ukuran_sewa'       => !empty($data['ukuran_sewa']) ? $data['ukuran_sewa'] : null,
            'id_user'           => !empty($data['id_user']) ? $data['id_user'] : null,
            'tanggal_sewa'      => $data['tanggal_sewa'],
            'tanggal_kembali'   => $data['tanggal_kembali'],
            'status'            => $data['status'],
            'total_harga'       => $data['total_harga'],
            'tipe_pembayaran'   => $data['tipe_pembayaran'] ?? 'lunas',
            'metode_pembayaran' => $data['metode_pembayaran'] ?? 'toko',
            'nominal_bayar'     => $data['nominal_bayar'] ?? ($data['total_harga'] ?? 0),
            'status_pembayaran' => $data['status_pembayaran'] ?? 'menunggu_konfirmasi'
        ]);
    }

    // Ubah status order / transaksi
    public function updateStatus($id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE tb_order SET status = :status WHERE id_order = :id");
        return $stmt->execute([
            'id'     => (int)$id,
            'status' => $status
        ]);
    }

    // Ubah status pembayaran order
    public function updatePaymentStatus($id, string $statusPembayaran, ?float $nominalBayar = null): bool
    {
        if ($nominalBayar !== null) {
            $stmt = $this->db->prepare("UPDATE tb_order SET status_pembayaran = :sp, nominal_bayar = :nb WHERE id_order = :id");
            return $stmt->execute([
                'id' => (int)$id,
                'sp' => $statusPembayaran,
                'nb' => $nominalBayar
            ]);
        }

        $stmt = $this->db->prepare("UPDATE tb_order SET status_pembayaran = :sp WHERE id_order = :id");
        return $stmt->execute([
            'id' => (int)$id,
            'sp' => $statusPembayaran
        ]);
    }

    // Hapus data order (dicegah jika terkait data pengembalian)
    public function delete($id): bool
    {
        try {
            $check = $this->db->prepare("SELECT COUNT(*) FROM tb_pengembalian WHERE id_order = :id");
            $check->execute(['id' => (int)$id]);
            if ((int)$check->fetchColumn() > 0) {
                return false;
            }

            $stmt = $this->db->prepare("DELETE FROM tb_order WHERE id_order = :id");
            return $stmt->execute(['id' => (int)$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Daftar customer untuk opsi form
    public function getCustomers(): array
    {
        $stmt = $this->db->query("SELECT id_customer, nama_customer FROM tb_customer ORDER BY nama_customer ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Daftar jas untuk opsi form
    public function getJas(): array
    {
        $stmt = $this->db->query("SELECT id_jas, Nama_jas, Ukuran_jas, Warna_jas, Harga_sewa, Stok FROM tb_jas ORDER BY Nama_jas ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah order baru dan kembalikan ID
    public function createGetId(array $data): int
    {
        $this->create($data);
        return (int)$this->db->lastInsertId();
    }

    // Hitung estimasi total sewa dan denda (durasi sewa > 3 hari kena denda Rp 50.000/hari)
    public function calculateTotal($id_jas, $tgl_sewa, $tgl_kembali): float
    {
        $stmt = $this->db->prepare("SELECT Harga_sewa FROM tb_jas WHERE id_jas = :id");
        $stmt->execute(['id' => (int)$id_jas]);
        $jas = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$jas) return 0;

        $harga = (float)$jas['Harga_sewa'];
        $t1 = strtotime($tgl_sewa);
        $t2 = strtotime($tgl_kembali);

        if (!$t1 || !$t2 || $t2 < $t1) {
            return $harga;
        }

        $lama = floor(($t2 - $t1) / 86400) + 1;
        $lama = max(1, $lama);

        $denda = 0;
        if ($lama > 3) {
            $denda = ($lama - 3) * 50000;
        }

        return $harga + $denda;
    }
}
