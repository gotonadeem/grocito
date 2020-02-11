<div class="form-group row">
    <div class="col-xs-4">
        <label>Weight</label>
    </div>
    <div class="col-xs-4">
        <label>Qty</label>
    </div>
    <div class="col-xs-4">
        <label>Update Qty</label>
    </div>
</div>
<?php
foreach ($item_details as $item){
?>
<div class="form-group row">
    <div class="col-xs-4">
        <label>{{$item->weight}}</label>
    </div>
    <div class="col-xs-4">
        <label>Qty</label>
    </div>
    <div class="col-xs-4">
        <input type="hidden" name="item_id[]" value="<?=$item->id?>" class="item_id_array">
        <input type="hidden" name="product_id" value="<?=$item->product_id?>" class="product_id">
        <input type="text" class="form-control qty" placeholder="qty" name="qty[]"  value="{{$item->qty}}">
    </div>
</div>
<?php } ?>

<div class="form-group text-right">
    <input type="button" onclick="update_product_qty()" class="btn btn-primary" value="Sumbit">
</div>