<option value="">Select Sub category</option>
<?PHP foreach($category_list as $vs): ?>
<option value="<?=$vs->id?>"><?=$vs->name?></option>
<?PHP endforeach; ?>