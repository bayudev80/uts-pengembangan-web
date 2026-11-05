<?php
require_once __DIR__ . '/../koneksi.php';

class CustomerModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua data customer
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM tb_customer ORDER BY id_customer DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari customer berdasarkan ID
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_customer WHERE id_customer = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah data customer
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO tb_customer (nama_customer, no_hp, KTP) VALUES (:nama_customer, :no_hp, :KTP)");
        return $stmt->execute([
            'nama_customer' => $data['nama_customer'],
            'no_hp'         => $data['no_hp'],
            'KTP'           => $data['KTP']
        ]);
    }

    // Update data customer
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE tb_customer SET nama_customer = :nama_customer, no_hp = :no_hp, KTP = :KTP WHERE id_customer = :id");
        return $stmt->execute([
            'id'            => $id,
            'nama_customer' => $data['nama_customer'],
            'no_hp'         => $data['no_hp'],
            'KTP'           => $data['KTP']
        ]);
    }

    // Hapus customer (dicegah jika memiliki transaksi order)
    public function delete($id)
    {
        $check = $this->db->prepare("SELECT COUNT(*) FROM tb_order WHERE id_customer = :id");
        $check->execute(['id' => $id]);

        if ((int)$check->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM tb_customer WHERE id_customer = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Tambah data customer dan kembalikan ID baru
    public function insertGetId($data): int
    {
        $stmt = $this->db->prepare("INSERT INTO tb_customer (nama_customer, no_hp, KTP) VALUES (:nama_customer, :no_hp, :KTP)");
        $stmt->execute([
            'nama_customer' => $data['nama_customer'],
            'no_hp'         => $data['no_hp'],
            'KTP'           => $data['KTP']
        ]);
        return (int)$this->db->lastInsertId();
    }

    // Cari customer berdasarkan KTP atau Nomor HP
    public function findByKtpOrPhone($ktp, $hp)
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_customer WHERE (KTP = :ktp AND KTP != '') OR (no_hp = :hp AND no_hp != '') LIMIT 1");
        $stmt->execute(['ktp' => $ktp, 'hp' => $hp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Alias method
    public function getCustomers() { return $this->all(); }
    public function getCustomerById($id) { return $this->find($id); }
}
