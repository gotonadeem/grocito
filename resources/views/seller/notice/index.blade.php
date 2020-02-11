@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar">
    <div class="notice">
        <div class="notice-page-heading">Notices</div>
        <div class="notice_page">
		<?PHP foreach($data as $vs): ?>
            <div class="notice-container unread-notice">
                <div class="notice-header clearfix">
                    <div class="notice-date float-left">Posted: <?=date('d M Y h:i:a', strtotime($vs->created_at))?></div>
                    <div class="float-right">
                        <div class="notice-right-buttons notice-tags"></div>
                    </div>
                </div>
                <div class="notice-body">	
                    <div class="admin-notice-title"><?=$vs->heading;?></div>
                    <div class="admin-notice-description">
					 <?=$vs->description;?>
					</div>
                </div>
            </div>
         <?PHP endforeach; ?>
		 </div>
    </div>
</div>

@endsection