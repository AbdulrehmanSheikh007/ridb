<?php
include("dbs.php");
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: login.php');
}
$error = array();
if (isset($_POST['new_password']) && !empty($_POST['new_password']) && isset($_POST['confirm_password']) && !empty($_POST['confirm_password']) && isset($_POST['old_password']) && !empty($_POST['old_password'])) {
    if ($_POST['new_password'] == $_POST['confirm_password']) {

        $sql = "SELECT * FROM users WHERE email='" . $_SESSION['email'] . "' AND password='" . $_POST['old_password'] . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $sql = "UPDATE users SET password='" . $_POST['new_password'] . "'  WHERE id='" . $_SESSION['id'] . "'";

            if ($conn->query($sql)) {
                $error = array('status' => 1, 'message' => 'Password have been updated successfully.');
                $_SESSION['password'] = $_POST['new_password'];
                unset($_POST['password']);
                unset($_POST['confirm_password']);
            }
        } else {
            $error = array('status' => 0, 'message' => 'Old password is incorrect.');
            unset($_POST['new_password']);
            unset($_POST['old_password']);
            unset($_POST['confirm_password']);
        }
    } else {
        $error = array('status' => 0, 'message' => 'Password does not match.');
        unset($_POST['new_password']);
        unset($_POST['old_password']);
        unset($_POST['confirm_password']);
    }
}
include 'header.php';
?>

<div class="container">
    <h3 class="text-center"><span class="glyphicon glyphicon-key"></span> Password Reset</h3>
    <form action="" method="post">
        <?php
        if (count($error) > 0 && $error['status'] == 1) {
            ?>
            <div class="alert alert-success"><?php echo $error['message']; ?></div>
            <?php
        }
        ?>
        <?php
        if (count($error) > 0 && $error['status'] == 0) {
            ?>
            <div class="alert alert-danger"><?php echo $error['message']; ?></div>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="old_password">Old Password:</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required placeholder="Enter old password">
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Enter new password">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm Password">
        </div>

        <button type = "submit" class = "btn btn-default">Update</button>
    </form>
</div>

</body>
</html>
