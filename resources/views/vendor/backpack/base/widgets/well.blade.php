@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
    <div class="col-md-6">
        <!-- Chart's container -->
    <div id="chart" style="height: 500px;"></div>
    <!-- Charting library -->
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
    <!-- Your application script -->
    <script>
        function addCommas(nStr)
	{
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
      const chart = new Chartisan({
        el: '#chart',
        // url: "@chart('footprint_chart')",
        url: "/api/chart/{{ $widget['chart'] }}",
        hooks: new ChartisanHooks()
			.colors(['#a51e22', '#023764'])
			.legend({ position: 'bottom' })
			.title('Total Revenue')
			.tooltip({
				enabled: true,
				mode: 'single',
				callbacks: {
					label: function(tooltipItems, data) {
						return '$' + addCommas(tooltipItems.yLabel) + '.00';
					}
				}
			})
			.custom(function({ data, merge, server }) {
				// data ->   Contains the current chart configuration
				//           data that will be passed to the chart instance.
				// merge ->  Contains a function that can be called to merge
				//           two javascript objects and returns its merge.
				// server -> Contains the server information in case you need
				//           to acces the raw information provided by the server.
				//           This is mostly used to access the `extra` field.

				return merge(data, {
					options: {
						scales: {
							yAxes: [{
								ticks: {
									// Include a dollar sign in the ticks
									callback: function(value, index, values) {
										return '$' + addCommas(value);
									}
								}
							}]
						}
					}
				});

				// The function must always return the new chart configuration.
			})
      });
    </script>
    </div>
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')
