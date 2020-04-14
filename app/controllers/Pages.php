<?php
class Pages extends Controller
{
  public function __construct()
  {
  }

  public function index()
  {
    if (isLoggedIn()) {
      redirect('pages/about');
    }

    $data = [
      'title' => 'Főoldal',
      'description' => 'Helló világ'
    ];

    $this->view('pages/index', $data);
  }

  public function about()
  {
    $data = [
      'title' => 'Rólunk',
      'description' => 'Sikeres bejelentkezés'
    ];

    $this->view('pages/about', $data);
  }
}
