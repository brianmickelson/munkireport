<?php

define("KISS_PREVENT_RENDER", TRUE);
define("KISS_PREVENT_ROUTE", TRUE);
header('Content-Type: application/javascript');
$index_page = dirname(dirname(__DIR__)) . "/index.php";
require_once($index_page);
$controller = KISS_Controller::get_instance();
$controller->prevent_render(TRUE);



echo 'MRP_BASE_URL = "'
	. Config::get('subdirectory') . Config::get('indexPage') . '/"';
?>


/**
 * An addition to the Array prototype that provides better sorting for version 
 * strings. Pass `false` as the only parameter to perform a reverse sort.
 */
Array.prototype.version_sort = function(sort_asc)
{
	if ( sort_asc == undefined)
		sort_asc = true;

	this.sort(function(a, b)
	{
		var a_parts = a.split("."),
			b_parts = b.split("."),
			max_len = a_parts.length > b_parts.length ? a_parts.length : b_parts.length;
		for(var i = 0; i < max_len; i++)
		{
			if (i > a_parts.length)
				return (sort_asc ? -1 : 1);
			if (i > b_parts.length)
				return (sort_asc ? 1 : -1);

			var left = parseInt(a_parts[i]),
				right = parseInt(b_parts[i]),
				equal = left == right,
				asc = left > right,
				desc = !asc && !equal;

			if (equal)
				continue;
			if (asc)
				return (sort_asc ? 1 : -1);
			if (desc)
				return (sort_asc ? -1 : 1);
		}
		return 0;
	});
};


// Just a quickie to help with class detection.
function get_class(anObject)
{
	return {}.toString.call(anObject);
}



// Performs something akin to PHP's htmlspecialchars() function.
 function htmlspecialchars(aString)
{
	if (aString == undefined || get_class(aString) != '[object String]')
		return '';
	return aString
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
}



/*
	This next closure moves dataTables aside and so it can respond to
	new instance requests instead. This allows us to set some default
	options for all dataTable instances, site-wide.
 */
(function($)
{

	// Set the original dataTable function aside.
	var DataTablesOrig = $.fn.dataTable;

	$.fn.dataTable = function(options)
	{
		if(typeof options === "object")
		{
			options = $.extend(
			{
				'sDom': '<"well well-small"<"row"<"span4 muted"i><f>><"row"<"span4 muted"l><p>>>tp<"muted"i>',
				'iDisplayLength': 25,
				'sPaginationType': 'bootstrap',
				'aLengthMenu': [[15, 25, 50, -1], [15, 25, 50, "All"]],
				'bStateSave': false,
				'bDeferRender': true,
				"aaSorting": [[0,'asc']],
			}, options);
		}

		// Initialize and return the original dataTables function with our 
		// defaults applied.
		var args = Array.prototype.slice.call(arguments,0);
		return DataTablesOrig.apply(this, args);
	}

})(jQuery);




/*
	A helper view function that will draw a standard button group for machine 
	details.
 */
function draw_machine_button(	serial_number,
								hostname,
								ip,
								controller_name,
								action_name)
{
	var computer_name = hostname;
	if (hostname == '' && serial_number == '')
		computer_name = ip;
	else if (hostname == '')
		computer_name = serial_number;

	// give the elements 50ms to render and then attempt to bind the popover
	// view to the new element.
	setTimeout(detect_popover_elements, 50);
	return	'<div class="btn-group pull-right">'
				+ '<span class="btn btn-mini" data-serialnumber="' + serial_number + '">'
					+ '<i class="icon-info-sign"></i>'
				+ '</span>'
				+ '<a class="btn btn-mini' + (ip == null ? ' disabled' : '')
					+ '" href="vnc://' + ip + ':5900" title="Remote Desktop:'
					+ ip + '"><i class="icon-eye-open"></i></a>'
			+ '</div>'
			+ '&nbsp;<a href="../' + controller_name + '/' + action_name
					+ '/' + serial_number + '">'
				+ computer_name
				+ '</a>';
}