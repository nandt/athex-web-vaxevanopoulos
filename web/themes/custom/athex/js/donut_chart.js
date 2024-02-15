(function ($, Drupal) {
	Drupal.behaviors.chartCustomBehavior = {
	  attach: function (context, settings) {
		var drawChart;
		var dataArray = [['label', 'value']];
		var colorArray = [];

		google.charts.load("current", { packages: ["corechart"] });

		google.charts.setOnLoadCallback(function () {
		  drawChart = function (dataArray, colorArray) {
			var data = google.visualization.arrayToDataTable(dataArray);

			var options = {
			  pieHole: 0.5,
			  colors: colorArray,
			  chartArea: { width: '100%', left: '30%' },
			  width: '100%'
			};

			var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
			chart.draw(data, options);
		  }

		  function showChartForDataItem($dataItem) {
			dataArray = [['label', 'value']];
			colorArray = [];

			$dataItem.find('.donut_data_item_elements').each(function () {
			  var label = $(this).find('.ddi_label').text().trim();
			  var value = parseFloat($(this).find('.ddi_value').text().trim());
			  var color = $(this).find('.ddi_color').text().trim();
			  colorArray.push(color);
			  dataArray.push(['"' + label + '"', value]);
			});

			drawChart(dataArray, colorArray);
		  }

		  var $firstDataItem = $('.donut_data_item:first');
		  var $firstDataItemLabel = $('.ddi_title:first');
		  $firstDataItem.addClass('active');
		  $firstDataItemLabel.addClass('active');
		  showChartForDataItem($firstDataItem);

		  $('.ddi_title').click(function () {
			$('.ddi_title').removeClass('active');
			$(this).addClass('active');
			var $dataItem = $(this).closest('.donut_data_item');

			$('.donut_data_item').removeClass('active');
			$dataItem.addClass('active');

			showChartForDataItem($dataItem);
		  });

		  $(window, context).resize(function () {
			drawChart(dataArray, colorArray);
		  }).resize(); // Trigger initial resize

		});
	  }
	};
  })(jQuery, Drupal);
