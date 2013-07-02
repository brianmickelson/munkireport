/*
	This script is used on the inventory/bundles route to handle loading the
	table data via json. It also formats the columns so each value is clickable
 */
$(document).ready(function()
{
	// Perform the json call and format the results so that DataTables will
	// understand it.
	var process_json = function( sSource, aoData, fnCallback )
	{
		$.getJSON( sSource, function(json, status, jqXHR)
		{
			// update the count info badge
			$("#machines-count-badge").text(json.length);

			// let datatables do its thing.
			fnCallback( {'aaData': json} );
		});
	};



	var format_timestamp_col = function(val, type, row)
	{
		var inventory_timestamp = row.inventory_timestamp,
			munki_timestamp = row.munki_timestamp,
			stamp = inventory_timestamp == '' ? munki_timestamp : inventory_timestamp * 1000;

		if (type == 'sort')
			return new Date(stamp);

		return new Date(stamp).toLocaleString();
	};




	// This is manily implemented to help with sorting, since IP addresses
	// suck to sort.
	var format_ip_col = function(val, type, row)
	{
		if (type != 'sort')
			return val;
		var parts = val.split("."),
			concated = '';
		for(var i in parts)
		{
			while (parts[i].length < 3)
				parts[i] = '0' + parts[i];
			concated += parts[i];
		}
		return concated;
	};




	var format_user_col = function(val, type, row)
	{
		// We have to escape html chars here because Munki will report
		// the user as "<None>" when no one is logged in, which of course
		// gets gobbled up by the browser when passed as-is.
		return htmlspecialchars(row.console_user);
	};




	var format_host_col = function(val, type, row)
	{
		if (type != 'display')
			return row.hostname;

		return draw_machine_button(
				row.serial_number,
				row.hostname,
				row.remote_ip,
				'inventory',
				'detail');
	}




	$("#machines-table").dataTable({
		"sAjaxSource": window.location.href + ".json",
		"fnServerData": process_json,
		"aaSorting": [[0,'desc']],
		"aoColumnDefs": [
			{
				// Setup some hidden columns that we just want for searching
				'bVisible': false,
				'aTargets': [5, 6, 7, 8, 9, 10, 11]
			},
			{
				'mData': 'hostname',
				'mRender': format_host_col,
				'sWidth': '25%',
				'aTargets': [0]
			},
			{
				'mData': 'console_user',
				'mRender': format_user_col,
				'aTargets': [1]
			},
			{
				'mData': 'remote_ip',
				'mRender': format_ip_col,
				'aTargets': [2]
			},
			{
				'mData': 'os_version',
				'aTargets': [3]
			},
			{
				'mData': 'inventory_timestamp',
				'mRender': format_timestamp_col,
				'aTargets': [4]
			},
			{
				'mData': 'serial_number',
				'aTargets': [5]
			},
			{
				'mData': 'machine_model',
				'aTargets': [6]
			},
			{
				'mData': 'machine_desc',
				'aTargets': [7]
			},
			{
				'mData': 'cpu_arch',
				'aTargets': [8]
			},
			{
				'mData': 'platform_UUID',
				'aTargets': [9]
			},
			{
				'mData': 'SMC_version_system',
				'aTargets': [10]
			},
			{
				'mData': 'boot_rom_version',
				'aTargets': [11]
			},
		]
	});
});