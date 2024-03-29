<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['email']))
{
    header("location:registration/welcome.php");
    exit;
}
require_once "config.php";

$email = $password = $class ="";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($err))
    {
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;

        // Try to execute this statement
    }
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
        {
            mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
            if(mysqli_stmt_fetch($stmt))
            {
                if(password_verify($password, $hashed_password))
                {
                    // this means the password is corrct. Allow user to login
                    session_start();
                    $_SESSION["email"] = $email;
                    $_SESSION["id"] = $id;
                    $_SESSION["loggedin"] = true;

                    //Redirect user to welcome page
                    header("location: registration/welcome.php");

                }else {
                    $err = "Email or password is not valid";
                    $class = "is-invalid";

                }
            } else {
                $err = "Email or password is not valid";
                $class = "is-invalid";

            }

        }else {
            $err = "Email or password is not valid";
            $class = "is-invalid";

        }

    }else {
        $err = "Email or password is not valid";
        $class = "is-invalid";

    }
}


?>

<?php include "includes/reg_header.php" ?>

<link href="assets/css/signin.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin">
    <form action="" method="post">
        <img class="mb-4" src="assets/images/book.svg" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
            <input type="email" value="<?php echo  $email?>" required class="form-control <?php echo $class ?>" id="floatingInput" name="email" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                <?php echo $err ?>
            </div>
        </div>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control  <?php echo $class ?>"  required value="<?php echo $password?>" id="floatingPassword" name="password" placeholder="Password">
            <label for="floatingPassword">Password</label>

        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="w-100 mb-3 btn btn-lg btn-primary" type="submit">Sign in</button>


    </form>
<div class="row">
       <div class="col"> <a href="registration/register.php"> <button class="w-100 btn btn-lg btn-secondary">Register</button></a>
       </div>
<div class="col">
        <a href="google_login/google_login.php">
            <button type="button" class="w-100 btn btn-lg btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"></path>
                </svg>
                login
            </button>



        </a>
</div>
</div>
</main>



</body>
</html>


