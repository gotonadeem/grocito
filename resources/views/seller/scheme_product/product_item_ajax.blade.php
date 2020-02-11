<option value="">Select Sub category</option>
<?PHP foreach($productItem as $vs): ?>
<option value="<?=$vs->id?>"><?=$vs->weight?> - ₹<?=$vs->sprice?></option>
<?PHP endforeach; ?>