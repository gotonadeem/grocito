<option value="">Select Seller</option>
<?php
foreach($data as $vs){ ?>
<option value="<?=$vs['user']['id']?>"><?=$vs['user']['username']?>(<?=$vs['user']['email']?>)</option>
<?php } ?>