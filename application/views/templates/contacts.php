<div class="contacts_map">
	<div id="map" style="height: 500px;z-index: 1"></div>
	<div class="text_wrap">
		<div class="text">
			<li><?= $site_info['address'] ?></li>

			<div class="contacts mt30">
				<li>Телефон: <?= $site_info['phone'] ?></li>
				<li>Факс: <?= $site_info['fax'] ?></li>
				<li>Почта: <?= $site_info['email'] ?></li>
			</div>
		</div>
	</div>
</div>

<script>

	<?
	/**
	 * Google maps
	 * <?= $site_info['coordinates'] ?>
	 */

	 /*

	$(document).ready(function(){

		var w_height = $(window).height();
		$('#map').css('height', w_height );

		user.initialize_GoogleMap( document.getElementById('map') , <?= $site_info['coordinates'] ?>, false , false , false );


	});

	*/ ?>

</script>
