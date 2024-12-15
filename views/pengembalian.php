<?php
if(!isset($_SESSION['is_login'])) {
    header("Location: /login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Buku - Winnie the Pooh Theme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background dan font */
        body {
            background-color: #fff8dc; /* Warna latar lembut */
            color: #5C3C1D; /* Warna teks coklat */
            font-family: 'Fredoka', sans-serif;
        }

        h2 {
            color: #C0392B; /* Warna merah khas Winnie the Pooh */
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            background-color: #ffeb99; /* Warna kuning pastel */
            border: 2px solid #f7d794; /* Garis tepi kuning lembut */
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: 30px auto;
        }

        /* Gaya alert */
        .alert {
            border-radius: 10px;
            font-weight: bold;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Tombol */
        .btn-secondary {
            background-color: #ffad60; /* Warna oranye pastel */
            color: #5C3C1D;
            font-weight: bold;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #ff8c42;
            color: white;
        }

        /* Winnie the Pooh Icon */
        .pooh-icon {
            display: block;
            margin: 0 auto;
            width: 100px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <img src="/views/img/pooh.png" alt="Winnie the Pooh" class="pooh-icon">
        <h2>Pengembalian Buku</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning">
                <?= $_SESSION['warning']; ?>
                <?php unset($_SESSION['warning']); ?>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <a href="/book" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
