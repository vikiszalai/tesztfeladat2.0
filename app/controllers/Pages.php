<?php
class Pages extends Controller
{
  public function __construct()
  {
  }

  public function index()
  {
    if (isLoggedIn()) {
      redirect('posts');
    }

    $data = [
      'title' => 'Főoldal',
      'description' => 'ez egy MVC frameworkon alapuló oldal'
    ];

    $this->view('pages/index', $data);
  }

  public function about()
  {
    $data = [
      'title' => 'Rólunk',
      'description' => 'Rólunk'
    ];

    $this->view('pages/about', $data);
  }
}
