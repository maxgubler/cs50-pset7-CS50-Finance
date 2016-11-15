<table class="table table-striped">
    <thead>
        <tr>
            <th>Buy/Sell</th>
            <th>Symbol</th>
            <th>Shares</th>
            <th>Price</th>
            <th>Timestamp</th>
        </tr>
    </thead>
    <tbody>
        <?php
            // TODO: Limit table size in memory and/or table size displayed per page
            foreach($rows as $row)
            {
                extract($row);
                echo("<tr>\n");
                if ($shares > 0)
                {
                    echo("<td>Buy</td>\n");
                }
                else
                {
                    echo("<td>Sell</>\n");
                }
                echo("<td>" . $symbol . "</td>\n");
                echo("<td>" . $shares . "</td>\n");
                echo("<td>$" . number_format($price, 2) . "</td>\n");
                echo("<td>" . $timestamp . "</td>\n");
                echo("</tr>\n");
            }
        
        ?>
    </tbody>
</table>