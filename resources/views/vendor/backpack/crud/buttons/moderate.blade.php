@php
	$url = explode('/', url()->current());
    $id = $url[count($url) - 1];
@endphp
@if($entry->validated == 0)
<a href="#" class="btn btn-sm btn-link moderate-button" data-toggle="modal" data-target="#moderateModal" aria-controls="moderateModal" aria-expanded="false"><i class="la la-check-circle"></i> Moderate</a>
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<div class="modal fade" id="moderateModal" tabindex="-1" role="document" aria-labelledby="moderateModalLabel" aria-hidden="true">
	<div class="modal-dialog moderate-modal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="moderateModalLabel">Moderate item</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="validatation-form" enctype="multipart/form-data" >
					<div class="form-group col-sm-12" element="div"> <label>Please make a choice</label>
						<div data-init-function="bpFieldInitRadioElement" class="form-group" element="div" data-initialized="true">
							<input type="hidden" value="" name="choice" data-moderation={{$id}}>
							<div class="form-check ">
								<input type="radio" class="form-check-input" value="0" name="action" id="radio_6740500">
								<label class=" form-check-label font-weight-normal" for="radio_6740500">Validate submitted equipment</label>
							</div>
							<div class="form-check ">
								<input type="radio" class="form-check-input" value="1" name="action" id="radio_6740501">
								<label class=" form-check-label font-weight-normal" for="radio_6740501">Replace submitted equipment by its CP closest element</label>
							</div>
							<div class="form-check ">
								<input type="radio" class="form-check-input" value="2" name="action" id="radio_6740502">
								<label class=" form-check-label font-weight-normal" for="radio_6740502">Delete submitted element</label>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="cancelValid btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="submitValid btn btn-primary">Confirm</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function checkValidation() {
	if ($('.moderate-button').length == 0) {
		console.log("QDsqd0");
		swal({
			title: "{!! trans('backpack::base.success') !!}",
			text: "All uploaded items have been treated",
			icon: "success",
			showDenyButton: false,
			showCancelButton: false,
			confirmButtonText: 'OK',
			// denyButtonText: `Don't save`,
			dangerMode: false,
			}).then((value) => {
					$.ajax({
						url: "{{ url($crud->route.'/' . $id . '/validate-upload') }}",
						type: 'POST',
					});
				});
			}
		}
	$(".submitValid").unbind().click(function(e) {
		e.preventDefault();
		$elem = $(this).parent().prev();
		$action = $("input[name='action']:checked", $elem).val();
		$moderation = $("input[name='choice']", $elem).data('moderation');
		$that = $(this);
		$.ajax({
			type: "POST",
			url: "{{url($crud->route.'/'.$entry->getKey().'/moderate') }}",
			data: {
				'action': $action,
				'closest': "{{$entry->closest}}",
				'moderation': $moderation
			},
			dataType: "json",
			success: function(response) {
				$('form', $elem)[0].reset();
				$that.prev().trigger('click');
				new Noty({
					type: "success",
					text: "The item has been moderated",
					}).show();
				$("#crudTable").DataTable().ajax.reload();
				setTimeout(() => {
					checkValidation()
				}, 500);
			}
		});
	});
</script>
@if (!request()->ajax()) @endpush @endif
@endif
