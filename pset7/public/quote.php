<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render form
        render("quote_form.php", ["title" => "Get Quote"]);
    }
    
    // else if user reached page via POST (as by submitting quote form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["symbol"]))
        {
            apologize("You must provide a symbol.");
        }
        else
        {
            $stock = lookup($_POST["symbol"]);
            // check for valid stock stymbol
            if (!$stock)
            {
                apologize("Symbol not found.");
            }
            
            else
            {
                render("quote_render.php", ["title" => "Quote", "stock" => $stock]);
            }
        }
    }

?>