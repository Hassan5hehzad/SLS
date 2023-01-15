<?php include '../includes/header.php'; ?>
<?php include '../includes/session.php'; ?>
<?php
if (isset($_POST['details_class_btn'])) {
    $class_subject_id = $_POST['details_class_btn'];
    $_SESSION['current_class'] = $_POST['details_class_btn'];
    $_SESSION['class_name'] = $_POST['class_name'];
    $school_name = $_SESSION['school_name'];
    $class_name = $_POST['class_name'];
}
if (isset($_SESSION['current_class'])) {
    $class_subject_id = $_SESSION['current_class'];
    $class_name = $_SESSION['class_name'];
    $school_name = $_SESSION['school_name'];
}
$conn = new PDO('mysql:host=localhost;dbname=sls', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare('SELECT * FROM sls_teachers');
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $row) {
    $teacherid = $row['id'];
    $_SESSION['teacherid'] = $row['id'];
    $teachername = $row['teacher_name'];
    $_SESSION['teacher_name'] = $row['teacher_name'];
}
?>

<body>
  <?php include '../includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span9" id="content">
        <?php
        if (isset($_SESSION['error'])) {
            echo "
                <div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                  <h4><i class='icon fa fa-warning'></i> Error!</h4>
                  " .
                $_SESSION['error'] .
                "
                </div>
              ";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "
                <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                  " .
                $_SESSION['success'] .
                "
                </div>
              ";
            unset($_SESSION['success']);
        }
        if (isset($class_subject_id)) {

            $conn = new PDO('mysql:host=localhost;dbname=sls', 'root', '');
            $sql = 'SELECT * FROM sls_teachers';
            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $teachers_rows = $stmt->fetchAll();
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
            $teachers_selection =
                "<select name='teacher' class='custom-select'>";
            $teachers_selection =
                $teachers_selection .
                "<option value='-1'>Select Teacher</option>";
            foreach ($teachers_rows as $teacher_record) {
                $teachers_selection =
                    $teachers_selection .
                    "<option value='" .
                    $teacher_record['id'] .
                    "'>" .
                    $teacher_record['teacher_name'] .
                    '</option>';
            }
            $teachers_selection = $teachers_selection . '</select>';
            $stmt = $conn->prepare(
                "SELECT sls_subjects.*, sls_teachers.teacher_name
              FROM sls_subjects
              LEFT JOIN sls_teachers
              ON sls_subjects.teacher_id = sls_teachers.id
              WHERE sls_subjects.class_id = $class_subject_id;
              "
            );
            $stmt->execute();
            $rows = $stmt->fetchAll();
            ?>
        <div class="row-fluid"><br>
          <a href="classes.php" class="btn btn-info"><i class="icon-plus-sign icon-large"></i> Back to Classes</a>
          <a href="add_subjects.php" class="btn btn-info" name="addnew_subject" value='"<?php $teacherid; ?>"'> Add Subjects</a>

          <!-- block -->
          <div id="block_bg" class="block">
            <div class="navbar navbar-inner block-header">
              <?php echo '' . $school_name . ' > ' . $class_name; ?>
              <!-- <div class="muted pull-left">Subjects List</div> -->
            </div>
            <div class="block-content /*collapse in*/">
              <div class="span12">
                <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                  <thead>
                    <tr>

                      <th>Subject Name</th>
                      <!-- <th>Email</th> -->
                      <th width=30%>Teacher</th>
                      <th>Chapters</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($rows as $row) {
                        $id = $row['id'];
                        $subject_name = $row['subject_name'];
                        $teacher_details = "<form action='edit_subject.php' method='POST'>
                            <button  type='submit' class='btn btn-warning' value='$id' name='edit_subject'>Add Teacher</button>
                          </form>";
                        if (!is_null($row['teacher_name'])) {
                            $teacher_details = $row['teacher_name'];
                        }
                        echo " 
                            <tr> 
                              <td>" .
                            $row['subject_name'] .
                            "</td>
                             <td> 
                              " .
                            $teacher_details .
                            "
                              <td> 
                                  <form action='chapters.php' method='POST'>
                                    <input type='hidden' name='subject_name' value='$subject_name'>
                                    <button  type='submit' class='btn btn-primary' value='$id' name='details_subject_btn'>Chaptes</button>
                                  </form>                                    
                                  </td><td>
                                   <form action='delete_subject.php' method='POST'>       
                                    <button  type='submit' class='btn btn-danger' value='$id' name='delete_subject'> <i class='bi bi-trash'></i></button>  
                                  </form>
                                  <form action='edit_subject.php' method='POST'>
                                    <button  type='submit' class='btn btn-success' value='$id' name='edit_subject'><i class='bi bi-pencil-square'></i></button>
                                  </form>

                              </td>
                            </tr>
                        ";
                    }
        }
        ?>
    <?php
    $sql = 'SELECT * FROM sls_teachers';
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    ?>
                  </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /block -->
    </div>
    <?php
//include('footer.php');
?>
  </div>
  <?php include '../includes/script.php'; ?>
</body>

</html>