<?php
require_once __DIR__ . '/../koneksi.php';

class adminModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua data admin
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM tb_admin ORDER BY id_admin DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari admin berdasarkan ID
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_admin WHERE id_admin = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah admin baru
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO tb_admin (nama, email) VALUES (:nama, :email)");
        return $stmt->execute([
            'nama' => $data['nama'],
            'email' => $data['email']
        ]);
    }

    // Update data admin
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE tb_admin SET nama = :nama, email = :email WHERE id_admin = :id");
        return $stmt->execute([
            'id' => $id,
            'nama' => $data['nama'],
            'email' => $data['email']
        ]);
    }

    // Hapus data admin
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tb_admin WHERE id_admin = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Alias method
    public function getAlladmin() { return $this->all(); }
}
