<?php
require_once __DIR__ . '/../koneksi.php';

class DashboardController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Tampilkan ringkasan dashboard statistik rental jas
    public function index()
    {
        // 1. Data Order & Pendapatan
        $totalPendapatan = (float)$this->db->query("SELECT COALESCE(SUM(total_harga), 0) FROM tb_order")->fetchColumn();
        $totalOrder = (int)$this->db->query("SELECT COUNT(*) FROM tb_order")->fetchColumn();
        $orderDiproses = (int)$this->db->query("SELECT COUNT(*) FROM tb_order WHERE status = 'diproses'")->fetchColumn();
        $orderDisewa = (int)$this->db->query("SELECT COUNT(*) FROM tb_order WHERE status = 'disewa'")->fetchColumn();
        $orderSelesai = (int)$this->db->query("SELECT COUNT(*) FROM tb_order WHERE status IN ('selesai', 'dikembalikan')")->fetchColumn();

        // 2. Data Jas & Stok
        $totalJasModel = (int)$this->db->query("SELECT COUNT(*) FROM tb_jas")->fetchColumn();
        $totalStokTersedia = (int)$this->db->query("SELECT COALESCE(SUM(Stok), 0) FROM tb_jas")->fetchColumn();

        // 3. Data Customer & Admin
        $totalCustomer = (int)$this->db->query("SELECT COUNT(*) FROM tb_customer")->fetchColumn();
        $totalAdmin = (int)$this->db->query("SELECT COUNT(*) FROM tb_admin")->fetchColumn();

        // 4. Data Pengembalian & Denda
        $totalPengembalian = (int)$this->db->query("SELECT COUNT(*) FROM tb_pengembalian")->fetchColumn();
        $totalDenda = (float)$this->db->query("SELECT COALESCE(SUM(denda), 0) FROM tb_pengembalian")->fetchColumn();

        // 5. Pesanan Terbaru
        $sqlRecent = "SELECT o.*, c.nama_customer, c.no_hp AS no_hp_customer, j.Nama_jas, j.Warna_jas
                      FROM tb_order o
                      LEFT JOIN tb_customer c ON o.id_customer = c.id_customer
                      LEFT JOIN tb_jas j ON o.id_jas = j.id_jas
                      ORDER BY o.id_order DESC LIMIT 5";
        $recentOrders = $this->db->query($sqlRecent)->fetchAll(PDO::FETCH_ASSOC);

        // 6. Koleksi Jas Terkini
        $sqlJas = "SELECT * FROM tb_jas ORDER BY id_jas DESC LIMIT 5";
        $popularJas = $this->db->query($sqlJas)->fetchAll(PDO::FETCH_ASSOC);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        include __DIR__ . '/../View/dashboard/index.php';
    }
}

