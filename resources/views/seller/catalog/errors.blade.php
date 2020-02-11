@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar">
    <div class="notice">
        <div class="notice-page-heading">Catalog errors (<?=$product->name;?>)</div>
        <div class="notice_page">
		<?PHP foreach($data as $vs): ?>
            <div class="notice-container unread-notice">
                <div class="notice-header clearfix">
                    <div class="notice-date float-left"><?=date('d M Y h:i:a', strtotime($vs->created_at))?></div>
                    <div class="float-right">
                        <div class="notice-right-buttons notice-tags"></div>
                    </div>
                </div>
                <div class="notice-body">	
                    <div class="admin-notice-title">Status: &nbsp;&nbsp;<?=str_replace("_"," ",$vs->heading);?></div><br>
                    <div class="admin-notice-description">
					 <span class="admin-notice-title" style="text-decoration:underline;">Message:-</span>
					 <span><?=$vs->message;?></span>
					</div>
                </div>
            </div>
         <?PHP endforeach; ?>
		 </div>
    </div>
</div>

<style>
.notice-page-heading{
	background-color:brown;
}
.notice-header
{
	background-color:brown !important;
}
.admin-notice-title
{
	text-transform:capitalize;
}
</style>
@endsection
