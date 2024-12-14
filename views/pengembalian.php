<?php

if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}

$title = "Pengembalian Buku";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loan_id = (int)$_POST['loan_id'];
    $return_date = trim($_POST['return_date']);

    $conn = new mysqli('localhost', 'root', '', 'mylibrary'); 

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT return_date FROM book_loans WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $loan = $result->fetch_assoc();
        $expected_return_date = new DateTime($loan['return_date']);
        $actual_return_date = new DateTime($return_date);

        // Hitung keterlambatan dan denda
        $late_days = max(0, $expected_return_date->diff($actual_return_date)->days);
        $fine = $late_days * 5000; // Rp 5.000 per hari keterlambatan

        $update_sql = "UPDATE book_loans SET actual_return_date = ?, fine = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sii", $return_date, $fine, $loan_id);

        if ($update_stmt->execute()) {
            echo "<div class='alert alert-success text-center'>";
            echo "Pengembalian berhasil diproses! ";
            if ($fine > 0) {
                echo "<span style='color: red;'> Anda terlambat $late_days hari. Denda: Rp $fine</span>";
            } else {
                echo "Tidak ada denda.";
            }
            echo "</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>";
            echo "Terjadi kesalahan: " . $update_stmt->error;
            echo "</div>";
        }

        $update_stmt->close();
    } else {
        echo "<div class='alert alert-danger text-center'>";
        echo "ID Pinjaman tidak ditemukan.";
        echo "</div>";
    }

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

    input.form-control {
        border: 2px solid #FFD700;
        border-left: none;
        font-size: 16px;
        color: #5C4033;
    }

    input.form-control:focus {
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
                <h3 class="panel-title text-center">Pengembalian Buku</h3>

                <form method="POST" action="" class="form-center">
                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                        <input
                            type="number"
                            class="form-control"
                            placeholder="Buku Pinjaman"
                            name="loan_id"
                            required>
                    </div>

                    <!-- Tanggal Pengembalian -->
                    <div class="input-group mb-20">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                        <input
                            type="date"
                            class="form-control"
                            name="return_date"
                            required>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Proses Pengembalian</button>
                </form>
            </div>
        </div>
    </div>
</div>