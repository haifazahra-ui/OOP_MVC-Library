<?php

require 'Controller.php';

class MembershipController extends Controller
{
    public static function index()
    {
        $data = [];
        if (isset($_SESSION['username'])) {
            $data['username'] = $_SESSION['username'];
        }

        return self::view('views/membership.php', $data);
    }
}

MembershipController::index();