<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet">
    <!-- Custom font -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?php echo $title ?? 'Cake Shop'; ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary custom-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">Cake Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="logout.php" id="logout-link" class="btn btn-danger btn-sm">Logout</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" id="login-link" class="btn btn-primary btn-sm">Login</a>
                    </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    <script src="assets/js/logout.js" type="module"></script>
</body>

</html>