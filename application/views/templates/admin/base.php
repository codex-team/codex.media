<h3 class="p30">Настройки сайта</h3>


<form action="/admin/base" method="post">

	<?= Form::hidden('csrf', Security::token()); ?>

	<label>Title</label>
	<input type="text" name="title" value="<?= $site_info->title ?>">

	<label>Full name</label>
	<input type="text" name="full_name" value="<?= $site_info->full_name ?>">

	<label>Description</label>
	<input type="text" name="description" value="<?= $site_info->description ?>">

	<label>Address</label>
	<input type="text" name="address" value="<?= $site_info->address ?>">

	<label>Phone</label>
	<input type="text" name="phone" value="<?= $site_info->phone ?>">

	<label>Fax</label>
	<input type="text" name="fax" value="<?= $site_info->fax ?>">

	<label>Email</label>
	<input type="text" name="email" value="<?= $site_info->email ?>">

	<label>Logo</label>
	<input type="text" name="logo" value="<?= $site_info->logo ?>">
	
	<input class="mt20" type="submit" value="Опубликовать">

</form>