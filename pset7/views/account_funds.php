<!--TODO: Institute limit-->
<h1>Free Money!</h1>
<form action="account.php" method="post">
    <fieldset>
        <div class="form-group">
            <input type="hidden" name="addfunds" value="1"/>
        </div>
        <div class="form-group">
            <label>$</label>
            <input autocomplete="off" autofocus class="form-control" name="funds" placeholder="0" type="text"/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">Add Funds</button>
        </div>
    </fieldset>
</form>