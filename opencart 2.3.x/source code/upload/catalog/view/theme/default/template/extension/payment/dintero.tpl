<?php if( $testmode ){ ?>
<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $text_testmode ?></div>
<?php } ?>
  <div class="dintero-error"></div>
  <div class="buttons">
    <div class="pull-right ">
        <input type="button" value="<?php echo $button_confirm ?>" id="button-confirm" data-loading-text="<?php echo $text_loading ?>" class="btn btn-primary" />
    </div>
  </div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
    $('.dintero-error').empty();
	$.ajax({
		url: 'index.php?route=extension/payment/dintero/send',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#payment').before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_wait ?></div>');
		},
		complete: function() {
			$('.alert-dismissible').remove();
			$('#button-confirm').attr('disabled', false);
		},
		success: function(json) {
			if (json['error']) {
                $('.dintero-error').empty().html('<div class="alert alert-danger">' + json['error'] + '</div>');
			}
		
			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script>  

