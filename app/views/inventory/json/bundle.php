<?php

echo json_encode(array(
	"requested_name" => $name,
	"requested_version" => $version,
	"all_versions" => $all_versions,
	"inventory_items" => $inventory_items
));