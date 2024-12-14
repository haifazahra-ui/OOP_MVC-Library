<?php

require 'Controller.php';

class MembershipController extends Controller
{
    public static function index()
    {
        // Jika ada session user, tampilkan pesan selamat datang
        $data = [];
        if (isset($_SESSION['username'])) {
            $data['username'] = $_SESSION['username'];
        }

        // Kirim halaman membership
        return self::view('views/membership.php', $data);
    }
}

MembershipController::index();