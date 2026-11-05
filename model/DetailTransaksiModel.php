<?php
require_once __DIR__ . '/../koneksi.php';

class DetailTransaksiModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua detail transaksi beserta relasi order dan customer
    public function all(): array
    {
        $sql = "SELECT 
                    dt.*,
                    o.tanggal_sewa,
                    o.tanggal_kembali,
                    o.status         AS status_order,
                    o.total_harga,
                    o.tipe_pembayaran,
                    o.metode_pembayaran,
                    o.status_pembayaran,
                    c.nama_customer,
                    c.no_hp          AS no_hp_customer
                FROM tb_detail_transaksi dt
                LEFT JOIN tb_order o    ON dt.id_order = o.id_order
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                ORDER BY dt.id_detail DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil detail transaksi berdasarkan ID
    public function find(int $id): ?array
    {
        $sql = "SELECT 
                    dt.*,
                    o.tanggal_sewa,
                    o.tanggal_kembali,
                    o.status         AS status_order,
                    o.total_harga,
                    o.tipe_pembayaran,
                    o.metode_pembayaran,
                    o.status_pembayaran,
                    o.nominal_bayar,
                    c.nama_customer,
                    c.no_hp          AS no_hp_customer,
                    c.KTP            AS ktp_customer,
                    j.foto           AS foto_jas,
                    j.kategori       AS kategori_jas
                FROM tb_detail_transaksi dt
                LEFT JOIN tb_order o    ON dt.id_order = o.id_order
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                LEFT JOIN tb_jas j      ON dt.id_jas = j.id_jas
                WHERE dt.id_detail = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Ambil semua detail berdasarkan id_order
    public function findByOrder(int $idOrder): array
    {
        $sql = "SELECT dt.*, j.foto AS foto_jas, j.kategori AS kategori_jas
                FROM tb_detail_transaksi dt
                LEFT JOIN tb_jas j ON dt.id_jas = j.id_jas
                WHERE dt.id_order = :id
                ORDER BY dt.id_detail ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idOrder]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah baris detail transaksi
    public function create(array $data): bool
    {
        $sql = "INSERT INTO tb_detail_transaksi
                    (id_order, id_jas, nama_jas, ukuran, warna, harga_sewa, lama_sewa, subtotal, keterangan)
                VALUES
                    (:id_order, :id_jas, :nama_jas, :ukuran, :warna, :harga_sewa, :lama_sewa, :subtotal, :keterangan)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_order'   => $data['id_order'],
            'id_jas'     => $data['id_jas'] ?? null,
            'nama_jas'   => $data['nama_jas'],
            'ukuran'     => $data['ukuran'],
            'warna'      => $data['warna'] ?? null,
            'harga_sewa' => $data['harga_sewa'],
            'lama_sewa'  => max(1, (int)($data['lama_sewa'] ?? 1)),
            'subtotal'   => $data['subtotal'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);
    }

    // Update detail transaksi
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE tb_detail_transaksi SET
                    id_order   = :id_order,
                    id_jas     = :id_jas,
                    nama_jas   = :nama_jas,
                    ukuran     = :ukuran,
                    warna      = :warna,
                    harga_sewa = :harga_sewa,
                    lama_sewa  = :lama_sewa,
                    subtotal   = :subtotal,
                    keterangan = :keterangan
                WHERE id_detail = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'         => $id,
            'id_order'   => $data['id_order'],
            'id_jas'     => $data['id_jas'] ?? null,
            'nama_jas'   => $data['nama_jas'],
            'ukuran'     => $data['ukuran'],
            'warna'      => $data['warna'] ?? null,
            'harga_sewa' => $data['harga_sewa'],
            'lama_sewa'  => max(1, (int)($data['lama_sewa'] ?? 1)),
            'subtotal'   => $data['subtotal'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);
    }

    // Hapus detail transaksi
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tb_detail_transaksi WHERE id_detail = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Daftar order untuk dropdown form
    public function getOrders(): array
    {
        $sql = "SELECT o.id_order, c.nama_customer, o.tanggal_sewa
                FROM tb_order o
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                ORDER BY o.id_order DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Daftar jas untuk dropdown form
    public function getJas(): array
    {
        $sql = "SELECT id_jas, Nama_jas, Ukuran_jas, Warna_jas, Harga_sewa
                FROM tb_jas ORDER BY Nama_jas ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

