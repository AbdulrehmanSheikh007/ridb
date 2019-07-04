<?php
include("dbs.php");
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: login.php');
}
$error = array();
if (isset($_POST['fullname']) && !empty($_POST['fullname']) && isset($_POST['admin_email']) && !empty($_POST['admin_email']) && isset($_POST['api_access_key']) && !empty($_POST['api_access_key'])) {

    $sql = "UPDATE users SET fullname='" . $_POST['fullname'] . "', admin_email='" . $_POST['admin_email'] . "',api_access_key='" . $_POST['api_access_key'] . "'  WHERE id='" . $_SESSION['id'] . "'";
    if ($conn->query($sql)) {
        $error = array('status' => 1, 'message' => 'Record have been updated successfully.');

        $_SESSION['fullname'] = $_POST['fullname'];
        $_SESSION['api_access_key'] = $_POST["api_access_key"];
        $_SESSION['admin_email'] = $_POST["admin_email"];

        unset($_POST['fullname']);
        unset($_POST['admin_email']);
        unset($_POST['api_access_key']);
    }
}

$sql = "SELECT * FROM users WHERE email='" . $_SESSION['email'] . "' AND password='" . $_SESSION['password'] . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
include 'header.php';
?>

<div class="container">
    <h3 class="text-center"><span class="glyphicon glyphicon-cog"></span> Settings</h3>
    <form action="" method="post">
        <?php
        if (count($error) > 0 && $error['status'] == 1) {
            ?>
            <div class="alert alert-success"><?php echo $error['message']; ?></div>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="fullname">Full Name:</label>
            <input type="text" class="form-control" id="fullname" value="<?php echo $row['fullname']; ?>" name="fullname" required placeholder="Enter full name">
        </div>
        <div class="form-group">
            <label for="admin_email">Admin Email:</label>
            <input type="email" class="form-control" id="admin_email" value="<?php echo $row['admin_email']; ?>"  name="admin_email" required placeholder="Enter admin email">
        </div>
        <div class="form-group">
            <label for="api_access_key">API Access Key:</label>
            <input type="text" class="form-control" id="api_access_key"  value="<?php echo $row['api_access_key']; ?>" name="api_access_key" required placeholder="Enter API Access Key">
        </div>
        <button type="submit" class="btn btn-default">Update</button>
    </form>
</div>

</body>
</html>
