<?php

if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

$title = "Peminjaman Buku";

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'mylibrary');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data buku yang tersedia
$sql_books = "SELECT id, title FROM books"; // Query untuk mengambil id dan judul buku
$result_books = $conn->query($sql_books);
$books = [];
while ($row = $result_books->fetch_assoc()) {
    $books[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = (int)$_POST['book_id']; // Mengambil id buku yang dipilih
    $member_name = trim($_POST['member_name']);
    $borrow_date = trim($_POST['borrow_date']);
    $description = trim($_POST['description']);
    $quantity = (int)$_POST['quantity'];

    // Menghitung tanggal pengembalian otomatis (+3 hari)
    $borrow_date_obj = new DateTime($borrow_date);
    $borrow_date_obj->modify('+3 days');
    $return_date = $borrow_date_obj->format('Y-m-d');

    // Cek apakah data tidak kosong
    if (empty($book_id) || empty($member_name) || empty($borrow_date) || empty($description) || $quantity <= 0) {
        echo "<div class='alert alert-danger text-center'>";
        echo "Semua field harus diisi dengan benar!";
        echo "</div>";
        exit;
    }

    // Query untuk menyimpan data peminjaman
    $sql = "INSERT INTO book_loans (book_id, member_name, borrow_date, return_date, description, quantity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Binding parameter
    $stmt->bind_param("issssi", $book_id, $member_name, $borrow_date, $return_date, $description, $quantity);

    // Eksekusi dan pengecekan hasil
    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>";
        echo "Data berhasil disimpan! Tanggal pengembalian otomatis: " . $return_date;
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>";
        echo "Terjadi kesalahan: " . $stmt->error;
        echo "</div>";
    }

    // Menutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>
<style>
    /* Tema Winnie the Pooh */
    body {
        background: url('assets/images/pooh-background.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Fredoka One', cursive;
        color: #5C4033;
    }

    .main-content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-panel {
        background-color: rgba(255, 245, 204, 0.95); /* Kuning Pooh */
        width: 100%;
        max-width: 500px;
        padding: 20px;
        border-radius: 20px;
        border: 3px solid #D2691E; /* Coklat Pooh */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .login-body {
        text-align: center;
    }

    .pooh-logo {
        width: 120px;
        margin-bottom: 15px;
    }

    .pooh-home-icon {
        font-size: 24px;
        color: #D2691E; /* Coklat Pooh */
    }

    .panel-title {
        color: #B22222; /* Merah Pooh */
        font-size: 28px;
        margin-bottom: 20px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group-text {
        background-color: #FFD700; /* Kuning Pooh */
        border: none;
        color: #5C4033;
        font-size: 18px;
    }

    input.form-control, select.form-control {
        border: 2px solid #FFD700;
        border-left: none;
        font-size: 16px;
        color: #5C4033;
    }

    input.form-control:focus, select.form-control:focus {
        outline: none;
        box-shadow: 0 0 5px #FFD700;
    }

    .btn-primary {
        background-color: #D2691E; /* Coklat Pooh */
        border: none;
        font-size: 18px;
        padding: 10px;
        border-radius: 10px;
        transition: background 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #8B4513;
        cursor: pointer;
    }

    .form-center {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }

    @media (max-width: 600px) {
        .login-panel {
            padding: 15px;
            width: 90%;
        }
    }
</style>

<div class="main-content">
    <div class="login-panel">
        <div class="login-body">
            <div class="top d-flex justify-content-between align-items-center">
                <div class="logo">
                    <img src="/views/img/pooh.png" alt="Logo Pooh" class="pooh-logo">
                </div>
                <a href="/"><i class="fa-duotone fa-house-chimney pooh-home-icon"></i></a>
            </div>

            <div class="bottom">
                <h3 class="panel-title text-center">Peminjaman Buku</h3>

                <form method="POST" action="" class="form-center">

                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-book"></i></span>
                        <select class="form-control" name="book_id" required>
                            <option value="" disabled selected>Pilih Buku</option>
                            <?php foreach ($books as $book) : ?>
                                <option value="<?= $book['id'] ?>"><?= $book['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nama Member"
                            name="member_name"
                            required>
                    </div>

                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                        <input
                            type="date"
                            class="form-control"
                            name="borrow_date"
                            required>
                    </div>

                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-comment"></i></span>
                        <select class="form-control" name="description">
                            <option value="" disabled selected>Pilih Keterangan</option>
                            <option value="peminjaman">Peminjaman</option>
                        </select>
                    </div>

                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-list"></i></span>
                        <input
                            type="number"
                            class="form-control"
                            placeholder="Jumlah Buku"
                            name="quantity"
                            required min="1">
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Pinjam Buku</button>
                </form>
            </div>
        </div>
    </div>
</div>
