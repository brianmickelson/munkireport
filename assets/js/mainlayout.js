// define globals and site-wide helper functions here.


// Just a quickie to help with class detection.
function get_class(anObject)
{
	return {}.toString.call(anObject);
}



// Performs something akind to PHP's htmlspecialchars() function.
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
				'sDom': '<"pull-left"<"clearfix"l><"clearfix muted"i>><"pull-right"fp>tp<"muted"i>',
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

	return	'<div class="btn-group">'
				+ '<span class="btn" data-serialnumber="' + serial_number + '">'
					+ '<i class="icon-info-sign"></i>'
				+ '</span>'
				+ '<a class="btn' + (ip == null ? ' disabled' : '')
					+ '" href="vnc://' + ip + ':5900" title="Remote Desktop:'
					+ ip + '"><i class="icon-eye-open"></i></a>'
				+ '<a class="btn" href="../' + controller_name + '/' + action_name
					+ '/' + serial_number + '">'
				+ computer_name
				+ '</a>'
			+ '</div>';
}