<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render form
        // render("sell_form.php", ["title" => "Sell"]);
        $poscash = return_poscash(); // test

        render_multi(["sell_form.php", "portfolio.php"], ["title" => "Sell", "positions" => $poscash["positions"], "cash" => $poscash["cash"]]); // test
    }
    
    // else if user reached page via POST (as by submitting quote form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["symbol"]))
        {
            apologize("You must specify a stock to sell.");
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
            // ensure stock symbol is uppercase
            $symbol = strtoupper($_POST["symbol"]);
            
            // attempt to load portfolio row associated with this stock symbol
            $portfolioArray = CS50::query("SELECT * FROM `portfolios` WHERE `user_id` = ? AND `symbol` = ?", $_SESSION["id"], $symbol);
            
            // check for stock symbol in portfolio
            if (count($portfolioArray) != 1)
            {
                apologize("Symbol not found in porfolio.");
            }
            else
            {
                $sharesToSell = $_POST["shares"];
                
                // check for valid share amount   
                if ($sharesToSell > $portfolioArray[0]["shares"])
                {
                    apologize("You do not have that may shares to sell.");
                }
                
                else
                {
                    // TODO: employ SQL "transactions" (with InnoDB tables). See http://dev.mysql.com/doc/refman/5.5/en/sql-syntax-transactions.html
                    
                    // if selling all shares, delete row from portfolio
                    if ($portfolioArray[0]["shares"] == $sharesToSell)
                    {
                        CS50::query("DELETE FROM `portfolios` WHERE `user_id` = ? AND `symbol` = ?", $portfolioArray[0]["user_id"], $portfolioArray[0]["symbol"]);
                    }
                    
                    // else reduce shares in portfolio
                    else
                    {
                        CS50::query("UPDATE `portfolios` SET `shares` = shares - {$sharesToSell} WHERE id = ?", $portfolioArray[0]["id"]);
                    }
                    
                    // get latest stock information, including price
                    $stock = lookup($_POST["symbol"]);
                    
                    // record transaction history, recording shares as negative for a sell
                    CS50::query("INSERT INTO `history` (`user_id`, `symbol`, `shares`, `price`) VALUES(?, ?, ?, ?)", $_SESSION["id"], $stock["symbol"], -$sharesToSell, $stock["price"]);
                
                    // complete sale by increasing cash balance
                    // number_format ensures up to 4 decimals for cash balance, with the last '' indicating a comma is not to be used
                    $saleTotal = number_format($stock["price"] * $sharesToSell, 4, '.', '');
                    
                    CS50::query("update `users` SET `cash` = cash + ? WHERE `id` = ?", $saleTotal, $_SESSION["id"]);
                    
                    // render confirmation page
                    render("sell_confirmation.php", ["title" => "{$stock["symbol"]} Sale Complete", "stock" => $stock, "shares" => $_POST["shares"]]);
                }
            }
        }
    }

?>