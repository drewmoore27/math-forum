<?php


class User {


  public $user_id;
  public $user_name;
  public $user_pass;
  public $user_email;
  public $user_date;
  public $last_active;
  public $pw_is_hashed;
  public $responses_checked_at;


  public static function from_name($conn, $name) {
    $user = new self();
    $user->fill_from_name($conn, $name);
    return $user;
  }

  public static function from_id($conn, $id) {
    $user = new self();
    $user->fill_from_id($conn, $id);
    return $user;
  }

  protected function fill_from_name($conn, $name) {
    $query = "
      SELECT user_id, user_name, user_pass, user_email,
       user_date, last_active, pw_is_hashed, user_salt,
       responses_checked_at
      FROM users
      WHERE user_name = '" . $name . "'";
    $result = $conn->query($query);
    if (!$result) {
            echo $query;
            die($conn->error);
        }
    $row = $result->fetch_assoc();
    $this->fill_from_row($row);
  }

  protected function fill_from_id($conn, $id) {
    $query = "
      SELECT
        user_id, user_name, user_pass, user_email,
        user_date, last_active, pw_is_hashed, user_salt,
        responses_checked_at
      FROM users
      WHERE user_id =
    " . $id;
    $result = $conn->query($query);
        if (!$result) {
            echo $query;
            die($conn->error);
        }
    $row = $result->fetch_assoc();
    $this->fill_from_row($row);
  }

  protected function fill_from_row($row) {
    $this->user_id = $row['user_id'];
    $this->user_name = $row['user_name'];
    $this->user_pass = $row['user_pass'];
    $this->user_email = $row['user_email'];
    $this->user_date = $row['user_date'];
    $this->last_active = $row['last_active'];
    $this->pw_is_hashed = $row['pw_is_hashed'];
    $this->user_salt = $row['user_salt'];
    $this->responses_checked_at = $row['responses_checked_at'];
  }




  public function is_password($pass) {
    if ($this->pw_is_hashed) {
      $pass_check = crypt($pass, $this->user_salt);
    }
    else {
        $pass_check = $pass;
    }
    return ($pass_check = $this->user_pass);
  }

  public function update_password($conn, $pass) {
    $new_salt = rand(1,1000000);
    $new_pass = crypt($pass, $new_salt);
    $query = "
      UPDATE users
      SET user_pass = '" . $new_pass . "',
      SET user_salt = " . $new_salt . ",
      SET pw_is_hashed = 1
      WHERE user_id = " . $this->user_id;
    $conn->query($query);
    $this->user_pass = $new_pass;
    $this->user_salt = $new_salt;
    $this->pw_is_hashed = 1;
  }

  public function update_email($conn, $email) {
    $query = "
      UPDATE users
      SET user_email = '" . $email . "'
      WHERE user_id = " . $this->user_id;
    $conn->query($query);
    $this->user_email = $email;
  }

  public function update_last_active($conn) {
    $query = "
      UPDATE users
      SET last_active = (SELECT NOW())
      WHERE user_id = " . $this->user_id;
    $result = $conn->query($query);
    if (!$result) {
        echo $query;
        die($conn->error);
    }
    $query2 = "
      SELECT last_active
      FROM users
      WHERE USER_ID = " . $this->user_id;
    $result = $conn->query($query2);
    if (!$result) {
        echo $query;
        die($conn->error);
    }
    $row = $result->fetch_assoc();
    $active = $row['last_active'];
    $this->last_active = $active;
  }

  public function check_update_password($conn, $current_pass, $pass1, $pass2) {
    if (!$this->is_password($current_pass)) {
      return 0;   // if wrong password entered, return false
    }
    if ($pass1 == $pass2) {
      $this->update_password($conn, $pass1);
      return 1; //password updated!
    }
    else {
      return 0; //passwords weren't the same
    }
  }

  public static function user_exists($conn,$name) {
    $query = "
      SELECT user_name
      FROM users
      WHERE user_name = '" . $name . "'";
    $result = $conn->query($query);
    if (!$result) {
        echo $query;
        die($conn->error);
    }
    return ($result->num_rows != 0);
  }

  public function new_response_id_list($conn) {
    $query = "select r.response_id as id
      from responses r
      left join responses_top rt
      on rt.response_top_id = r.response_id
      left join responses r1
      on r1.response_id = rt.response_parent
      left join users u1
      on u1.user_id = r1.response_author
      left join responses_below rb
      on rb.response_below_id = r.response_id
      left join responses r2
      on r2.response_id = rb.response_parent
      left join users u2
      on u2.user_id = r2.response_author
      where (u1.user_id = " . $this->user_id
      . " and u1.responses_checked_at < r.response_date)
        or (u2.user_id = " . $this->user_id
        . " and u2.responses_checked_at < r.response_date)
      order by r.response_date desc";
    $result = $conn->query($query);
    if (!$result) {
        echo $query;
        die($conn->error);
    }
    $id_array = array();
    while ($row=$result->fetch_assoc()) {
      $id_array[] = $row['id'];
    }
    return $id_array;
  }

  public function old_response_id_list($conn) {
    $query = "select r.response_id
      from responses r
      left join responses_top rt
      on rt.response_top_id = r.response_id
      left join responses r1
      on r1.response_id = rt.response_parent
      left join users u1
      on u1.user_id = r1.response_author
      left join responses_below rb
      on rb.response_below_id = r.response_id
      left join responses r2
      on r2.response_id = rb.response_parent
      left join users u2
      on u2.user_id = r2.response_author
      where (u1.user_id = " . $this->user_id
      . " and u1.responses_checked_at > r.response_date)
        or (u2.user_id = " . $this->user_id
        . " and u2.responses_checked_at > r.response_date)
      order by r.response_date desc";
    $result = $conn->query($query);
    if (!$result) {
        echo $query;
        die($conn->error);
    }
    $id_array = array();
    while ($row=$result->fetch_assoc()) {
      $id_array[] = $row['id'];
    }
    return $id_array;

  }




}

 ?>
