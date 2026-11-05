<?php
require_once __DIR__ . '/../koneksi.php';

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Ambil semua user
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM tb_user");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari user berdasarkan ID
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_user WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cari user berdasarkan username
    public function findByUsername(string $username): ?array
    {
        $clean = strtolower(trim($username));
        $noSpace = str_replace(' ', '', $clean);

        $sql = "SELECT * FROM tb_user 
                WHERE LOWER(username) = :u 
                   OR LOWER(REPLACE(username, ' ', '')) = :no_space 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'u'        => $clean,
            'no_space' => $noSpace
        ]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    // Tambah user baru
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO tb_user (username, password) VALUES (:username, :password)");
        return $stmt->execute([
            'username' => $data['username'],
            'password' => $data['password']
        ]);
    }

    // Update data user
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE tb_user SET username = :username, password = :password WHERE id = :id");
        return $stmt->execute([
            'id'       => $id,
            'username' => $data['username'],
            'password' => $data['password']
        ]);
    }

    // Hapus user
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tb_user WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
