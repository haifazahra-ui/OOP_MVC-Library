<?php
if (!defined('SECURE_ACCESS')) {
   die('Direct access not permitted');
}

if(isset($_SESSION['is_login']) == false){
    header("location:/login");
}

?>
    <title>MEMBERSHIP | HONEY TREE LIBRARY</title>
    <link rel="stylesheet" href="/views/style.css">
    <style>
        body {
            background: url('assets/images/library-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .membership-container {
            background-color: rgba(255, 245, 204, 0.9); /* Warna lembut */
            border: 2px solid #D2691E;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin: 50px auto;
            max-width: 800px;
        }

        h1 {
            text-align: center;
            color: #8B4513;
            margin-bottom: 20px;
        }

        .membership-list {
            list-style: none;
            padding: 0;
        }

        .membership-list li {
            background: #FFD700;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            color: #5C4033;
        }

        .btn-primary {
            background-color: #D2691E;
            border: none;
        }

        .btn-primary:hover {
            background-color: #8B4513;
        }

        a {
            color: #8B0000;
            text-decoration: none;
        }
    </style>
</head>
<body> 
    <header class="header">
        <div class="logo">
        <img src="views/img/pooh.png" alt="d" class="logo-img"> Honey Tree Library
        </div>
        <nav class="nav">
            <a href="login">Login</a>
            <a href="register">Register</a>
            <a href="/">Home</a>
            <a href="#">Pengembalian</a>
            <a href="#">Membership</a>
        </nav>
    </header>
    <div class="membership-container">
        <p class="text-center">
            Discover our membership benefits and services designed to enrich your reading experience.
        </p>
        <ul class="membership-list">
        <li>✔ Akses tanpa batas ke ribuan buku</li>
        <li>✔ Meminjam hingga 3 buku sekaligus</li>
        <li>✔ Akses eksklusif ke acara khusus anggota</li>
        <li>✔ Reservasi buku secara online</li>
        <li>✔ Denda keterlambatan: Rp 2.000 per hari setelah batas pengembalian</li>

        </ul>
        <div class="text-center mt-4">
            <br>
            <a href="/register" class="btn btn-primary">Join Now</a>
        </div>
    </div>
    <footer class="footer">
        <p>© 2024 Perpustakaan Winnie the Pooh | Hundred Acre Wood</p>
    </footer>
</body>
</html>
