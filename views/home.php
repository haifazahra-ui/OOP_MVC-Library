<?php
if (!defined('SECURE_ACCESS')) {
   die('Direct access not permitted');
}
?>
    <title>Honey Tree Library</title>
    <link rel="stylesheet" href="/views/style.css">

    <header class="header">
        <div class="logo">
        <img src="views/img/pooh.png" alt="d" class="logo-img"> Honey Tree Library
        </div>
        <nav class="nav">
            <a href="login">Login</a>
            <a href="/">Home</a>
            <a href="book">Book</a>
            <a href="peminjaman">Peminjaman</a>
            <a href="pengembalian">Pengembalian</a>
            <a href="membership">Membership</a>
        </nav>
    </header>

    <section class="hero">
        <h1>Selamat Datang di Perpustakaan Winnie the Pooh</h1>
        <p>Temukan koleksi buku terbaik dan nikmati petualangan di dunia Hundred Acre Wood.</p>
        <button class="cta-button"><a href="book">Mulai Meminjam</a></button>
    </section>

    <main class="main-content">
        <section class="book-section">
            <h2>Koleksi Buku</h2>
                <div class="book-cards">
                <div class="book-card">
                    <img src="views/img/bedtime.jpg" alt="Buku Winnie the Pooh">
                    <h3>My Favourite Bedtime Storybook</h3>
                    <br>
                    <p>With six sweet stories, little readers can join their favourite friends from the Hundred-Acre Wood in this new bedtime storybook collection!</p>
                </div>
                <div class="book-card">
                    <img src="views/img/classic collection.jpg" alt="Buku Winnie The Pooh">
                    <h3>Disney: Classic Collection #15</h3>
                    <br>
                    <p>The friends in the Hundred-Acre Wood are embarking on an exciting adventure.</p>
                </div>
                <div class="book-card">
                    <img src="views/img/gift.jpg" alt="Buku Winnie The Pooh">
                    <h3>GIFT FOR POOH</h3>
                    <br>
                    <p>Pooh wraps a pot full of honey to give away, but with each visit to his friends, his tummy gets a little more rumbly. Who will Pooh Bear give his honeypot to in the end? Lift the flaps of this delightful gift-giving tale to find out!</p>
                </div>
                <div class="book-card">
                    <img src="views/img/honey trouble.jpg" alt="Buku Winnie The Pooh">
                    <h3>Pooh's Honey Trouble</h3>
                    <br>
                    <p>With a rumbly in his tumbly, but not even a smidgen of honey, Winnie the Pooh is in real trouble. What's a Hungry Bear to do?</p>
                </div>
                <div class="book-card">
                    <img src="views/img/secret garden.jpg" alt="Buku Winnie The Pooh">
                    <h3>POOHS SECRET GARDEN</h3>
                    <br>
                    <p>Spring has sprung in the Hundred-Acre Wood and Winnie the Pooh is planting a garden.</p>
                </div>
                <br>
                <div class="book-card">
                    <img src="views/img/adventure.jpeg" alt="Buku Winnie The Pooh">
                    <h3>The Many Adventures of Winnie the Pooh</h3>
                    <br>
                    <p>Experience the magical tales of Pooh and his Hundred Acre Wood friends.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>© 2024 Perpustakaan Winnie the Pooh | Hundred Acre Wood</p>
    </footer>
</body>
</html>
