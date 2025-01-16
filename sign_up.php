<?php 
$title = "Cake Shop | Sign up";
require_once('controls/header.php'); 
?>
<div class ="shop-container">
<div class="container mt-5">
    <h2>User Registration</h2>
    <form id="registrationForm">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder= "First Name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" id="last_name" name="last_name"placeholder= "Last Name"  required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <input type="email" id="email" name="email" placeholder= "Email" required>
        </div>
        <div class="form-group">
            <input type="password" id="password" name="password" placeholder= "Password" required>
        </div>
        <div class="form-group">
            <input type="password" id="cpassword" name="cpassword" placeholder= "Confirm Password" required>
        </div>
        <button type="submit" class="btn">Sign up</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>
</div>

<?php 
require_once('controls/footer.php');
?>

<script src="assets/js/sign_up.js" type="module"></script>

</body>

</html>