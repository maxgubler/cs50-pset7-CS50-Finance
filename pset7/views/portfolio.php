<table class="table table-striped">
    <thead>
        <tr>
            <th>Symbol</th>
            <th>Name</th>
            <th>Shares</th>
            <th>Price</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <?php
            // render position table row and data
            foreach ($positions as $position)
            {
                extract($position);
                $total = $shares * $price;
                echo("<tr>\n");
                echo("<td>" . $symbol . "</td>\n");
                echo("<td>" . $name . "</td>\n");
                echo("<td>" . $shares . "</td>\n");
                echo("<td>$" . number_format($price, 2) . "</td>\n");
                echo("<td>$" . number_format($total, 2) . "</td>\n");
                echo("</tr>\n");
            }
            // lastly, render cash into the table
            echo("<tr>\n");
            echo('<td colspan="4">CASH</td>' . "\n");
            echo("<td>$" . number_format($cash, 2) . "</td>\n");
            echo("</tr>\n");
        ?>
    </tbody>
</table>