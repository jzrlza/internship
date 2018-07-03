<?php

class Login {
  public function __construct($db) {
    $this->db = $db;
  }
  /**
   * Functions
   */
  public function authenticate($input){
    try {
      $user_name = $input['user_name'];
      $password = $input['password'];
      $db = $this->db->get();
      $result = $db->fetchAll("SELECT * FROM users WHERE user_name = ? AND password = ?", [$user_name, $password]);
      //Prevent SQL Injection, where hackers can access all the users, which is dangerous

      if (count($result) == 0) {
        throw new Exception("Invalid Username or Password", 401);
      } else if ($result[0]["banned"] == '1') {
        throw new Exception("User is banned :(", 403);
      } else {
        return $result[0];
      } 
    } catch (Exception $e) {
      return $e;
    }
  }

  public function register($input) {
    try {
      //Password Match?
      if ($input['password'] !== $input['password_confirm']) {
        throw new Exception("Password Mismatch", 400);
      }

      //No duplicate user_name, email allowed
      $user_name = $input['user_name'];
      $email = $input['email'];
      $db = $this->db->get();
      $found = $db->fetchAll("SELECT * FROM users WHERE user_name = ? OR email = ?", [$user_name, $email]);
      //Prevent SQL Injection, where hackers can access all the users, which is dangerous
      if (count($found) > 0) {
        throw new Exception("Either User or E-mail or Both already exist", 409);
      }

      //Upload the profile image into the database
      if ($_FILES["image_upload"]["name"] !== '') {
        $image_uploaded = $_FILES["image_upload"]["tmp_name"];

        //Convert this image into base64 LARGETEXT
        //save to image_src, file must be at most 1 MB
        $data = file_get_contents($image_uploaded);
        $type = pathinfo($_FILES["image_upload"]["name"], PATHINFO_EXTENSION);
        $image_src = "data:image/" . $type . ";base64,". base64_encode($data);
      } else {
        $image_src = null;
      }

      //The real insertion
      $user_data = [
        'user_name' => $input['user_name'],
        'password' => $input['password'],
        'email' => $input['email'],
        'full_name' => "",
        'nick_name' => "",
        'image_src' => $image_src
      ];
      $db->insert('users', $user_data);

      return $user_data;

    } catch (Exception $e) {
      return $e;
    }
  }
}
