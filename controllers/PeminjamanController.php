<?php
  
 require 'Controller.php';

  class PeminjamanController extends Controller
  {
    public static function index()
    {
        return self::view('views/peminjaman.php');
    }
  }
  PeminjamanController::index();