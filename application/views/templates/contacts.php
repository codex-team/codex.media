<div class="contacts_map">
	<div id="map" style="height: 500px; margin-top: 2px;z-index: 1"></div>
	<div class="text_wrap">
		<div class="text">
			<li><?= $site_info->address ?></li>

			<div class="contacts mt30">
				<li>Телефон: <?= $site_info->phone ?></li>
				<li>Факс: <?= $site_info->fax ?></li>
				<li>Почта: <?= $site_info->email ?></li>
			</div>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){

		var w_height = $(window).height();
		$('#map').css('height', w_height );
		<? /*
			#$lat = 59.919041;
			#$lon = 30.4875325;
		?>
		<!-- user.initialize_GoogleMap( document.getElementById('map') , <?= $lat ?>, <?= $lon ?>, false , false , false ); */
		?>
		user.initialize_GoogleMap( document.getElementById('map') , <?= $site_info->coordinates ?>, false , false , false );

	});

</script>

