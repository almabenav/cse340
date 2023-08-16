<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP MOTORS Website by Alma Benavides in CSE 340">
    <title>Home | PHP MOTORS</title>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/styles.css" media="screen">
    <link rel="stylesheet" href="css/normalize.css" media="screen">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main class="home-main">
        <h1>Welcome to PHP Motors!</h1>
        <div class="hero">
            <div class="hero-img">
                <section class="hero-txt">
                <h2>DMC Delorean</h2>
                <p>3 Cup holders Superman doors Fuzzy dice!</p>
                </section>   
            </div>
            <a class="blue-btn hero-btn" href="">Own Today</a>
        </div>
        <div class="container">
        <div class="reviews-home">
            <h2>DMC Delorean Reviews</h2>
            <ul>
                <li>So fast it's almost like traveling in time." (4/5)</li>
                <li>"Coolest ride on the road (4/5)</li>
                <li>"I'm feeling Marty McFly!" (5/5)</li>
                <li>"The most futuristic ride of our day"(4/5)</li>
                <li>"80's livin and I love it!" (5/5)</li>
            </ul>
        </div>
        <div class="upgrades">
            <h2>Delorean Upgrades</h2>
            <div class="upgrades-cards">
                <div class="flux-capacitor">
                    <div class="flux-img">
                        <img src="images/flux-cap.png" alt="Flux Capacitor">
                    </div>
                    <a href="">Flux Capacitor</a>
                </div>
                <div class="flam-decals">
                    <div class="flam-img">
                        <img src="images/llama(1).jpg" alt="Flam Decals">
                    </div>
                    <a href="">Flam Decals</a>
                </div>
                <div class="bumper-stickers">
                    <div class="bumper-img">
                        <img src="images/bumper_sticker.jpg" alt="Bumper Stickers">
                    </div>
                    <a href="">Bumper Stickers</a>
                </div>
                <div class="hub-caps">
                    <div class="hub-img">
                        <img src="images/hub-cap.jpg" alt="Hub Caps">
                    </div>
                    <a href="">Hub Caps</a>
                </div>
            </div>
        </div>
        </div>
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>
</body>
</html>