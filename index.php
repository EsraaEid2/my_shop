<?php 
session_start();
$title = "Cake Shop";
require_once('controls/header.php'); 
?>
     <!-- Hero Section -->
     <section class="hero" id="home">
        <div class="hero-content">
            <h1>Welcome to Cake Shop</h1>
            <p>Handcrafted cakes for your special moments</p>
        </div>
    </section>


<?php 
require_once('controls/footer.php');
?>

<!-- Bootstrap 5 JS (No jQuery required) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap 5 -->
<script src="assets/js/logout.js" type="module"></script>

</body>

</html>
