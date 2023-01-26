function GetManualCurrency() {
	$.ajax({
        dataType: 'json',
        url: "/ajax/get/currency",
        type: "GET",
        data: {
        },
        success: function(data) {
        	if(data['status'] == true) {
        		$(".currency-modal-body").html('');
        		$(".currency-modal-body").append(`
        			<div class="currency-body">
	        			<form id="currency_form">
	                        <div class="modal-body">
	                            <label for="`+data['data']['0']['code']+`">`+data['data']['0']['code']+`: </label>
	                            <div class="mb-1">
	                                <input type="text" id="`+data['data']['0']['code']+`" name="`+data['data']['0']['code']+`" class="form-control" / value="`+data['data']['0']['rate']+`">
	                            </div>
	                            <label for="`+data['data']['1']['code']+`">`+data['data']['1']['code']+`: </label>
	                            <div class="mb-1">
	                                <input type="text" id="`+data['data']['1']['code']+`" name="`+data['data']['1']['code']+`" class="form-control" / value="`+data['data']['1']['rate']+`">
	                            </div>
	                        </div>
	                    </form>
                    </div>
    			`);
				$("#currency_modal").modal('show');
        	}
        }
    });
}

function SaveManualCurrency() {
	var form = $('#currency_form')[0];
    var data = new FormData(form);

    $.ajax({
        dataType: 'json',
        url: "/ajax/save/currency",
        type: "POST",
        data: data,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
			if(data['status'] == true) {
				Swal.fire({
			        icon: 'success',
			        title: data['message'],
			        showConfirmButton: false,
			        timer: 1500,
			        customClass: {
			          confirmButton: 'btn btn-primary'
			        },
			        buttonsStyling: false
		      	});
		      	location.reload();
			}            
        }
    });
}