<?php
class Users extends Controller
{
  public function __construct()
  {
    $this->userModel = $this->model('User');
  }

  public function register()
  {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {



      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


      $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => ''
      ];


      if (empty($data['email'])) {
        $data['email_err'] = 'Kérlek adjon meg email címet!';
      } else {

        if ($this->userModel->findUserByEmail($data['email'])) {
          $data['email_err'] = 'Ez az email cím foglalt!';
        }
      }

      if (empty($data['password'])) {
        $data['password_err'] = 'Kérlek adj meg jelszót!';
      } elseif (strlen($data['password']) < 6) {
        $data['password_err'] = 'A jelszónak minimum 6 karakternek kell lennie!';
      }


      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Kérlek erősítse meg a jelszavát!';
      } else {
        if ($data['password'] != $data['confirm_password']) {
          $data['confirm_password_err'] = 'A jelszavak nem egyeznek';
        }
      }


      if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {



        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);


        if ($this->userModel->register($data)) {
          flash('register_success', 'A regisztráció sikeres, mostmár bejelentkezhet!');
          redirect('users/login');
        } else {
          die('Valami nem jó');
        }
      } else {

        $this->view('users/register', $data);
      }
    } else {
      $data = [
        'email' => '',
        'password' => '',
        'confirm_password' => '',
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => ''
      ];

      // Load view
      $this->view('users/register', $data);
    }
  }

  public function login()
  {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


      $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_err' => '',
        'password_err' => '',
      ];


      if (empty($data['email'])) {
        $data['email_err'] = 'Kérlek adjon meg emailt!';
      }

      if (empty($data['password'])) {
        $data['password_err'] = 'Kérlek adjon meg jelszót!';
      }


      if ($this->userModel->findUserByEmail($data['email'])) {
      } else {

        $data['email_err'] = 'Nincs ilyen felhasználó';
      }


      if (empty($data['email_err']) && empty($data['password_err'])) {

        $loggedInUser = $this->userModel->login($data['email'], $data['password']);

        if ($loggedInUser) {

          $this->createUserSession($loggedInUser);
        } else {
          $data['password_err'] = 'A megadott jelszó nem helyes';

          $this->view('users/login', $data);
        }
      } else {

        $this->view('users/login', $data);
      }
    } else {

      $data = [
        'email' => '',
        'password' => '',
        'email_err' => '',
        'password_err' => '',
      ];


      $this->view('users/login', $data);
    }
  }

  public function createUserSession($user)
  {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    redirect('posts');
  }

  public function logout()
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    session_destroy();
    redirect('users/login');
  }
}
