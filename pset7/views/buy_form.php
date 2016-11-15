<form action="buy.php" method="post">
    <fieldset>
        <!--symbol entry-->
        <div class="form-group">
            <input autocomplete="off" autofocus class="form-control" name="symbol" placeholder="Symbol" type="text"/>
        </div>
        <!--shares entry-->
        <div class="form-group">
            <input class="form-control" name="shares" placeholder="Shares" type="text"/>
        </div>
        <!--submit symbol-->
        <div class="form-group">
            <button class="btn btn-default" type="submit">Buy</button>
        </div>
    </fieldset>
</form>