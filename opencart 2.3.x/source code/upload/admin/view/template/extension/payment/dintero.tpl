<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cod" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action ?>" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general ?></a></li>
            <li><a href="#tab-status" data-toggle="tab"><?php echo $tab_order_status ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group ">
                <label class="col-sm-3 control-label" for="payment_dintero_client_id"><?php echo $entry_client_id ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_client_id" value="<?php echo $payment_dintero_client_id ?>" placeholder="<?php echo $entry_client_id ?>" id="payment_dintero_client_id" class="form-control"/>
                    <?php if( $error_client_id) { ?>
                    <div class="text-danger"><?php echo $error_client_id ?></div>
                    <?php } ?>
                </div>
              </div>    
              
              <div class="form-group ">
                <label class="col-sm-3 control-label" for="payment_dintero_client_secret"><?php echo $entry_client_secret ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_client_secret" value="<?php echo $payment_dintero_client_secret ?>" placeholder="<?php echo $entry_client_secret ?>" id="payment_dintero_client_secret" class="form-control"/>
                    <?php if ($error_client_secret) { ?>
                    <div class="text-danger"><?php echo $error_client_secret ?></div>
                    <?php } ?>
                </div>
              </div>   
              
              <div class="form-group ">
                <label class="col-sm-3 control-label" for="payment_dintero_payment_profile_id"><?php echo $entry_payment_profile_id ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_payment_profile_id" value="<?php echo $payment_dintero_payment_profile_id ?>" placeholder="<?php echo $entry_payment_profile_id ?>" id="payment_dintero_payment_profile_id" class="form-control"/>
                    <?php if ($error_payment_profile_id) { ?>
                    <div class="text-danger"><?php echo $error_payment_profile_id ?></div>
                    <?php } ?>
                </div>
              </div>               
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="payment_dintero_client_id"><?php echo $entry_client_id_test ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_client_id_test" value="<?php echo $payment_dintero_client_id_test ?>" placeholder="<?php echo $entry_client_id_test ?>" id="payment_dintero_client_id_test" class="form-control"/>
                </div>
              </div>               
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="payment_dintero_client_secret_test"><?php echo $entry_client_secret_test ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_client_secret_test" value="<?php echo $payment_dintero_client_secret_test ?>" placeholder="<?php echo $entry_client_secret_test ?>" id="payment_dintero_client_secret_test" class="form-control"/>
                </div>
              </div>                                                    
            
              <div class="form-group ">
                <label class="col-sm-3 control-label" for="payment_dintero_payment_profile_id_test"><?php echo $entry_payment_profile_id_test ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_payment_profile_id_test" value="<?php echo $payment_dintero_payment_profile_id_test ?>" placeholder="<?php echo $entry_payment_profile_id_test ?>" id="payment_dintero_payment_profile_id_test" class="form-control"/>
                    <?php if ($error_payment_profile_id_test) { ?>
                    <div class="text-danger"><?php echo $error_payment_profile_id_test ?></div>
                    <?php } ?>
                </div>
              </div>             
            
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="payment_dintero_account_id"><?php echo $entry_account_id ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_account_id" value="<?php echo $payment_dintero_account_id ?>" placeholder="<?php echo $entry_account_id ?>" id="payment_dintero_account_id" class="form-control"/>
                    <?php if ($error_account_id) { ?>
                    <div class="text-danger"><?php echo $error_account_id ?></div>
                    <?php } ?>
                </div>
              </div>             
            
              <div class="form-group required">
                <label class="col-sm-3 control-label" for="payment_dintero_title"><?php echo $entry_title ?></label>
                <div class="col-sm-9">
                    <input type="text" name="payment_dintero_title" value="<?php echo $payment_dintero_title ?>" placeholder="<?php echo $entry_title ?>" id="payment_dintero_title" class="form-control"/>
                    <?php if ($error_title) { ?>
                    <div class="text-danger"><?php echo $error_title ?></div>
                    <?php } ?>
                </div>
              </div>                
            
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-live-demo"><?php echo $entry_test ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_test" id="input-live-demo" class="form-control">
                    <?php if ($payment_dintero_test) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes ?></option>
                    <option value="0"><?php echo $text_no ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes ?></option>
                    <option value="0" selected="selected"><?php echo $text_no ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-transaction"><?php echo $entry_transaction ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_transaction" id="input-transaction" class="form-control">
                    <?php if (!$payment_dintero_transaction) { ?>
                    <option value="0" selected="selected"><?php echo $text_authorization ?></option>
                    <?php } else { ?>
                    <option value="0"><?php echo $text_authorization ?></option>
                    <?php } ?>
                    <?php if ($payment_dintero_transaction) { ?>
                    <option value="1" selected="selected"><?php echo $text_sale ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_sale ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total ?>"><?php echo $entry_total ?></span></label>
                <div class="col-sm-9">
                  <input type="text" name="payment_dintero_total" value="<?php echo $payment_dintero_total ?>" placeholder="<?php echo $entry_total ?>" id="input-total" class="form-control"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-geo-zone"><?php echo $entry_geo_zone ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones ?></option>
                    <?php foreach($geo_zones AS $geo_zone){ ?>
                        <?php if ($geo_zone['geo_zone_id'] == $payment_dintero_geo_zone_id) { ?>
                        <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-status"><?php echo $entry_status ?></label>
                <div class="col-sm-9">
                  <select name="dintero_status" id="input-status" class="form-control">
                    <?php if ($dintero_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled ?></option>
                    <option value="0"><?php echo $text_disabled ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-sort-order"><?php echo $entry_sort_order ?></label>
                <div class="col-sm-9">
                  <input type="text" name="payment_dintero_sort_order" value="<?php echo $payment_dintero_sort_order ?>" placeholder="<?php echo $entry_sort_order ?>" id="input-sort-order" class="form-control"/>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-status">
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-canceled-reversal-status"><?php echo $entry_authorized_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_authorized_status_id" id="input-canceled-reversal-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                        <?php if ($order_status['order_status_id'] == $payment_dintero_authorized_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-expired-status"><?php echo $entry_captured_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_captured_status_id" id="input-expired-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_captured_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>              
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-completed-status"><?php echo $entry_declined_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_declined_status_id" id="input-completed-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_declined_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-expired-status"><?php echo $entry_failed_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_failed_status_id" id="input-expired-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_failed_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-expired-status"><?php echo $entry_refunded_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_refunded_status_id" id="input-expired-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_refunded_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>              
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-failed-status"><?php echo $entry_unknown_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_unknown_status_id" id="input-failed-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_unknown_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-authorization_voided-status"><?php echo $entry_authorization_voided_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_authorization_voided_status_id" id="input-authorization_voided-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_authorization_voided_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>              
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-partially_captured-status"><?php echo $entry_partially_captured_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_partially_captured_status_id" id="input-partially_captured-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_partially_captured_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>                
              
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-partially_captured_refunded-status"><?php echo $entry_partially_captured_refunded_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_partially_captured_refunded_status_id" id="input-partially_captured_refunded-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_partially_captured_refunded_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>               
              
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-_partially_refunded-status"><?php echo $entry_partially_refunded_status ?></label>
                <div class="col-sm-9">
                  <select name="payment_dintero_partially_refunded_status_id" id="input-_partially_refunded-status" class="form-control">
                    <?php foreach($order_statuses AS $order_status){ ?>
                    <?php if ($order_status['order_status_id'] == $payment_dintero_partially_refunded_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                        <?php } ?>
                    <?php }  ?>
                  </select>
                </div>
              </div>                  
              
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 