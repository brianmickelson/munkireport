$(document).ready(function()
{
	var all_versions = [],
		requested_bundle_name = '',
		requested_version = '';



	var populate_version_menu = function()
	{
		var versions = [],
			hash = {};
		for(var i = 0; i < all_versions.length; i++)
		{
			var v = all_versions[i]['version'];
			versions.push(v);
			hash[v] = all_versions[i]['num_installs'];
		}
		// sort the version numbers in descending order
		versions.version_sort(false);

		// output the version=>num_installs hash table
		var list = $("#bundle-versions");
		for(var i = 0; i < versions.length; i++)
		{
			var vers = versions[i],
				// create the list item
				li = $("<li></li>"),
				// create the link with the appropriate href value
				a = $("<a></a>").text(vers)
					.attr('href', 
						(requested_version != null ? '../' : '')
						+ requested_bundle_name + "/" + vers),
				// create the count badge and make it float to the right
				badge = $("<span></span>")
						.addClass('badge badge-info pull-right')
						.text(hash[vers]);
				// put them all together
				li.append(a.append(badge));

			// If this version is the one currently being displayed, disable it
			if (versions[i] == requested_version)
				li.addClass('disabled');

			// append the list item to the master ul
			list.append(li);

		}
	};



	var render_small = function(val, type, row)
	{
		if (type != 'display')
			return val;
		return "<small>" + val + "</small>";
	};



	var process_json = function(sSource, aoData, fnCallback)
	{
		$.getJSON( sSource, function(json, status, jqXHR)
		{
			all_versions = json.all_versions;
			requested_bundle_name = json.requested_name;
			requested_version = json.requested_version;
			populate_version_menu();

			// let datatables do its thing.
			fnCallback( {'aaData': json.inventory_items} );
		});
	};




	var format_hostname_col = function(val, type, row)
	{
		if (type != 'display')
			return val.hostname;

		return draw_machine_button(	val.serial_number,
								val.hostname,
								val.remote_ip,
								'../inventory',
								'detail');
	};




	var format_username_col = function (val, type, row)
	{
		return val.console_user;
	};




	$('#bundle-table').dataTable(
	{
		"sAjaxSource": window.location.href + ".json",
		"fnServerData": process_json,
		"aoColumnDefs": [
			{
				// setup hidden columns for searching
				'bVisible': false,
				'aTargets': [1, 2, 7, 8, 9, 10, 11, 12, 13]
			},
			{
				'mData': 'machine_info',
				'mRender': format_hostname_col,
				'sWidth': '30%',
				'aTargets': [0]
			},
			{
				'mData': 'machine_info',
				'mRender': format_username_col,
				'aTargets': [1]
			},
			{
				'mData': 'machine_info.os_version',
				'aTargets': [2]
			},
			{
				'mData': 'version',
				'aTargets': [3]
			},
			{
				'mData': 'bundleid',
				'mRender': render_small,
				'aTargets': [4]
			},
			{
				'mData': 'bundlename',
				'mRender': render_small,
				'aTargets': [5]
			},
			{
				'mData': 'path',
				'mRender': render_small,
				'sWidth': '25%',
				'aTargets': [6]
			},

			{
				'mData': 'machine_info.serial_number',
				'aTargets': [7]
			},
			{
				'mData': 'machine_info.machine_model',
				'aTargets': [8]
			},
			{
				'mData': 'machine_info.machine_desc',
				'aTargets': [9]
			},
			{
				'mData': 'machine_info.cpu_arch',
				'aTargets': [10]
			},
			{
				'mData': 'machine_info.platform_UUID',
				'aTargets': [11]
			},
			{
				'mData': 'machine_info.SMC_version_system',
				'aTargets': [12]
			},
			{
				'mData': 'machine_info.boot_rom_version',
				'aTargets': [13]
			},
		]
	});
});