<div>
<form action="" method="post" accept-charset="UTF-8" >
    <legend>Generate password hash</legend>
<?php if(isset($generated_pwd)):?>
	<label for="genpwd">Add this line to config.php:</label>
	<hr />
	<span class="text-info">$GLOBALS['auth_config']['<?php echo $login?>'] = '<?php echo $generated_pwd?>';</span><hr/>
	<input type="submit" id="submit" value="Start over" />
<?php else:?>
	<label for="loginusername">Username:</label><input type="text" id="loginusername" name="login" class="text" value="<?php echo $login?>"></input><br/>
	<label for="loginpassword">Password:</label><input type="password" id="loginpassword" name="password" class="text"></input>
	<input type="submit" id="submit" value="Generate" />
<?php endif?>
</form>
</div>