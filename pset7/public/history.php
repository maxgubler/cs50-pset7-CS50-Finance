<?php // TODO: Read less of the table into memory / Multiple pages of history / Change sort order

    // configuration
    require("../includes/config.php");
    
    // read portfolio data into $rows
    $rows = CS50::query("SELECT * FROM `history` WHERE `user_id` = ?", $_SESSION["id"]);

    // render history
    render("history_transactions.php", ["title" => "Transaction History", "rows" => $rows]);

?>