<?php

    // configuration
    require("../includes/config.php");
    
    // read portfolio data into $rows
    $rows = CS50::query("SELECT * FROM `portfolios` WHERE `user_id` = ?", $_SESSION["id"]);
    // read cash from user data into $cash (which is returned as an array, 0 = > "cash")
    $cash = CS50::query("SELECT `cash` FROM `users` WHERE `id` = ?", $_SESSION["id"]);
    
    // define positions by iterating over each row the user has in the portfolios table
    $positions = [];
    foreach ($rows as $row)
    {
        $stock = lookup($row["symbol"]);
        if ($stock !== false)
        {
            $positions[] = [
                "name" => $stock["name"],
                "price" => $stock["price"],
                "shares" => $row["shares"],
                "symbol" => $row["symbol"]
            ];
        }
    }

    // render portfolio
    render("portfolio.php", ["title" => "Portfolio", "positions" => $positions, "cash" => $cash[0]["cash"]]);

?>
