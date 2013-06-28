<div style="margin: auto" class="loginform">
<form action="<?php echo $url?>" method="post" accept-charset="UTF-8" class="loginfields">
    <legend>MunkiReport <small>version <?=$GLOBALS['version']?></small></legend>
    	<?php if (isset($error)):?>
	<p class="text-error"><?php echo $error?></p>
<?php endif?>
    <label for="loginusername">Username:</label><input type="text" id="loginusername" name="login" class="text" value="<?php echo $login?>"></input><br/>
    <label for="loginpassword">Password:</label><input type="password" id="loginpassword" name="password" class="text"></input>
    <button class="btn btn-large btn-primary" type="submit">Sign in</button>
</form>
</div>