<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render form
        // render("buy_form.php", ["title" => "Buy"]);
        
        $poscash = return_poscash(); // test

        render_multi(["buy_form.php", "portfolio.php"], ["title" => "Buy", "positions" => $poscash["positions"], "cash" => $poscash["cash"]]); // test
    }
    
    // else if user reached page via POST (as by submitting quote form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["symbol"]))
        {
            apologize("You must specify a stock to buy.");
        }
        else if (empty($_POST["shares"]))
        {
            apologize("You must specify a number of shares.");
        }
        else if (!preg_match("/^\d+$/", $_POST["shares"]))
        {
            apologize("You must specify a positive integer for shares.");
        }
        else
        {
            $stock = lookup($_POST["symbol"]);
            // check for valid stock stymbol
            if (!$stock)
            {
                apologize("Symbol not found.");
            }
            
            $sharesToBuy = $_POST["shares"];
            // number_format ensures up to 4 decimal places and the last '' indicates that a comma is not to be used
            $totalToBuy = number_format($stock["price"] * $sharesToBuy, 4, '.', '');
            
            // access cash balance from users table (query returns an array for $userCash and $cash accesses the desired value)
            $userCash = CS50::query("SELECT `cash` FROM `users` WHERE `id` = ?", $_SESSION["id"]);
            $cash = $userCash[0]["cash"];
            
            // check for valid balance
            if ($totalToBuy > $cash)
            {
                apologize("Insufficient cash balance.");
            }
            
            else
            {
                // TODO: employ SQL "transactions" (with InnoDB tables). See http://dev.mysql.com/doc/refman/5.5/en/sql-syntax-transactions.html
                
                // insert new row into portfolio or increase existing shares
                CS50::query("INSERT INTO `portfolios` (`user_id`, `symbol`, `shares`) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE `shares` = shares + VALUES(shares)", $_SESSION["id"], $stock["symbol"], $sharesToBuy);
                
                // record transaction history
                CS50::query("INSERT INTO `history` (`user_id`, `symbol`, `shares`, `price`) VALUES(?, ?, ?, ?)", $_SESSION["id"], $stock["symbol"], $sharesToBuy, $stock["price"]);
                
                // complete sale by decreasing cash balance
                CS50::query("update users SET cash = cash - ? WHERE id = ?", $totalToBuy, $_SESSION["id"]);
                    
                // render confirmation page
                render("buy_confirmation.php", ["title" => "{$stock["symbol"]} Buy Complete", "stock" => $stock, "shares" => $_POST["shares"]]);
            }
        }
    }

?>