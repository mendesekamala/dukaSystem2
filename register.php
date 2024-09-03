<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Duka-system</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="loginSignup/stylesLogIn.css">
        <script src="loginSignup/script.js"></script>

        <style>
            #logout-div{
                display: flex;
                justify-content: space-evenly;
                width: 80%;
                margin: 50px auto;
                border: 2px solid #72ccdb;
                border-radius: 2em;
                padding-top: 5px;
                align-items: center;
            }
            #logout-div button{
                margin: 0 0 5px 0;
                height: 45px;
                border-radius: 1em;
                padding: 0 1em 0 1em;
                font-size: 15px;
                letter-spacing: 0.05cm;
                background-color: #72ccdb;
                cursor: pointer;

            }
            #logout-div p{
                font-weight: bold;
                color: black;
                font-size: 18px;
                letter-spacing: 0.03cm;
                margin-bottom: 3px;
            }
        </style>

    </head>
    <body>
        <div>
            <div class="sidebar">
                <h3 style="text-align:center;">Dashboard</h3>
                <a href="stock.php">Stock</a>
                <a href="orders.php">Orders</a>
                <a href="transactions.php">Transactions</a>
                <a href="debts.html">Debts</a>
                <a href="#" class="active">User</a>
            </div>
        </div>

        <div class="content">
            <!-- Conditional display based on login status -->
            <?php if (isset($_SESSION['username'])): ?>
                <!-- Display this div only if the user is logged in -->
                <div id="logout-div">
                    <p style="margin-bottom: 1em;">username - <?php echo $_SESSION['username']; ?> </p>
                    
                    <button onclick="location.href='logout.php'" id="logoutBt">Logout</button>
                </div>
            <?php else: ?>
                <!-- Display this div only if the user is NOT logged in -->
                <div class="container-main">
                    <div class="container" id="container">
                        <div class="form-container sign-up-container">
                            <form name="signUpForm" method="POST" action="create_company.php" onsubmit="return formValidation()" enctype="multipart/form-data">
                                <h4>Register Your Company</h4>
                                <input type="text" placeholder="Company name" name="companyName" />
                                <input type="text" placeholder="user name" name="username"/>
                                <input type="number" placeholder="capital" name="capital"/>
                                <input type="password" id="myInput" placeholder="Password" name="password" />
                                <input class="input-submit" type="submit" value="submit">
                            </form>
                        </div>
                    
                        <div class="form-container sign-in-container">
                            <form method="POST" action="login.php">
                                <h1>Sign in</h1>
                                <span>into your company</span>
                                <input type="text" placeholder="user name" name="username"/>
                                <input type="password" placeholder="Password" id="password" name="password"/>
                                <input class="input-submit" type="submit" value="login">
                                <a class="aLogIn" href="#">Forgot your password?</a>
                            </form>
                        </div>
                    
                        <div class="overlay-container">
                            <div class="overlay">
                                <div class="overlay-panel overlay-left">
                                    <h1>Company registered!</h1>
                                    <p>log into your company</p>
                                    <button class="ghost" id="signIn">Sign In</button>
                                </div>
                                <div class="overlay-panel overlay-right">
                                    <h2>Haven't registered a company yet!</h2>
                                    <p>Enter your company details</p>
                                    <button class="ghost" id="signUp">register</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        const signUpButton = document.getElementById('signUp');
                        const signInButton = document.getElementById('signIn');
                        const container = document.getElementById('container');
                    
                        signUpButton.addEventListener('click', () => {
                            container.classList.add("right-panel-active");
                        });
                    
                        signInButton.addEventListener('click', () => {
                            container.classList.remove("right-panel-active");
                        });
                    </script>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>
