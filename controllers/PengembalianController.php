<?php
  
 require 'Controller.php';

  class PengembalianController extends Controller
  {
    public static function index()
    {
        return self::view('views/pengembalian.php');
    }
  }
  PengembalianController::index();