@php
	$field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];

    // each wrapper attribute can be a callback or a string
    // for those that are callbacks, run the callbacks to get the final string to use
    foreach($field['wrapper'] as $attributeKey => $value) {
        $field['wrapper'][$attributeKey] = !is_string($value) && $value instanceof \Closure ? $value($crud, $field, $entry ?? null) : $value ?? '';
    }
	// if the field is required in the FormRequest, it should have an asterisk.
	// we add the base entity to the field name to company for nested relation fields validated with `field.*.key`
	$fieldName = isset($field['baseEntity']) ? $field['baseEntity'].'.'.$field['name'] : $field['name'];
	$fieldName = is_array($fieldName) ? current($fieldName) : $fieldName;
	$required = (isset($action) && $crud->isRequired($fieldName)) ? ' required' : '';
	
	// if the developer has intentionally set the required attribute on the field
	// forget whatever is in the FormRequest, do what the developer wants
	$required = isset($field['showAsterisk']) ? ($field['showAsterisk'] ? ' required' : '') : $required;
	if (isset($loop) && $loop->iteration == 1) {
		$field['wrapper']['class'] = "form-group col-sm-12";
	} else if ($field['type'] != "hidden" || !$field['wrapper']['class']) {
		$field['wrapper']['class'] = $field['wrapper']['class'] ?? "form-group col-sm-3";
	} 
	// $field['wrapper']['class'] = $loop->iteration == 1 ? "form-group col-sm-12" : "form-group col-sm-12" ;// field['type'] != "hidden" || $field['wrapper']['class'] ?? "form-group col-sm-12";
	$field['wrapper']['class'] = $field['wrapper']['class'].$required;
	$field['wrapper']['element'] = $field['wrapper']['element'] ?? 'div';
@endphp

<{{ $field['wrapper']['element'] }}
	@foreach($field['wrapper'] as $attribute => $value)
	    {{ $attribute }}="{{ $value }}"
	@endforeach
>
