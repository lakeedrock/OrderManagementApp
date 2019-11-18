<?php
  /**
   *
   */
  class Users extends Controller{

  	public function __construct(){
      if (!(isset($_SESSION['user_id']) && isset($_SESSION['user_email']) && isset($_SESSION['user_name']))) {
        header('location: '. BASE_URL.'users/login');
      }
      $this->userModel = $this->model('User');
  	}

    public function index(){
      redirect('views/pages/');
    }

  	public function register(){
  	  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    		// process form

    		// Sanitize POST Data
    		$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        //Init data for Register
    		$data = [
    		  'name' => trim($_POST['name']),
    		  'email' => trim($_POST['email']),
    		  'password' => trim($_POST['password']),
    		  'confirm_password' => trim($_POST['confirm_password']),
    		  'name_error' => '',
    		  'email_error' => '',
    		  'password_error' => '',
    		  'confirm_password_error' => ''
    		];

        //auto generate dataarray construction
        $genArray = array(
          'name' => $data['name'],
          'email' => $data['email'],
          'password' => $data['password'],
          'confirm_password' => $data['confirm_password']
        );

        //Generate Data Array for validation
        $generatedArray = $this->generateDataArray($genArray);

        //Sending prepared data array to validation algorythm
        $validatedArray = $this->validateData($generatedArray);

        //Validate email already exists
        if (empty($validatedArray['email']['error'])) {
          if ($this->userModel->findUserByEmail($validatedArray['email']['data'])) {
            $validatedArray['email']['error'] = 'Email is already exists';
          }
        }

        //Final validation to check there are no errors
        if (
        empty($validatedArray['name']['error']) &&
        empty($validatedArray['email']['error']) &&
        empty($validatedArray['password']['error']) &&
        empty($validatedArray['confirm_password']['error'])
        ){
          //Validate Successful & unset $data array
          unset($data);
          //Hashing Password
          $validatedArray['password']['data'] = password_hash($validatedArray['password']['data'],PASSWORD_DEFAULT);

          //Register User
          if ($this->userModel->registerUser($validatedArray)) {
            //Init Login Data
            $data = [

              'email' => '',
              'password' => '',
              'email_error' => '',
              'password_error' => ''
            ];
            flash('register_success','Thank You for Registering with AdCash. Please Login to Continue.');
            $this->view('users/login',$data);
          }else {
            //Trigger Fatal Error
            $data = [
        		  'name' => '',
        		  'email' => '',
        		  'password' => '',
        		  'confirm_password' => '',
        		  'name_error' => '',
        		  'email_error' => '',
        		  'password_error' => '',
        		  'confirm_password_error' => ''
        		];
            flash('register_fail','Fatal Error! Please try again later.','alert alert-danger');
            $this->view('users/register',$data);
          }
        }else{
          //Mapping values with $validateArray and $data array
          $data['name_error'] = $validatedArray['name']['error'];
          $data['email_error'] = $validatedArray['email']['error'];
          $data['password_error'] = $validatedArray['password']['error'];
          $data['confirm_password_error'] = $validatedArray['confirm_password']['error'];
          //Load View with errors
          $this->view('users/register',$data);
        }
  	  }else{
  		//Init Data for
  		$data = [
  		  'name' => '',
  		  'email' => '',
  		  'password' => '',
  		  'confirm_password' => '',
  		  'name_error' => '',
  		  'email_error' => '',
  		  'password_error' => '',
  		  'confirm_password_error' => ''
  		];
  		// load view
  		$this->view('users/register', $data);
  	  }

  	}

  	public function login(){
  	  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  		// process form

      // Sanitize POST Data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      //Init data for Register
      $data = [

        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_error' => '',
        'password_error' => ''
      ];

      //auto generate dataarray construction
      $genArray = array(
        'email' => $data['email'],
        'password' => $data['password']
      );

      //Generate Data Array for validation
      $generatedArray = $this->generateDataArray($genArray);

      //Sending prepared data array to validation algorythm
      $validatedArray = $this->validateData($generatedArray);
      //Final validation to check there are no errors

      //Check for user/email
      $this->loggedInUser = '';
      if ($this->userModel->findUserByEmail($validatedArray['email']['data'])) {
        // User found
        //Check and set Logged in User
        $loggedInUser = $this->userModel->login($validatedArray['email']['data'], $validatedArray['password']['data']);
        if ($loggedInUser) {
          $this->loggedInUser = $loggedInUser;
        }else{
          //Incorrect Password
          $validatedArray['password']['error'] = 'Incorrect Password!';
        }
      }else{
        //User Not Found
        $validatedArray['email']['error'] = 'No user found!';
      }

      if (
      empty($validatedArray['email']['error']) &&
      empty($validatedArray['password']['error']) && !empty($this->loggedInUser)
      ){
        //Unset $data
        unset($data);
        //Validated Logged User
        // Create Session
        $this->createUserSession($this->loggedInUser);

      }else{
        //Mapping values with $validateArray and $data array
        $data['email_error'] = $validatedArray['email']['error'];
        $data['password_error'] = $validatedArray['password']['error'];
        //Load View with errors
        $this->view('users/login',$data);
      }

  	  }else{
  		//Init Data
  		$data = [
  		  'email' => '',
  		  'password' => '',
  		  'email_error' => '',
  		  'password_error' => ''
  		];
  		// load view
  		$this->view('users/login', $data);
  	  }

  	}

    public function validateData($data){

      foreach ($data as $key => $value) {
        switch ($key) {
          case 'name':
            if (empty($data['name']['data'])){
              $data['name']['error'] = 'Please enter your name';
            }else{
              $data['name']['error'] = '';
            }
            break;

          case 'email':
            if (empty($data['email']['data'])){
            $data['email']['error'] = 'Please enter email address';
          }else{
            $data['email']['error'] = '';
          }
            break;

          case 'password':
            if (empty($data['password']['data'])){
              $data['password']['error'] = 'Please enter your password';
            }else if (strlen($data['password']['data']) < 6){
                $data['password']['error'] = 'Minimum 6 characters are required';
            }else{
              $data['password']['error'] = '';
            }
            break;

          case 'confirm_password':
            if (empty($data['confirm_password']['data'])) {
              $data['confirm_password']['error'] = 'Please confirm password';
            }else if ($data['password']['data'] != $data['confirm_password']['data']) {
              $data['confirm_password']['error'] = 'Passwords did not match';
            }else{
              $data['confirm_password']['error'] = '';
            }
            break;

          default:
            // code...
            break;
        }
      }
      return $data;
    }

    public function generateDataArray($dataArray){

      $generatedArray = array();
      foreach ($dataArray as $key => $value) {
        $generatedArray[$key] = array(
          'name' => $key,
          'data' => $value,
          'error' => $key.'_error'
        );
      }
      return $generatedArray;
    }

    public function createUserSession($user){
      $_SESSION['user_id'] = $user->id;
      $_SESSION['user_email'] = $user->email_address;
      $_SESSION['user_name'] = $user->name;
      redirect('pages/index');
    }

    public function logout(){
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      session_destroy();
      redirect('users/login');
    }

    public function isLoggedIn(){
      if (isset($_SESSION['user_id'])) {
        return true;
      }else{
        return false;
      }
    }
  }
