<?php
session_start();

include('conn.php');

if (isset($_POST['registerUser'])) {
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  $pass = $_POST['pass1'];
  $confirmPass = $_POST['pass2'];

  if ($pass == $confirmPass) {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $addUser = $conn->prepare("INSERT INTO users (u_fname, u_lname, u_email, u_pass) VALUES(?, ?, ?, ?)");
      $addUser->execute([
          $fname,
          $lname,
          $email,
          $hash
      ]);

      $msg = "User registered succesfully!";
      header("Location: register.php?msg=$msg");
  } else {
      $msg = "Password do not match!";
      header("Location: register.php?msg=$msg");
  }
}

if (isset($_POST['login'])) {
  $u_email = $_POST['email'];
  $u_pass = $_POST['pass'];

  $getData = $conn->prepare("SELECT * FROM users WHERE u_email = ?");
  $getData->execute([$u_email]);

  foreach ($getData as $data) {
      if ($data['u_email'] == $u_email && password_verify($u_pass, $data['u_pass'])) {
          $_SESSION['logged_in'] = true;
          $_SESSION['u_id'] = $data['u_id'];

          $msg = "User logged-in successfully!";
          header("Location: index.php?msg=$msg");
      } else {
          $msg = "Email or Password do not match";
          header("Location: login.php?msg=$msg");
      }
  }
}

// for logout
if (isset($_GET['logout'])) {
  session_start();
  unset($_SESSION['logged_in']);
  unset($_SESSION['user_id']); 
  
  header("Location: login.php");
}

if(isset($_POST['delete_student']))
{
    $personal_info_id = $_POST['delete_student'];

  try {

    $query = "DELETE FROM personal_info WHERE id=:personal_info_id";
    $statement = $conn->prepare($query);
    $data = [':personal_info_id' => $personal_info_id
    ];
    $query_execute = $statement->execute($data);

    if($query_execute)
    {
      $_SESSION['message'] = "Deleted Successfully";
      header('location: index.php');
      exit(0);
    }
    else 
    {
      $_SESSION['message'] = "Not Deleted";
      header('location: index.php');
      exit(0);
    }

  }catch(PDOException $e){
    echo $e->getMessage();
  }
}

if(isset($_POST['update_student_btn']))
{
  $personal_info_id = $_POST['personal_info_id'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $address = $_POST['address'];
  $age = $_POST['age'];

  try{
        $query = "UPDATE personal_info SET fname=:fname, lname=:lname, address=:address, age=:age WHERE id=:personal_info_id LIMIT 1";
        $statement = $conn->prepare($query);
        $data = [
        ':fname' =>   $fname,
        ':lname' =>   $lname,
        ':address' =>   $address,
        ':age' =>   $age,
        ':personal_info_id' => $personal_info_id,

      ]; 
      
      $query_execute = $statement->execute($data);

      if($query_execute)
      {
        $_SESSION['message'] = "Updated Successfully";
        header('location: index.php');
        exit(0);
      }
      else 
      {
        $_SESSION['message'] = "Not Updated";
        header('location: index.php');
        exit(0);
      }

  }catch (PDOException $e) {
    echo $e->getMessage();
  }
}

if(isset($_POST['add_student_btn']))
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $age = $_POST['age'];

    $query = "INSERT INTO personal_info (fname, lname, address, age) VALUES (:fname, :lname, :address, :age)";
    $query_run = $conn->prepare($query);

    $data = [
        ':fname' => $fname,
        ':lname' => $lname,
        ':address' => $address,
        ':age' => $age,
    ];
    $query_execute = $query_run->execute($data);

    if($query_execute)
    {
        $_SESSION['message'] = "Added Successfully";
        header('location: index.php');
        exit(0);
    }
    else 
    {
        $_SESSION['message'] = "Not Added";
        header('location: index.php');
        exit(0);
    }
}
?>