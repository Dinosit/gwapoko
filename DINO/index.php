<?php
include 'header.php'; 
include('conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <title>Home</title>
</head>
<body>
    <div class="container">
        <div class="row">
              <div class="col-md-8 mt-4">

                <?php if(isset( $_SESSION['message'])): ?>
                      <h5 class="alert alert-success"> <?= $_SESSION['message']; ?></h5>
                <?php 
                unset($_SESSION['message']);
                endif; ?>

              <div class="card">
                <div class="card-header">
                <h3>Student Info
                <a href="personalinfo-add.php" class="btn btn-primary float-end">Add Student</a></h3>
                </div>

                <div class="card-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                      <tbody>
                        <?php  
                         $query =  "SELECT * FROM personal_info";
                         $statement = $conn->prepare($query);
                         $statement->execute();
                         $statement->setFetchMode(PDO::FETCH_OBJ);
                         $result = $statement->fetchAll();
                         if($result)
                         {
                            foreach($result as $row)
                            {
                                ?>
                                <tr>
                            <td><?= $row->id;?></td>
                            <td><?= $row->fname;?></td>
                            <td><?= $row->lname;?></td>
                            <td><?= $row->address;?></td>
                            <td><?= $row->age;?></td>
                            <td>
                              <a href="edit.php?id=<?= $row->id;?>" class="btn btn-primary">Edit</a>
                            </td>
                          <td>
                            <form action="process.php" method="POST">
                              <button type="submit" name="delete_student" value="<?=$row->id;?>" class="btn btn-danger">Delete</button>
                            </form>
                          </td>
                                </tr>
                                <?php 
                            }
                         }
                         else
                         {
                          ?>
                          <tr>
                            <td colspan="5">No Record Found</td>
                          </tr>
                          <?php
                          
                         }
                        ?>
                        <tr>
                          <td></td>
                        </tr>
                      </tbody>
                  </table>
          
                </div>
              </div>
              </div>
        </div>
    </div>
    
</body>
</html>