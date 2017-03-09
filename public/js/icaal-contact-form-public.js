(function( $ ) {
	'use strict';

	var $form = $('.icaal-contact-form');

	$form.submit(function() {

		var $submit = $form.find('.submit');
		var $response = $form.find('.response');
		var $data = $form.serializeObject();

		$submit.prop('disabled', true);
		$response.empty();

		$.post( variables.ajax_url, {
			nonce: variables.nonce,
			action: 'icaal_contact_form_submission',
			data: $data
		}, function(response) {
			$submit.prop('disabled', false);
			if( response.error ) {
				$response.addAlert('danger', response.error);
        if( typeof icaal_contact_form_failure == 'function' ) {
          icaal_contact_form_failure(response.error);
        }
			} else if( response.errors ) {
				$response.addAlert('danger', 'Validation errors occurred:', response.errors);
        if( typeof icaal_contact_form_failure == 'function' ) {
          icaal_contact_form_failure(response.errors);
        }
			} else {
				$response.addAlert('success', 'Your enquiry has been successfully sent');
				$form.trigger('reset');
        if( typeof icaal_contact_form_success == 'function' ) {
          icaal_contact_form_success();
        }
        if( typeof ga == 'function' ) {
          ga('send', 'event', 'Enquiry', 'submit');
        }
        if( typeof __gaTracker == 'function' ) {
          __gaTracker('send', 'event', 'Enquiry', 'submit');
        }
			}
		});

		return false;
	});

	$.fn.serializeObject = function() {
	  var o = {};
	  var a = this.serializeArray();
	  $.each(a, function() {
	    if (o[this.name] !== undefined) {
	      if (!o[this.name].push) {
	        o[this.name] = [o[this.name]];
	      }
	      o[this.name].push(this.value || '');
	    } else {
	      o[this.name] = this.value || '';
	    }
	  });
	  return o;
	};

  $.fn.addAlert = function( type, message, items ) {

    if( items ) {
      var itemsArray = [];
      $.each(items, function(index, value) {
        itemsArray.push('<li>' + value + '</li>');
      });
      items = itemsArray.join('');
      items = '<h5>' + message + '</h5>' + '<ul class="mb-0">' + items + '</ul>';
      message = items;
    }

    this.append('<div class="alert alert-' + type + '">' + message + '</div>');

  };

})( jQuery );
