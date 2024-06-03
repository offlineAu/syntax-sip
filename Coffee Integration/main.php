<?php
session_start();

// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_fullname = $_SESSION['user_fullname'] ?? 'Signup';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main1.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Finger Paint|Inter">
    <title>Main Page</title>

</head>
<body>
    <div class="container">
        <nav class="custom-navbar">
            <div class="navbar-header">
                <img src="/Coffee%20Integration/Logo_Designs.png" class="logo" alt="Logo">
            </div>
            <ul class="navbar-nav">
                <li><a href="#">Home</a></li>
                <li><a href="#">Menu</a></li>
                <li><a href="#">Deliver</a></li>
                <li><a href="#">About</a></li>
                <li>
                    <?php if ($user_fullname === 'Signup'): ?>
                        <a href="login.php">Signup</a>
                    <?php else: ?>
                        <div class="dropdown">
                            <span class="dropbtn" onclick="toggleDropdown()"> <!-- Add onclick event -->
                                <?php echo htmlspecialchars($user_fullname); ?> &#9662;
                            </span>
                            <div class="dropdown-content" id="dropdownContent"> <!-- Add an id to the dropdown content -->
                                <a href="?logout=true">Logout</a>
                            </div>
                        </div>

                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </div>
    <div class="content-container">
        <div class="cup-mockup-container text-center">
            <img src="Cup Mockup.png" alt="Cup Mockup" class="cup-mockup">
            <div class="about_coffee">
                <h2 class="a">Syntax.sip</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt, 
                    urna sed sodales vestibulum, mauris est dictum sapien, in hendrerit nibh
                    nulla vitae orci. Maecenas ut libero a erat mollis ultrices non sed diam.</p>
                <button type="button" class="prdcts-btn" id="products-btn">Products</button>
            </div>
        </div>
    </div>
    <div class="popular-container" id="popular-section">
        <div class="popular-text">
            <h2>Popular Today</h2>
            <p>Savor every sip of our rich, aromatic coffee and every bite of our fresh, artisan bread.
                Refresh your day with our selection of invigorating drinks!</p>
            <p>Delicious Choices, Unforgettable Flavors!</p>
        </div>
        <div class="popular-menu">
            <div class="a-menu">
                <a href="your_link_here">
                    <p class="coffee-text">Syntax Kremy Latte</p>
                    <p class="price-text">190₱</p>
                    <img src="Rectangle 64.png" alt="" class="price-image">
                    <img src="Rectangle 37.png" alt="Rectangle" class="rectangle-image">
                    <img src="Coffee.png" alt="Coffee" class="coffee-image">
                </a>
            </div>
            <div class="a-menu">
                <a href="your_link_here">
                    <p class="coctail-text">Hibiscus Tea</p>
                    <p class="price-text">185₱</p>
                    <img src="Rectangle 64.png" alt="" class="price-image">
                    <img src="Rectangle 37.png" alt="Rectangle" class="rectangle-image">
                    <img src="Red Cocktail.png" alt="Red Cocktail" class="coctail-image">
                </a>
            </div>
            <div class="a-menu">
                <a href="your_link_here">
                    <p class="crossaint-text">Crossaints</p>
                    <p class="price-text">200₱</p>
                    <img src="Rectangle 64.png" alt="" class="price-image">
                    <img src="Rectangle 37.png" alt="Rectangle" class="rectangle-image">
                    <img src="Croissant.png" alt="Croissant" class="crossaint-image">
                </a>
            </div>
            <div class="a-menu">
                <a href="your_link_here">
                    <h2 class="cake-text">Strawberry</h2>
                    <p class="cake-text">Fudge Cake</p>
                    <p class="price-text">699₱</p>
                    <img src="Rectangle 64.png" alt="" class="price-image">
                    <img src="Rectangle 37.png" alt="Rectangle" class="rectangle-image">
                    <img src="Strawberry Cake.png" alt="Strawberry Cake" class="cake-image">
                </a>
            </div>
            <div class="a-menu">
                <a href="your_link_here">
                    <p class="beans-text">Beans</p>
                    <p class="price-text">299₱</p>
                    <img src="Rectangle 64.png" alt="" class="price-image">
                    <img src="Rectangle 37.png" alt="Rectangle" class="rectangle-image">
                    <img src="Coffee Bag.png" alt="Beans" class="beans-image">
                </a>
            </div>
        </div>
    </div>
    <button type="submit" class="view-btn">View More</button>

    <script src="main.js"></script>
</body>
</html>
