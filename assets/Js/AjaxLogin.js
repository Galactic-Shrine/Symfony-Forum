/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

import 'toastr/build/toastr.min.css';
import $ from 'jquery';
import toastr from 'toastr';

$(() => {

	$('#LoginForm').on('submit', function (e) {

		e.preventDefault();

		const data = {
			Email: $('input[name="Email"]').val(),
			Password: $('input[name="Password"]').val(),
			_csrf_token: $('input[name="_csrf_token"]').val(),
		};

		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			contentType: 'application/json',
	  		data: JSON.stringify(data),
			success: (response) => {

				console.log('Réponse AJAX :', response); // Consigner la réponse
				toastr.success(response.message);
				location.reload();
			},
			error: (xhr) => {

				console.log('Erreur AJAX :', xhr); // Consigner la réponse d'erreur
				let errorMessage = 'An error occurred. Please try again.';

				if (xhr.responseJSON && xhr.responseJSON.message) {
					
				  errorMessage = xhr.responseJSON.message;
				}
				toastr.error(errorMessage);
			}
		});
	});
});
