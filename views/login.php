<?php
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitted');
}
$title = "LOGIN | HONEY TREE LIBRARY";
?>

<style>
    /* Tema Winnie the Pooh Fullscreen */
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: url('assets/images/pooh-background.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: "Fredoka One", cursive;
        color: #5C4033; /* Coklat muda */
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-content {
        background: rgba(255, 245, 204, 0.95); /* Kuning madu semi transparan */
        border: 3px solid #D2691E; /* Coklat Pooh */
        border-radius: 20px;
        padding: 30px;
        width: 100%;
        max-width: 500px; /* Responsif */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .panel-title {
        color: #B22222; /* Warna merah Pooh */
        font-size: 28px;
        margin-bottom: 15px;
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

    a {
        color: #B22222; /* Merah Pooh */
        text-decoration: none;
        font-size: 14px;
    }

    a:hover {
        color: #FF4500; /* Oranye Pooh */
        text-decoration: underline;
    }

    /* Dekorasi Logo dan Pooh */
    .top .logo img {
        width: 120px;
        margin-bottom: 10px;
    }

    .bottom::after {
        content: url('assets/images/pooh-icon.png');
        display: block;
        margin: 20px auto;
        width: 80px;
        height: auto;
    }

    /* Responsif */
    @media (max-width: 600px) {
        .main-content {
            padding: 20px;
            width: 90%;
        }
    }
</style>

<div class="main-content login-panel">
    <div class="login-body">
        <div class="top">
            <div class="logo">
                <img src="/views/img/pooh.png" alt="Winnie the Pooh Logo">
            </div>
        </div>
        <div class="bottom">
            <h3 class="panel-title">Welcome Back to Honey Tree!</h3>
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="alert alert-danger text-center">
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif ?>
            <form method="POST" action="/login">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Username"
                        name="username"
                        value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : "" ?>"
                        required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                    <input type="password" 
                        class="form-control rounded-end" 
                        placeholder="Password"
                        name="password" 
                        required>
                </div>
                <button type="submit" class="btn btn-primary w-100 login-btn">Login</button><br>
                <div class="mt-3">
                    Don't have an account? <a href="/register">Sign Up Here</a>
                </div>
            </form>
        </div>
    </div>
</div>
