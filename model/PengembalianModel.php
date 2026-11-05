<?php
require_once __DIR__ . '/../koneksi.php';

class PengembalianModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua data pengembalian beserta relasi
    public function all(): array
    {
        $sql = "SELECT 
                    p.*,
                    o.tanggal_sewa,
                    o.tanggal_kembali AS batas_kembali,
                    o.total_harga AS biaya_sewa,
                    o.status AS status_order,
                    c.nama_customer,
                    c.no_hp AS no_hp_customer,
                    j.Nama_jas,
                    j.Ukuran_jas,
                    j.Warna_jas
                FROM tb_pengembalian p
                JOIN tb_order o ON p.id_order = o.id_order
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                LEFT JOIN tb_jas j ON o.id_jas = j.id_jas
                ORDER BY p.id_pengembalian DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari data pengembalian berdasarkan ID
    public function find($id): ?array
    {
        $sql = "SELECT 
                    p.*,
                    o.tanggal_sewa,
                    o.tanggal_kembali AS batas_kembali,
                    o.total_harga AS biaya_sewa,
                    o.status AS status_order,
                    c.nama_customer,
                    c.no_hp AS no_hp_customer,
                    c.KTP AS ktp_customer,
                    j.Nama_jas,
                    j.Ukuran_jas,
                    j.Warna_jas
                FROM tb_pengembalian p
                JOIN tb_order o ON p.id_order = o.id_order
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                LEFT JOIN tb_jas j ON o.id_jas = j.id_jas
                WHERE p.id_pengembalian = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Simpan data pengembalian, pulihkan stok jas, dan update status order ke 'dikembalikan'
    public function create(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            // Cegah duplikasi pengembalian untuk order yang sama
            $checkExisting = $this->db->prepare("SELECT COUNT(*) FROM tb_pengembalian WHERE id_order = :id_order");
            $checkExisting->execute(['id_order' => (int)$data['id_order']]);
            if ((int)$checkExisting->fetchColumn() > 0) {
                $this->db->rollBack();
                return false;
            }

            $sql = "INSERT INTO tb_pengembalian (id_order, tanggal_dikembalikan, kondisi_jas, denda, catatan)
                    VALUES (:id_order, :tanggal_dikembalikan, :kondisi_jas, :denda, :catatan)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_order'             => $data['id_order'],
                'tanggal_dikembalikan' => $data['tanggal_dikembalikan'],
                'kondisi_jas'          => $data['kondisi_jas'] ?? 'baik',
                'denda'                => $data['denda'] ?? 0,
                'catatan'              => $data['catatan'] ?? ''
            ]);

            // Update status transaksi order menjadi 'dikembalikan'
            $updateOrder = $this->db->prepare("UPDATE tb_order SET status = 'dikembalikan' WHERE id_order = :id_order");
            $updateOrder->execute(['id_order' => $data['id_order']]);

            // Tambah kembali stok jas fisik jika jas tidak hilang
            $kondisi = strtolower($data['kondisi_jas'] ?? 'baik');
            if ($kondisi !== 'hilang') {
                $orderStmt = $this->db->prepare("SELECT id_jas FROM tb_order WHERE id_order = :id_order");
                $orderStmt->execute(['id_order' => $data['id_order']]);
                $orderJasId = $orderStmt->fetchColumn();
                if ($orderJasId) {
                    $updateStock = $this->db->prepare("UPDATE tb_jas SET Stok = Stok + 1 WHERE id_jas = :id_jas");
                    $updateStock->execute(['id_jas' => $orderJasId]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Update data pengembalian
    public function update($id, array $data): bool
    {
        $sql = "UPDATE tb_pengembalian SET 
                    id_order             = :id_order,
                    tanggal_dikembalikan = :tanggal_dikembalikan,
                    kondisi_jas          = :kondisi_jas,
                    denda                = :denda,
                    catatan              = :catatan
                WHERE id_pengembalian = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'                   => $id,
            'id_order'             => $data['id_order'],
            'tanggal_dikembalikan' => $data['tanggal_dikembalikan'],
            'kondisi_jas'          => $data['kondisi_jas'],
            'denda'                => $data['denda'],
            'catatan'              => $data['catatan']
        ]);
    }

    // Hapus data pengembalian
    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tb_pengembalian WHERE id_pengembalian = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Daftar order untuk opsi form pengembalian (termasuk status pengembalian sebelumnya)
    public function getOrdersForReturn(): array
    {
        $sql = "SELECT 
                    o.id_order,
                    o.tanggal_sewa,
                    o.tanggal_kembali,
                    o.status,
                    c.nama_customer,
                    j.Nama_jas,
                    p.id_pengembalian AS id_pengembalian_exist
                FROM tb_order o
                LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                LEFT JOIN tb_jas j ON o.id_jas = j.id_jas
                LEFT JOIN tb_pengembalian p ON o.id_order = p.id_order
                ORDER BY o.id_order DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
