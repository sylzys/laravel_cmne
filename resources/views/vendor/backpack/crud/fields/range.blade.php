<!-- html5 range input -->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    @php
        $name = $field['name'];
    @endphp
    <div class="range-wrap">
        <div class="range-value" id="rangeV"></div>
        <input id="range" type="range"  value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}" step="1" @include('crud::fields.inc.attributes')>
      </div>
    
        <output class="bubble"></output>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@section('after_scripts')
<script type="text/javascript">
    
    const
  range = document.getElementById('range'),
  rangeV = document.getElementById('rangeV'),
  setValue = ()=>{
    const
      newValue = Number( (range.value - range.min) * 100 / (range.max - range.min) ),
      newPosition = 10 - (newValue * 0.2);
    rangeV.innerHTML = `<span>${range.value}</span>`;
    rangeV.style.left = `calc(${2.3 + newValue}% + (${newPosition}px))`;
  };
document.addEventListener("DOMContentLoaded", setValue);
range.addEventListener('input', setValue);
</script>
<style>
    input[type=range] {
  -webkit-appearance: none;
  margin: 20px 0;
  width: 100%;
}
input[type=range]:focus {
  outline: none;
}
input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 4px;
  cursor: pointer;
  animate: 0.2s;
  background: #03a9f4;
  border-radius: 25px;
}
input[type=range]::-webkit-slider-thumb {
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #fff;
  box-shadow: 0 0 4px 0 rgba(0,0,0, 1);
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -8px;
}
input[type=range]:focus::-webkit-slider-runnable-track {
  background: #03a9f4;
}
.range-wrap{
  width: 500px;
  position: relative;
}
.range-value{
  position: absolute;
  top: -70%;
}
.range-value span{
  width: 30px;
  height: 24px;
  line-height: 24px;
  text-align: center;
  background: #03a9f4;
  color: #fff;
  font-size: 12px;
  display: block;
  position: absolute;
  left: 50%;
  transform: translate(-50%, 0);
  border-radius: 6px;
}
.range-value span:before{
  content: "";
  position: absolute;
  width: 0;
  height: 0;
  border-top: 10px solid #03a9f4;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  margin-top: -1px;
}
</style>
@endsection
