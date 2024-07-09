<?php

  include "../classes/user.php";

  # Create an object
  $user = new User();

  # Call the register method
  $user->store($_POST);
  # $_POST --> holds the data coming from the form
  # Data: firstnamne, lastname, username and password
  #$_POST['first_name' => 'John','last_name' => 'Doe', 'username' => 'johndoe', 'password' => 'password123']