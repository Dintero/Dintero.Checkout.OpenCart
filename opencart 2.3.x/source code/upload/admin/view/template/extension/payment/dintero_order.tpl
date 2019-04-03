<form class="form-horizontal">
    <!--
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-override">
        Refund payment in Dintero
      </label>
      <div class="col-sm-10">
          <a class="btn btn-danger btn-sm" href="https://backoffice.dintero.com/" target="_blank " >Refund in Dintero</a>
      </div>
    </div>
    -->
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-override">
        Curent status in Dintero system
      </label>
      <div class="col-sm-10">
            <span style="background:<?php echo $_color ?>" class="badge"><?php echo $dintero_status?></span>          
           <a  href="javascript:void(0)" onclick="update_dintero_status()"  data-loading-text="loading..." class="btn chs_in_dintero">Update status</a>
      </div>
    </div>    
    
    
        <?php if( $dintero_status == 'AUTHORIZED' ){ ?>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-override">
            Capture payment in Dintero system
          </label>
          <div class="col-sm-10">
              <a class="btn btn-success btn-sm chs_in_dintero" href="javascript:void(0)" onclick="change_dintero_status('capture')" data-loading-text="loading..." >Capture</a>
          </div>
        </div>
            
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-override">
            Void payment in Dintero system
          </label>
          <div class="col-sm-10">
              <a class="btn btn-success btn-sm chs_in_dintero" href="javascript:void(0)" onclick="change_dintero_status('void')" data-loading-text="loading..." >Void</a>
          </div>
        </div>    
        <?php } ?>
    
    <?php if( $dintero_status == 'CAPTURED' ){ ?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-override">
        Refund payment in Dintero system
      </label>
      <div class="col-sm-10">
          <a class="btn btn-success btn-sm chs_in_dintero" href="javascript:void(0)" onclick="change_dintero_status('refund')" data-loading-text="loading..." >Refund</a>
      </div>
    </div>  
    <?php } ?>  
    <input type="hidden" value="<?php echo $_href?>" id="_href" />  
</form>

<script>
$(document).delegate('.chs_in_dintero', 'click', function() {
    return false;
});

function update_dintero_status(){
    _href = $('#_href').val();
    $.ajax({
        url  : _href+'index.php?route=extension/payment/dintero/update_status',
        type : 'post',
        data : 'order_id=<?php echo $order_id?>',
        beforeSend: function() {
            $('.chs_in_dintero').button('loading');
		}, 
        complete: function() {
			$('.chs_in_dintero').button('reset');
        },               
        dataType: 'json',
        success: function (json) {
            
            if(json['error']){
                alert(json['error']);
            } else {
                location.reload();
            }
        }
    });       
}

function change_dintero_status(dintero_status){
    _href = $('#_href').val();
    $.ajax({
        url: _href+'index.php?route=extension/payment/dintero/change_status',
        type: 'post',
        data:'dintero_status='+ dintero_status+'&order_id=<?php echo $order_id?>',
        beforeSend: function() {
            $('.chs_in_dintero').button('loading');
		},      
        complete: function() {
			$('.chs_in_dintero').button('reset');
        },             
        dataType: 'json',
        success: function (json) {
            if(json['error']){
                alert(json['error']);
            } else {
                location.reload();
            }
        }
    });   
    return false;
} 
</script>