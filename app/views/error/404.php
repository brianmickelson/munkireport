<?php
header("HTTP/1.0 404 Not Found");
?>
<h1>Error 404: Not Found</h1>
<?php echo $error_message; ?>
<p>Please go <a href="javascript: history.back(1)">back</a> and try again.</p>