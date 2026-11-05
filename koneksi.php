<?php
class Database
{
    private static ?PDO $instance = null;

    // Singleton koneksi PDO
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = 'localhost';
            $user = 'root';
            $pass = '';
            $db   = 'sewajas';

            try {
                self::$instance = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Koneksi database gagal: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}

// Helper: Format mata uang Rupiah
if (!function_exists('formatRupiah')) {
    function formatRupiah($angka) {
        return 'Rp ' . number_format((float)$angka, 0, ',', '.');
    }
}

// Helper: Format tanggal (d M Y)
if (!function_exists('formatTanggal')) {
    function formatTanggal($tgl) {
        if (!$tgl) return '-';
        return date('d M Y', strtotime($tgl));
    }
}

// Helper: Path foto jas valid
if (!function_exists('getFotoJas')) {
    function getFotoJas($foto) {
        $foto = trim($foto ?? '');
        if (!empty($foto)) {
            // Dukung link gambar web eksternal (http/https)
            if (preg_match('#^https?://#i', $foto)) {
                return $foto;
            }
            if (file_exists(__DIR__ . "/foto_jas/" . $foto)) {
                return "foto_jas/" . $foto;
            }
            if (file_exists(__DIR__ . "/" . $foto)) {
                return $foto;
            }
        }
        return "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=500&auto=format&fit=crop&q=60";
    }
}
