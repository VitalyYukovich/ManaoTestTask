$(document).ready(function() {
	$.ajax({
		url: '../php/checksessioncookie.php',
		type: 'GET'
	}).done(function(data) {
		let dataParse = jQuery.parseJSON(data);
		if(dataParse.result == 'error')
			$('.container').html('<button id="regBtn" type="button" class="btn btn-outline-primary btn-lg" data-toggle="modal" data-target="#regModal">Регистрация</button><button id="authBtn" type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#authModal">Авторизация</button>');
		if(dataParse.result == 'success')
			welcome(dataParse.message);
	});

	$('#authForm').on('submit', function(event){
		event.preventDefault();
		let data = $('#authForm').serialize();
		$.ajax({
			url: '../php/authorization.php',
			type: 'POST',
			data: data,
		})
		.done(function(data) {
			let dataParse = jQuery.parseJSON(data);
			if(dataParse.result == 'success'){
				$('#authModal').modal('hide');
				$('#authForm')[0].reset();
				welcome(dataParse.message);
			}
			if(dataParse.result == 'error'){
				Swal.fire({
					'title': 'Заблокировано',
					'text': dataParse.message,
					'type': 'error'
				});
			}
		})
	});
	$('#regForm').on('submit', function(event){
		event.preventDefault();
		let data = $('#regForm').serialize();
		$.ajax({
			url: '../php/registration.php',
			type: 'POST',
			data: data,
		})
		.done(function(data) {
			let dataParse = jQuery.parseJSON(data);
			if(dataParse.result == 'success'){
				Swal.fire({
					'title': 'Успешно',
					'text': dataParse.message,
					'type': 'success'
				});
				$('#regModal').modal('hide');
				$('#regForm')[0].reset();
			}
			if(dataParse.result == 'error'){
				Swal.fire({
					'title': 'Заблокировано',
					'text': dataParse.message,
					'type': 'error'
				});
			}
		})
	});
	
	hideModal('#regModal');
	hideModal('#authModal');
});

function welcome(text){
	$('.container').html('<p style="font-size: 20px;">' + text + '</p>');
}
function hideModal(idModal){
	$(idModal).on('hidden.bs.modal', function (e) {
	  	$(idModal).find('form')[0].reset();
	})
}