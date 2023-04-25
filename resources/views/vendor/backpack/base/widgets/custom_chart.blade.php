@php
  // -----------------------
  // Backpack ChartJS Widget
  // -----------------------
  // Uses:
  // - Backpack\CRUD\app\Http\Controllers\ChartController
  // - https://github.com/ConsoleTVs/Charts
  // - https://github.com/chartjs/Chart.js

  $controller = new $widget['controller'];
  // $chart = $controller->chart;
  // $path = $controller->getLibraryFilePath();
  // dd($controller);
  $data = $controller->data;
  $uuid = $controller->uuid;
  // $layout = $controller->layout;
  // defaults
  $widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-md-4';
@endphp

@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
  <div class="{{ $widget['class'] ?? 'card' }}">
    @if (isset($widget['content']['header']))
    <div class="card-header">{!! $widget['content']['header'] !!}</div>
    @endif
    <div class="card-body">

      {!! $widget['content']['body'] ?? '' !!}

      <div class="card-wrapper">
        {{-- {!! $chart->container() !!} --}}
        {{-- <canvas id="{{ $chart->id }}" {!! $chart->formatContainerOptions('html') !!}></canvas>
@include('charts::loader') --}}
      <div id="chart-{{$uuid}}"></div>

      </div>

    </div>
  </div>

@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')

@push('before_scripts')
{{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
    {{-- <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> --}}
    <script src="https://cdn.plot.ly/plotly-2.12.1.min.js"></script>
  @endpush
@push('after_scripts')

<script>

  var data = <?= json_encode($data) ?>//[trace1, trace2];
  // if (data[0].type == 'pie') {
  //   var layout =  {margin: {"t": 0, "b": 0, "l": 0, "r": 0}};
  // } else {
    var layout = {height: 600, width: 600,
      font: {
        size: 16
      },
    barmode: 'stack'};
  // }


  Plotly.newPlot('chart-{{$uuid}}', data, layout);

</script>
@endpush
