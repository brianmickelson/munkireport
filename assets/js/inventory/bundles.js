/*
	This script is used on the inventory/bundles route to handle loading the
	table data via json. It also formats the columns so each value is clickable
 */
$(document).ready(function()
{
	var version_count_template = function(name, version, count)
	{
		return "<a href='bundle/"
			+ encodeURIComponent(name) + "/" + version
			+ "'>" + version + "<span class='badge badge-info pull-right'>"
			+ count + "</span></a><br />";
	}

	// Perform the json call and format the results so that DataTables will
	// understand it.
	var process_json = function( sSource, aoData, fnCallback )
	{
		$.getJSON( sSource, function(json, status, jqXHR)
		{
			// let datatables do its thing.
			fnCallback( {'aaData': json} );
		});
	}




	var format_name_column = function(val, type, row)
	{
		if (type != 'display')
			return val.replace(/^[^a-zA-Z0-9]*/, '');

		return '<a href="bundle/' + encodeURIComponent(val)
			+ '">' + htmlspecialchars(val) + "</a>";
	}




	var format_versions_column = function(val, type, row)
	{
		if (type != 'display')
			return row['versions'][0][0];

		var v = row['versions'],
			out = ''
		for(var i = 0; i < v.length; i++)
		{
			var version = v[i][0],
				count = v[i][1];
			out += version_count_template(
				row['name'],
				v[i][0],
				v[i][1]
			);
		}
		return out;
	}


	$("#bundles-table").dataTable({
		"sAjaxSource": window.location.href + ".json",
		"fnServerData": process_json,
		"aoColumnDefs": [
			{
				'mData': 'name',
				'mRender': format_name_column,
				'aTargets': [0]
			},
			{
				'mData': 'versions',
				'mRender': format_versions_column,
				'aTargets': [1]
			}
		]
	});
});