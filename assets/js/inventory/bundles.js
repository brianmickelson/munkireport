/*
	This script is used on the inventory/bundles route to handle loading the
	table data via json. It also formats the columns so each value os clickable
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
			// update the count info badge
			$("#bundles-count-badge").text(json.length);

			// let datatables do its thing.
			fnCallback( {'aaData': json} );
		});
	}




	var format_name_column = function(rowObject)
	{
		var name = rowObject.aData['name'];
		return '<a href="bundle/' + encodeURIComponent(name)
			+ '">' + name + "</a>";
	}




	var format_versions_column = function(rowObject)
	{
		var v = rowObject.aData['versions'],
			out = ''
		for(var i = 0; i < v.length; i++)
		{
			var version = v[i][0],
				count = v[i][1];
			out += version_count_template(
				rowObject.aData['name'],
				v[i][0],
				v[i][1]
			);
		}
		return out;
	}


	$("#bundles-table").dataTable({
		"sAjaxSource": window.location.href + ".json",
		"fnServerData": process_json,
		"iDisplayLength": 15,
        "sPaginationType": "bootstrap",
        "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
        "bStateSave": true,
        "aaSorting": [[4,'desc']],
        "aoColumns": [
        	{'mData': 'name'},
        	{'mData': 'versions'}
        ],
        "aoColumnDefs": [
	        {
        		'fnRender': format_name_column,
        		'aTargets': [0]
        	},
        	{
        		'fnRender': format_versions_column,
        		'aTargets': [1]
        	}
        ]
	});
});