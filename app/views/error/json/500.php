<?php
header("HTTP/1.0 500 Internal Server Error: Uncaught Exception");
echo json_encode(array(
	"code" => $exception->getCode(),
	"line" => $exception->getLine(),
	"file" => $exception->getFile(),
	"message" => $exception->getMessage(),
	"trace" => $exception->getTrace()
));