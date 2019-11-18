<?php

/**
 *
 */
class User{
  private $db;

  public function __construct(){
    $this->db = new Database;
  }

  //Find user by email
  public function findUserByEmail($email){
    $sql = 'SELECT * FROM users WHERE email_address = :email';
    $this->db->query($sql);
    $this->db->bind(':email', $email);
    $row = $this->db->singleResult();

    //Check row
    if ($this->db->rowCount() > 0) {
      return true;
    }else{
      return false;
    }
  }

  //Register User
  public function registerUser($data){
    $sql = 'INSERT INTO users (name, email_address, password) VALUES (:name, :email, :password)';
    $this->db->query($sql);
    //Bind Values
    $this->db->bind(':name', $data['name']['data']);
    $this->db->bind(':email', $data['email']['data']);
    $this->db->bind(':password', $data['password']['data']);

    //Execute Query
    if ($this->db->execute()) {
      return true;
    }else{
      return false;
    }
  }

  public function login($email, $password){
    $sql = 'SELECT * FROM users WHERE email_address = :email';
    $this->db->query($sql);
    $this->db->bind(':email', $email);
    $row = $this->db->singleResult();

    $hashedPassword = $row->password;
    if (password_verify($password, $hashedPassword)) {
      return $row;
    }else{
      return false;
    }
  }
}
