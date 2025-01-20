<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <!-- Logged-in user icon -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path
                                    d="M13.468 12.37C12.758 11.226 11.512 10.5 10 10.5s-2.758.726-3.468 1.87A6.96 6.96 0 0 0 8 15c.69 0 1.366-.098 2-.283a6.96 6.96 0 0 0 3.468-2.347zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0zm4.5-7a7 7 0 1 0 0 14A7 7 0 0 0 8 1z" />
                            </svg>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="user_products.php">My products</a></li>
                            <li><a class="dropdown-item" href="profile.php">My profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" id="logout-button">Logout</a>
                            </li>

                        </ul>
                    </li>
                    <?php else: ?>
                    <!-- Login button for guests -->
                    <li class="nav-item">
                        <a href="login.php" id="login-link" class="btn btn-primary btn-sm">Login</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/logout.js" type="module"></script>
</body>

</html>