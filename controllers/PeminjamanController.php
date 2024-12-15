<?php
if(!defined('SECURE_ACCESS')) die('Direct access not permitted');

require_once 'Controller.php';
require_once 'models/Book.php';
require_once 'config/database.php';

class PeminjamanController extends Controller
{
    const DENDA_PER_HARI = 1000; 

    public static function return()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }
        
        return self::view('views/return.php');
    }

    public static function processReturn()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /return");
            exit();
        }

        $borrowing_id = $_POST['borrowing_id'] ?? null;

        if (!$borrowing_id) {
            $_SESSION['error'] = "ID peminjaman tidak valid";
            header("Location: /return");
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
                header("Location: /return");
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
            
            header("Location: /return");
            exit();

        } catch (PDOException $e) {
            if(isset($pdo)) $pdo->rollBack();
            error_log("Error in ReturnController::processReturn - " . $e->getMessage());
            $_SESSION['error'] = "Terjadi kesalahan saat memproses pengembalian";
            header("Location: /return");
            exit();
        }
    }

    public static function index()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }
        
        try {
            global $pdo;
            $stmt = $pdo->prepare("SHOW COLUMNS FROM books LIKE 'status'");
            $stmt->execute();
            if($stmt->rowCount() == 0) {
                $pdo->exec("ALTER TABLE books ADD COLUMN status ENUM('available', 'borrowed') NOT NULL DEFAULT 'available'");
            }
            
            return self::view('views/peminjaman.php');
        } catch (Exception $e) {
            error_log("Error in BorrowController::index - " . $e->getMessage());
            $_SESSION['error'] = "Terjadi kesalahan saat memuat halaman";
            header("Location: /");
            exit();
        }
    }

    public static function store()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /peminjaman");
            exit();
        }

        $book_id = $_POST['book_id'] ?? null;
        $borrow_date = $_POST['borrow_date'] ?? null;
        $return_date = $_POST['return_date'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$book_id || !$borrow_date || !$return_date || !$user_id) {
            $_SESSION['error'] = "Semua field harus diisi";
            header("Location: /peminjaman");
            exit();
        }

        try {
            global $pdo;
            $stmt = $pdo->prepare("SHOW TABLES LIKE 'borrowings'");
            $stmt->execute();
            if($stmt->rowCount() == 0) {
                $pdo->exec("CREATE TABLE borrowings (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    user_id INT NOT NULL,
                    book_id INT NOT NULL,
                    borrow_date DATE NOT NULL,
                    return_date DATE NOT NULL,
                    status ENUM('borrowed', 'returned') NOT NULL DEFAULT 'borrowed',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id),
                    FOREIGN KEY (book_id) REFERENCES books(id)
                )");
            }
            
            $stmt = $pdo->prepare("SELECT status FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch();

            if (!$book || $book['status'] !== 'available') {
                $_SESSION['error'] = "Buku tidak tersedia untuk dipinjam";
                header("Location: /peminjaman");
                exit();
            }
            $borrow_timestamp = strtotime($borrow_date);
            $return_timestamp = strtotime($return_date);
            $min_borrow = strtotime('-30 days');
            $max_borrow = strtotime('+30 days');
            
            if ($borrow_timestamp < $min_borrow || $borrow_timestamp > $max_borrow) {
                $_SESSION['error'] = "Tanggal peminjaman tidak valid";
                header("Location: /peminjaman");
                exit();
            }

            $max_return = strtotime('+3 months', $borrow_timestamp);
            $min_return = strtotime('-30 days', $borrow_timestamp);

            if ($return_timestamp > $max_return) {
                $_SESSION['error'] = "Tanggal pengembalian tidak boleh lebih dari 3 bulan";
                header("Location: /peminjaman");
                exit();
            }
            if ($return_timestamp < $borrow_timestamp) {
                $_SESSION['warning'] = "Perhatian: Tanggal pengembalian lebih awal dari tanggal peminjaman. Ini akan mengakibatkan denda keterlambatan.";
            }

            $pdo->beginTransaction();
            $sql = "INSERT INTO borrowings (user_id, book_id, borrow_date, return_date, status) 
                    VALUES (?, ?, ?, ?, 'borrowed')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $book_id, $borrow_date, $return_date]);
            $sql = "UPDATE books SET status = 'borrowed' WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$book_id]);

            $pdo->commit();

            $_SESSION['success'] = "Buku berhasil dipinjam";
            header("Location: /peminjaman");
            exit();

        } catch (PDOException $e) {
            if(isset($pdo)) $pdo->rollBack();
            error_log("Error in BorrowController::store - " . $e->getMessage());
            $_SESSION['error'] = "Terjadi kesalahan saat memproses peminjaman";
            header("Location: /peminjaman");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    PeminjamanController::store();
} else {
    PeminjamanController::index();
} 