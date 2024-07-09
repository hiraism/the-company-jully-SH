<?php

  require_once "Database.php";

  # the logic of the app will here:

  class User extends Database {
    public function store($request){
      $first_name = $request['first_name'];
      $last_name = $request['last_name'];
      $username = $request['username'];
      $password = $request['password'];

      $password = password_hash($password, PASSWORD_DEFAULT);
      # admin12345 --> aiw5877(8*7$23...
      # Query string
      $sql = "INSERT INTO users (`first_name`, `last_name`, `username`, `password`) VALUES ('$first_name', '$last_name', '$username', '$password')";

      #Execute the query
      if($this->conn->query($sql)){
        header('location: ../views'); //go to index.php (login page)
        exit(); //same as die()
      } else {
        die("Error in creating the user: " . $this->conn->error);
      }
  }

  public function login($request){
    $username = $request['username'];
    $password = $request['password'];
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $this->conn->query($sql);
    if($result->num_rows == 1){
      $user = $result->fetch_assoc();
      if(password_verify($password, $user['password'])){
        session_start();
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['first_name'] . " " . $user['last_name'];

        header('location: ../views/dashboard.php');
        exit();
      } else {
        die("Password is incorrect.");
      }
    } else {
      die("Username does not exist.");
    }
  }

  # logout
  public function logout(){
    session_start();   //start the session
    session_unset();   //unset the session
    session_destroy(); //delete or destroy the session

    header('location: ../views'); //Redirect the user to login page
    exit();
  }
  # Get or retreive all teh users frome the users table
  public function getAllUsers(){
    $sql = "SELECT id, first_name, last_name, username, photo FROM users";
    if($result = $this->conn->query($sql)){
      return $result;
    }else{
      die("Error retrieving users: " . $this->conn->error);
    }

  }
  public function getUser($id){

    $sql = "SELECT * FROM users WHERE id = $id";

    if($result = $this->conn->query($sql)){
      return $result->fetch_assoc();
    }else{
      die("Error in retrieving the user: " . $this->conn->error);
    }
  }
  public function update($request, $files){
    session_start();
    $id = $_SESSION['id'];

    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $username = $request['username'];

    $photo = $files['photo']['name'];
    $tmp_photo = $files['photo']['tmp_name'];

    # Sql query string
    $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = $id";

    # Execute the query
    if ($this->conn->query($sql)) {
        $_SESSION['username'] = $username;
        $_SESSION['full_name'] = "$first_name $last_name";

        # Check if there is an uploaded image/photo, save it to the Db and save the file to images folder
        if ($photo) { //true or false?
            $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
            $destination = "../assets/images/$photo";

            if ($this->conn->query($sql)) { //save the image to the Db
                # Save the file to the image folder
                if (move_uploaded_file($tmp_photo, $destination)) { //is okay?
                    header('location: ../views/dashboard.php');
                    exit;
                }else {
                    die("Error in moving the photo.");
                }
            }else {
                die("Error in uploading image. " . $this->conn->error);
            }

        }

        header('location: ../views/dashboard.php');
        exit;
    }else {
        die("Error in updating the user. " . $this->conn->error);
    }
    }

    public function delete(){
      session_start();
      $id = $_SESSION['id'];
      $sql = "DELETE FROM users WHERE id = $id";
      if($this->conn->query($sql)){
        $this->logout();
      }else{
        die("Error in deleting your account: " . $this->conn->error);
      }

  } 
    
}
?>