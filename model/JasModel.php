<?php
require_once __DIR__ . '/../koneksi.php';

class JasModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua data jas
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM tb_jas ORDER BY id_jas DESC");
        return $stmt->fetchAll();
    }

    // Cari jas berdasarkan ID
    public function find($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_jas WHERE id_jas = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // Tambah jas baru
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO tb_jas (Nama_jas, foto, kategori, Ukuran_jas, Warna_jas, Harga_sewa, Stok) 
                                    VALUES (:nama, :foto, :kategori, :ukuran, :warna, :harga, :stok)");
        return $stmt->execute([
            'nama'     => $data['Nama_jas'],
            'foto'     => $data['foto'] ?? '',
            'kategori' => $data['kategori'] ?? 'Formal',
            'ukuran'   => $data['Ukuran_jas'],
            'warna'    => $data['Warna_jas'],
            'harga'    => $data['Harga_sewa'],
            'stok'     => $data['Stok']
        ]);
    }

    // Update data jas
    public function update($id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE tb_jas SET 
                                    Nama_jas = :nama, 
                                    foto = :foto, 
                                    kategori = :kategori, 
                                    Ukuran_jas = :ukuran, 
                                    Warna_jas = :warna, 
                                    Harga_sewa = :harga, 
                                    Stok = :stok 
                                    WHERE id_jas = :id");
        return $stmt->execute([
            'id'       => $id,
            'nama'     => $data['Nama_jas'],
            'foto'     => $data['foto'] ?? '',
            'kategori' => $data['kategori'] ?? 'Formal',
            'ukuran'   => $data['Ukuran_jas'],
            'warna'    => $data['Warna_jas'],
            'harga'    => $data['Harga_sewa'],
            'stok'     => $data['Stok']
        ]);
    }

    // Hapus data jas (dicegah jika sedang disewa/terkait di tb_order)
    public function delete($id): bool
    {
        try {
            $check = $this->db->prepare("SELECT COUNT(*) FROM tb_order WHERE id_jas = :id");
            $check->execute(['id' => $id]);
            if ((int)$check->fetchColumn() > 0) {
                return false;
            }
        } catch (Exception $e) {
            // Abaikan jika relasi belum tersedia
        }

        $stmt = $this->db->prepare("DELETE FROM tb_jas WHERE id_jas = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Ambil daftar jas untuk katalog customer (filter pencarian dan kategori)
    public function getAvailable($search = '', $kategori = ''): array
    {
        $sql = "SELECT * FROM tb_jas WHERE 1=1";
        $params = [];

        if (!empty($kategori)) {
            $sql .= " AND kategori = :kategori";
            $params['kategori'] = $kategori;
        }

        if (!empty($search)) {
            $sql .= " AND (Nama_jas LIKE :kw OR Warna_jas LIKE :kw OR Ukuran_jas LIKE :kw OR kategori LIKE :kw)";
            $params['kw'] = "%$search%";
        }

        $sql .= " ORDER BY Stok DESC, id_jas DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Ambil daftar kategori jas yang tersedia
    public function getCategories(): array
    {
        try {
            $stmt = $this->db->query("SELECT DISTINCT kategori FROM tb_jas WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori ASC");
            $list = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($list)) {
                return $list;
            }
        } catch (Exception $e) {
            // Fallback kategori default
        }
        return ['Formal', 'Tuxedo', 'Pernikahan', 'Wisuda', 'Blazer Casual'];
    }

    // Kurangi stok jas saat terjadi pemesanan sewa
    public function decreaseStock($id, $qty = 1): bool
    {
        $stmt = $this->db->prepare("UPDATE tb_jas SET Stok = GREATEST(0, Stok - :qty) WHERE id_jas = :id");
        return $stmt->execute(['id' => $id, 'qty' => $qty]);
    }

    // Tambah kembali stok jas saat pengembalian atau pembatalan order
    public function increaseStock($id, $qty = 1): bool
    {
        $stmt = $this->db->prepare("UPDATE tb_jas SET Stok = Stok + :qty WHERE id_jas = :id");
        return $stmt->execute(['id' => $id, 'qty' => $qty]);
    }
}
