<?php

require_once "User.php";


class NewUser {
  public $user_name;
  public $user_salt;
  public $user_pass; //encrypted
  public $user_email;

  public function __construct($name, $raw_pass, $email) {
    $this->user_name = $name;
    $this->user_salt = rand(1,100000);
    $this->user_pass = crypt($raw_pass, $this->user_salt);
    $this->user_email = $email;
  }


  public function name_is_unique($conn) {
    $query = "
      SELECT user_name
      FROM users
      WHERE user_name = '" . $this->user_name . "'";
    $result = $conn->query($query);
    $is_unique = ($result->num_rows == 0);
    return $is_unique;
  }

  public function enter_user($conn) {
    if ($this->name_is_unique($conn)) {

      $values = "('" . $this->user_name . "', '" . $this->user_pass .
        "', '" . $this->user_salt . "', '" . $this->user_email .  "', (SELECT NOW()), (SELECT NOW()) )";

      $query = "
      INSERT INTO users (user_name, user_pass, user_salt, user_email, user_date, last_active)
      VALUES " . $values;

      $conn->query($query);
      $id = $conn->insert_id;
      return User::from_id($conn, $id);
    }
    else {
      return False;
    }
  }


}

 ?>
