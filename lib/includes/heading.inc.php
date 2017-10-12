<div id="heading" class="container">
    <h1>PHP, PDO and MySQL Tutorial</h1>
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['security'] === 'public') { ?>
        <div id="logout">
            <p>Welcome, <?= $_SESSION['user']['name']; ?>! <a href="logout.php">Logout</a></p>
        </div>
    <?php } else { ?>
        <form id="login" action="login.php" method="post">
            <input type="hidden" name="action" value="login">
            <label for="name">Username</label>
            <input id="name" type="text" name="name" value="">
            <label for="password">Password</label>
            <input id="password" type="password" name="password">
            <input type="submit" name="submitBtn" value="submit">
            <a id="registerBtn" href="register.php">Need to register?</a>
        </form>   
    <?php } ?>
</div>
