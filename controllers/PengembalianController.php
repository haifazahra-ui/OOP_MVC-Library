<?php
if(!defined('SECURE_ACCESS')) die('Direct access not permitted');

require_once 'Controller.php';
require_once 'config/database.php';

class PengembalianController extends Controller
{
    const DENDA_PER_HARI = 1000;

    public static function index()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }
        
        return self::view('views/pengembalian.php');
    }

    public static function processReturn()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /pengembalian");
            exit();
        }

        $borrowing_id = $_POST['borrowing_id'] ?? null;

        if (!$borrowing_id) {
            $_SESSION['error'] = "ID peminjaman tidak valid";
            header("Location: /pengembalian");
            exit();
        }

        try {
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT b.*, br.return_date, br.book_id 
                FROM borrowings br
                JOIN books b ON b.id = br.book_id
                WHERE br.id = ? AND br.status = 'borrowed'
            ");
            $stmt->execute([$borrowing_id]);
            $borrowing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$borrowing) {
                $_SESSION['error'] = "Data peminjaman tidak ditemukan";
                header("Location: /pengembalian");
                exit();
            }
            $return_date = new DateTime($borrowing['return_date']);
            $today = new DateTime();
            $denda = 0;

            if ($today > $return_date) {
                $diff = $today->diff($return_date);
                $denda = $diff->days * self::DENDA_PER_HARI;
            }
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("
                UPDATE borrowings 
                SET status = 'returned', 
                    actual_return_date = CURRENT_DATE,
                    late_fee = ?
                WHERE id = ?
            ");
            $stmt->execute([$denda, $borrowing_id]);
            $stmt = $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?");
            $stmt->execute([$borrowing['book_id']]);

            $pdo->commit();

            if ($denda > 0) {
                $_SESSION['warning'] = "Buku berhasil dikembalikan. Anda dikenakan denda keterlambatan sebesar Rp " . number_format($denda, 0, ',', '.');
            } else {
                $_SESSION['success'] = "Buku berhasil dikembalikan";
            }
            
            header("Location: /pengembalian");
            exit();

        } catch (PDOException $e) {
            if(isset($pdo)) $pdo->rollBack();
            error_log("Error in ReturnController::processReturn - " . $e->getMessage());
            $_SESSION['error'] = "Terjadi kesalahan saat memproses pengembalian";
            header("Location: /pengembalian");
            exit();
        }
    }
}

// Routing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    PengembalianController::processReturn();
} else {
    PengembalianController::index();
} 