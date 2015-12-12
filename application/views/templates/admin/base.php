<div class="page_form">

<h2 class="form_name">Настройки сайта</h2>


<form action="/admin/base" method="post">

	<?= Form::hidden('csrf', Security::token()); ?>

	<h4>Title</h4>
	<div class="input_text mb30">
		<?/* <input type="text" name="title" value="<?= $site_info->title ?>" /> */?>
		<textarea name="title"><?= $site_info->title ?></textarea>
	</div>

	<h4>Full name</h4>
	<div class="input_text mb30">
		<input type="text" name="full_name" value="<?= $site_info->full_name ?>" />
	</div>

	<h4>Description</h4>
	<div class="input_text mb30">
		<input type="text" name="description" value="<?= $site_info->description ?>" />
	</div>

	<h4>Address</h4>
	<div class="input_text mb30">
		<input type="text" name="address" value="<?= $site_info->address ?>" />
	</div>

	<? /*<div class="w50 fl_l"> */ ?>
		<h4>Phone</h4>
		<div class="input_text mb30">
			<input type="text" name="phone" value="<?= $site_info->phone ?>" />
		</div>
	<? /*</div>
	<div class="w50 fl_l"> */ ?>
		<h4>Fax</h4>
		<div class="input_text mb30">
			<input type="text" name="fax" value="<?= $site_info->fax ?>" />
		</div>
	<? /*</div> */ ?>

	<h4>Email</h4>
	<div class="input_text mb30">
		<input type="text" name="email" value="<?= $site_info->email ?>" />
	</div>

	<h4>Logo</h4>
	<div class="input_text mb30">
		<input type="text" name="logo" value="<?= $site_info->logo ?>" />
	</div>

	<input class="mt20" type="submit" value="Сохранить">

</form>

</div>