<center>
  <a href="<?= BASEURL; ?>/katalog">
    <img class="navbar-brand" src="<?= BASEURL; ?>/image/sunnylogo.png" width="400" height="250">
  </a>
</center>
<div class="wrapper">
    <div class="title-text">
        <div class="title login">Login</div>
        <div class="title signup">Signup</div>
    </div>
    <?php Flasher::flash(); ?>
    <div class="form-container">
        <div class="slide-controls">
            <input type="radio" name="slide" id="login" checked>
            <input type="radio" name="slide" id="signup">
            <label for="login" class="slide login">Login</label>
            <label for="signup" class="slide signup">Signup</label>
            <div class="slider-tab"></div>
        </div>
        <div class="form-inner">
            <form action="<?= BASEURL; ?>/login/processLogincustomer" method="post" class="login">
                <div class="field">
                    <input type="text" placeholder="Email" id="email" name="email" required>
                </div>
                <div class="field">
                    <input type="password" placeholder="Password" id="password" name="password" required>
                </div>
                <div class="field btn">
                    <div class="btn-layer"></div>
                    <input type="submit">
                </div>
            </form>
            <form action="" method="post" class="signup">
                <div class="field">
                    <input type="text" placeholder="Username" id="username" name="username" required>
                </div>
                <div class="field">
                    <input type="text" placeholder="Email" id="email" name="email" required>
                </div>
                <div class="field">
                    <input type="password" placeholder="Password" id="password" name="password" required>
                </div>
                <div class="field btn">
                    <div class="btn-layer"></div>
                    <input type="submit" value="Signup">
                </div>
            </form>

        </div>
    </div>
</div>