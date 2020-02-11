<option value="">Select Pincode</option>
<?PHP foreach($data as $vs): ?>
    <option value="<?=$vs->pincode?>"><?=$vs->pincode?></option>
<?PHp endforeach; ?>