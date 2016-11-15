<?php

    // configuration
    require("../includes/config.php");
    
    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // test render("account_funds.php", ["title" => "Account"]);
        
        render_multi(["account_funds.php", "account_pass.php"], ["title" => "Account"]);
    }
    
    // else if user reached page via POST (as by submitting quote form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // check which button was was pressed by looking for the hidden inputs
        // isset() returns TRUE if var exists and has value other than NULL, FALSE otherwise. 
        if (isset($_POST["addfunds"]))
        {
            // validate submission
            if (empty($_POST["funds"]))
            {
                apologize("You must specify an amount to add.");
            }
            // see RegEx_notes.txt in pset7 dir, php required / before ^ and after $
            else if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $_POST["funds"]))
            {
                apologize("You must specify a positive dollar amount.");
            }
            else
            {
                // TODO: Institute limit
                CS50::query("update `users` SET `cash` = cash + ? WHERE `id` = ?", $_POST["funds"], $_SESSION["id"]);
                render("account_funds_confirm.php", ["title" => "Funds Added Successfully", "funds" => $_POST["funds"]]);
            }
        }
        
        else if (isset($_POST["changepass"]))
        {
            // validate submission
            if (empty($_POST["password"]))
            {
                apologize("You must enter your current password.");
            }
            else if (empty($_POST["newpass"]) || empty($_POST["confirm"]))
            {
                apologize("You must enter your new password twice.");
            }
            else if ($_POST["newpass"] != $_POST["confirm"])
            {
                apologize("Your passwords did not match.");
            }
            else if ($_POST["newpass"] == $_POST["password"])
            {
                apologize("You cannot reuse your current password.");
            }
            else if (strlen($_POST["newpass"]) < 5 || strlen($_POST["newpass"]) > 16)
            {
                apologize("Your new password must be 5 to 16 characters long.");
            }
            else
            {
                // read in hash data from users table, where $rows is an array
                $rows = CS50::query("SELECT `hash` FROM `users` WHERE `id` = ?", $_SESSION["id"]);

                if (password_verify($_POST["password"], $rows[0]["hash"]))
                {
                    $newhash = password_hash($_POST["newpass"], PASSWORD_DEFAULT);
                    CS50::query("UPDATE `users` SET `hash` = ? WHERE id = ?", $newhash, $_SESSION["id"]);
                    render("account_pass_confirm.php", ["Title" => "Password Successfully Changed"]);
                }
                else
                {
                    apologize("Your current password was incorrect.");
                }
            }
        }
        
        else
        {
            trigger_error("No input found", E_USER_ERROR);
        }
    }
    
?>