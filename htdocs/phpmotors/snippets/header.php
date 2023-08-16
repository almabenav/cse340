<div id="top-header">
    <img src="/phpmotors/images/logo.PNG" alt="PHP Motors Logo" id="logo">
    <div class="name-logout">
        <?php 
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                $firstName = isset($_SESSION['clientData']['clientFirstname']) ? trim($_SESSION['clientData']['clientFirstname']) : '';
                if (!empty($firstName)) {
                    echo "<a class='welcome-ad' href='/phpmotors/accounts/?action=admin'>$firstName  | </a>";
                }
            echo "<a class='logout' href='/phpmotors/accounts/?action=Logout'>LogOut</a>";
        } else {
            echo "<a href='/phpmotors/accounts/?action=login-page'>My Account</a>"; 
        }
        ?>
    </div>
</div>
