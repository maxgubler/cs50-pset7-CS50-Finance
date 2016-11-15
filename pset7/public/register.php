<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // error check
        if (empty($_POST["username"]))
        {
            apologize("You must provide a username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide a password.");
        }
        else if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Those passwords did not match.");
        }
        else if (strlen($_POST["password"]) < 5 || strlen($_POST["password"]) > 16)
        {
            apologize("Your password must be 5 to 16 characters long.");
        }
        // if valid, insert user into database
        else
        {
            // if the insertion query does not return 0, the entry was successful
            if (CS50::query("INSERT IGNORE INTO users (username, hash, cash) VALUES(?, ?, 20000.0000)", $_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT)) != 0)
            {
            // log new user in and remember their session id
            
                // use a query to access an array containing this id
                $rows = CS50::query("SELECT LAST_INSERT_ID() AS id");
                
                // remember that user's now logged in by storing user's ID in session
                $_SESSION["id"] = $rows[0]["id"];

                // redirect to portfolio
                redirect("/");
            }
            // else, the username exists
            else
            {
                apologize("That username appears to be taken.");
            }
        }
    }

?>