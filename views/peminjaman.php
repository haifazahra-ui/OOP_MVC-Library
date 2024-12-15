<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['is_login'])) {
    header("Location: /login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku - Winnie the Pooh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <style>
        /* Tema Umum */
        body {
            font-family: 'Fredoka One', cursive; /* Terapkan Fredoka One */
            background-color: #FFF9C4; /* Light yellow seperti latar Pooh */
            color: #5C4033; /* Soft brown seperti karakter Pooh */
        }

        h2, h3 {
            color: #E74C3C; /* Warna merah seperti baju Pooh */
            font-weight: 400; /* Menyesuaikan ketebalan */
        }
        /* Header Section */
        .header {
            background-color: #FFD700; /* Pooh's yellow */
            text-align: center;
            padding: 20px;
        }
        .header img {
            max-width: 150px;
        }
        /* Themed Buttons */
        .btn-pooh {
            background-color: #E74C3C; /* Pooh's red shirt */
            color: white;
            border: none;
            transition: background 0.3s ease;
        }
        .btn-pooh:hover {
            background-color: #C0392B; /* Slightly darker red */
        }
        .btn-secondary {
            background-color: #F4A460; /* Honey-like brown */
        }

        /* Container and Cards */
        .main-container {
            background-color: white;
            border: 3px solid #FFD700;
            border-radius: 15px;
            padding: 30px;
            margin: 20px auto;
            max-width: 700px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        table th, table td {
            color: #5C4033; /* Text inside tables */
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #FFD700;
            color: #5C4033;
        }

        /* Image Decorations */
        .pooh-left {
            position: absolute;
            left: 20px;
            bottom: 20px;
            max-width: 150px;
        }

        .pooh-right {
            position: absolute;
            right: 20px;
            bottom: 20px;
            max-width: 150px;
        }
    </style>
</head>
<body>
    <!-- Header with Pooh Theme -->
    <div class="header">
        <img src="/views/img/pooh.png" alt="Winnie the Pooh Logo">
        <h1>Peminjaman dan Pengembalian Buku</h1>
        <p>Selamat datang di perpustakaan Winnie the Pooh!</p>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <h3>Form Peminjaman Buku</h3>
        
        <form action="/peminjaman" method="POST">
            <div class="mb-3">
                <label for="book" class="form-label">Pilih Buku</label>
                <select class="form-select" name="book_id" id="book" required>
                    <option value="">Pilih buku yang ingin dipinjam</option>
                    <?php
                    global $pdo;
                    $sql = "SELECT * FROM books WHERE status = 'available'";
                    $stmt = $pdo->query($sql);
                    while ($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $book['id'] . "'>" . htmlspecialchars($book['title']) . " - " . htmlspecialchars($book['author']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="borrow_date" class="form-label">Tanggal Peminjaman</label>
                <input type="date" class="form-control" id="borrow_date" name="borrow_date" required>
            </div>

            <div class="mb-3">
                <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                <input type="date" class="form-control" id="return_date" name="return_date" required>
            </div>

            <button type="submit" class="btn btn-pooh w-100">Pinjam Buku</button>
        </form>
    </div>

    <!-- Pengembalian Buku Section -->
    <div class="main-container">
        <h3>Daftar Buku yang Sedang Dipinjam</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Batas Pengembalian</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT b.title, br.id as borrowing_id, br.borrow_date, br.return_date, br.status,
                       DATEDIFF(CURRENT_DATE, br.return_date) as days_late
                       FROM borrowings br 
                       JOIN books b ON br.book_id = b.id 
                       WHERE br.user_id = ? AND br.status = 'borrowed'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id]);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $days_late = max(0, $row['days_late']);
                    $denda = $days_late * 1000;

                    echo "<tr>
                        <td>" . htmlspecialchars($row['title']) . "</td>
                        <td>" . $row['borrow_date'] . "</td>
                        <td>" . $row['return_date'] . "</td>
                        <td>" . ($days_late > 0 ? 'Terlambat' : 'Dipinjam') . "</td>
                        <td>Rp " . number_format($denda, 0, ',', '.') . "</td>
                        <td>
                            <form action='/pengembalian' method='POST'>
                                <input type='hidden' name='borrowing_id' value='{$row['borrowing_id']}'>
                                <button type='submit' class='btn btn-warning btn-sm'>Kembalikan</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Hak Cipta © 2024 - Perpustakaan Winnie the Pooh</p>
    </div>

    <img src="/views/img/right.png" class="pooh-left" alt="Pooh Image Left">
    <img src="/views/img/left.png" class="pooh-right" alt="Pooh Image Right">
</body>
</html>
