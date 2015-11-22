<div class="contacts_map">
	<div id="map" style="height: 500px; margin-top: 2px;z-index: 1"></div>
	<div class="text_wrap">
		<div class="text">
			<li>193312 Санкт-Петербург</li>
			<li>Товарищеский пр., д. 10, корп. 2</li>

			<div class="contacts mt30">
				<li>Телефон: 580-89-08; 584-54-98</li>
				<li>Факс: 580-82-49</li>
				<li>Почта: spb_school332@mail.ru</li>
			</div>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){

		var w_height = $(window).height();
		$('#map').css('height', w_height );
		<?
			$lat = 59.919041;
			$lon = 30.4875325;
		?>
		user.initialize_GoogleMap( document.getElementById('map') , <?= $lat ?>, <?= $lon ?>, false , false , false );

	});

</script>

