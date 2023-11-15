function drawChart(urlx, typex, idx, range, datax) {
	$('#' + idx).remove();
	$('#' + idx + '_cols').append('<div id="' + idx + '_load"><i class="fa fa-refresh fast-spin text-center fa-3x" style="position:absolute;color:#9e9e9e;left:50%;top:50%"></i></div><canvas id="' + idx + '"><canvas>');
	$(function () {
		var axes = true;
		if (typex == 'pie') {
			axes = false;
		}
		var legend = true;
		if (typex == 'horizontalBar') {
			legend = false;
		}
		var options = {
			responsive: true,
			legend: {
				display: legend
			},
			title: {
				display: false,
				text: 'Diagram'
			},
			scales: {
				xAxes: [{
					display: axes,
					ticks: {
						callback: function (dataLabel, index) {
							return range === true ? (index % 2 === 0 ? dataLabel : '') : dataLabel;
						}
					}
				}],
				yAxes: [{
					display: axes,
					// beginAtZero: true,
					ticks: {
						beginAtZero: true,
						steps: 10,
						stepValue: 5,
						// max: 100
					}
				}]
			}
		};
		var jsonData = $.ajax({
			url: urlx,
			type: 'ajax',
			dataType: 'json',
			async: false,
			method: "POST",
			data: datax,
		}).done(function (results) {
			$('#' + idx + '_load').remove();
			var config = {
				type: typex,
				data: results,
				options: options,
			};
			var ctx = $('#' + idx).get(0).getContext('2d');
			var chart = new Chart(ctx, config);
		});
	});
}
