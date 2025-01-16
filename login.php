<?php 
$title = "Cake Shop | Login";
require_once('controls/header.php'); 
?>
<div class="shop-container">
    <div class="container mt-5">
        <h2>User Login</h2>
        <form id="LoginForm">
            <div class="form-group">
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <a href="sign_up.php">Don't have an account? Sign Up</a>
    </div>
</div>
<?php 
require_once('controls/footer.php');
?>

<!-- Only use Bootstrap 5 (without jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="assets/js/login.js" type="module"></script>

</body>

</html>