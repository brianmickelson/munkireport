	var clientInfoPopovers = [],
		popoverOptions = {
			title: 'Machine Data',
			content: loadPopoverContent
		};

	/*
	 * This exists as a function followed immediately by a call to that 
	 * function so that all present elements are detected, but when scripts add
	 * more elements dynamicaly, they can call this function again.
	 */
	function detect_popover_elements()
	{
		$("span[data-serialnumber]").popover(
		{
			title: function(){
				var id = $.now();
				return "<div id='pop-title-" + id + "'>Title</div>";
			},
			html: true,
			content: function() {
				var id = $.now();
				return loadPopoverContent(id, $(this).data("serialnumber"));
			}
		});
	}
	detect_popover_elements();




	function loadPopoverContent(id, serialNumber)
	{
		// dismiss any other popovers that might be currently displayed.
		$("span[data-serialnumber!=" + serialNumber + "]").popover('hide');
		var div = $("<div></div>")
			.attr("id", id)
			.text("Loading...")
			.css({
				"height": 200,
				"width": 250
			})

		$.ajax({
			url: MRP_BASE_URL + "clients/detail/" + serialNumber + ".json",
			success: function(response) {
				formatResponse(response, id);
			},
			error: function(response) {
				$("#" + id).html("<span class='text-error'>Error fetching data</span>");
			}
		})
		return div;
	}




	function formatResponse(response, divId)
	{
		var $div = $("#" + divId),
			$title = $("#pop-title-" + divId);
		$div.empty();
		$title.empty();

		if (get_class(response) != '[object Object]')
		{
			$div.text("Error parsing response from server");
			return;
		}

		var img = $("<center>").append($("<img>")
						.attr('src', response.machine.img_url)
						.css("height", "72px"),
					$("<hr>")
				),
			cpu = generateReportItem(
					"Processor",
					response.machine.current_processor_speed
						+ " " + response.machine.cpu_arch
				),
			ram = generateReportItem("Memory",
				response.machine.physical_memory),
			ser = generateReportItem("Serial Number",
				response.machine.serial_number),
			osx = generateReportItem("Software",
				"OS X " + response.machine.os_version);

		$title.text(response.machine.machine_desc);
		$div.append(img, cpu, ram, ser, osx);
	}




	function generateReportItem(title, value)
	{
		return $("<small></small>").append(
			$("<div></div>").append(
				$("<b></b>").text(title + " "),
				$("<span></span>").text(value)
			)
		);
	}