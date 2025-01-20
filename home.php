<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Delights Bakery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>

        /* Header Styles */
        .header {
            background-color: white;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                url('/api/placeholder/1200/800');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding-top: 80px;
        }

        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        /* Featured Products */
        .featured {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .featured h2 {
            text-align: center;
            font-family: 'Playfair Display', serif;
            margin-bottom: 3rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            height: 200px;
            background: #f0f0f0;
        }

        .product-info {
            padding: 1.5rem;
        }

        /* About Section */
        .about {
            background-color: var(--accent-color);
            padding: 5rem 2rem;
        }

        .about-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        /* Video Section */
        .video-section {
            padding: 5rem 2rem;
            text-align: center;
            background-color: white;
        }

        .video-container {
            max-width: 800px;
            margin: 2rem auto;
            aspect-ratio: 16/9;
            background: #f0f0f0;
        }

        /* Contact Section */
        .contact {
            background-color: var(--light-yellow);
            padding: 5rem 2rem;
            text-align: center;
        }

        .contact-form {
            max-width: 600px;
            margin: 2rem auto;
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .social-links {
            margin: 1rem 0;
        }

        .social-links a {
            color: white;
            margin: 0 1rem;
            font-size: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-content {
                grid-template-columns: 1fr;
            }
            
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <div class="logo">Sweet Delights</div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#products">Products</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Welcome to Sweet Delights</h1>
            <p>Handcrafted cakes for your special moments</p>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured" id="products">
        <h2>Our Featured Cakes</h2>
        <div class="products-grid">
            <div class="product-card">
                <div class="product-image">
                    <img src="/api/placeholder/250/200" alt="Wedding Cake">
                </div>
                <div class="product-info">
                    <h3>Wedding Cakes</h3>
                    <p>Perfect for your special day</p>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="/api/placeholder/250/200" alt="Birthday Cake">
                </div>
                <div class="product-info">
                    <h3>Birthday Cakes</h3>
                    <p>Celebrate in style</p>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="/api/placeholder/250/200" alt="Custom Cake">
                </div>
                <div class="product-info">
                    <h3>Custom Cakes</h3>
                    <p>Your imagination, our creation</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-content">
            <div class="about-text">
                <h2>Our Story</h2>
                <p>With over 20 years of experience in creating delightful cakes, we put love and dedication into every creation.</p>
            </div>
            <div class="about-image">
                <img src="/api/placeholder/400/300" alt="Our Bakery">
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section">
        <h2>Watch Our Baking Process</h2>
        <div class="video-container">
            <!-- Replace with actual video embed -->
            <div style="background: #f0f0f0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                Video Placeholder
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2>Get in Touch</h2>
        <div class="contact-form">
            <form>
                <input type="text" placeholder="Your Name" style="width: 100%; margin-bottom: 1rem; padding: 0.5rem;">
                <input type="email" placeholder="Your Email" style="width: 100%; margin-bottom: 1rem; padding: 0.5rem;">
                <textarea placeholder="Your Message" style="width: 100%; margin-bottom: 1rem; padding: 0.5rem; height: 150px;"></textarea>
                <button style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 2rem; border-radius: 4px; cursor: pointer;">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <h3>Sweet Delights Bakery</h3>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-pinterest"></i></a>
            </div>
            <p>&copy; 2025 Sweet Delights. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>