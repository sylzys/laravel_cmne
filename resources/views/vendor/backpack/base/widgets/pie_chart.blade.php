@php
  // -----------------------
  // Backpack ChartJS Widget
  // -----------------------
  // Uses:
  // - Backpack\CRUD\app\Http\Controllers\ChartController
  // - https://github.com/ConsoleTVs/Charts
  // - https://github.com/chartjs/Chart.js

  $controller = new $widget['controller'];
  $data = $controller->data;
  $uuid = $controller->uuid;
  $text = $controller->text;
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
  <script src="https://cdn.plot.ly/plotly-2.12.1.min.js"></script>
@endpush
@push('after_scripts')

<script>

  var data = <?= json_encode($data) ?>//[trace1, trace2];

  var layout = {
  title: 'Global Emissions 1990-2011',
  annotations: [
    {
      font: {
        size: 20
      },
      showarrow: false,
      text: 'GHG',
      x: 0.17,
      y: 0.5
    },
    {
      font: {
        size: 20
      },
      showarrow: false,
      text: 'CO2',
      x: 0.82,
      y: 0.5
    }
  ],
  height: 400,
  width: 600,
  showlegend: false,
  grid: {rows: 1, columns: 2}
};

</script>
@endpush
