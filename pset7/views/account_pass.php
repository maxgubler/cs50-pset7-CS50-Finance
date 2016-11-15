<h1>Change password</h1>
<form id="changepass" action="account.php" method="post">
    <fieldset>
        <div class="form-group">
            <input type="hidden" name="changepass" value="1"/>
        </div>
        <div class="form-group">
            <label>Current password: </label>
            <input autocomplete="off" class="form-control" name="password" placeholder="Password" type="password"/>
        </div>
        <div class="form-group">
            <label>New password: </label>
            <input autocomplete="off" class="form-control" name="newpass" placeholder="New Password" type="password"/>
        </div>
        <div class="form-group">
            <label>Confirm: </label>
            <input autocomplete="off" class="form-control" name="confirm" placeholder="New Password" type="password"/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">Change</button>
        </div>
    </fieldset>
</form>