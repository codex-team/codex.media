<div class="contacts_map">

	<div id="map"></div>

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


<!-- Yandex.Maps  -->

<script async src="https://api-maps.yandex.ru/2.1/?lang=ru_Ru" type="text/javascript"></script>

<script type="text/javascript">

	ymaps.ready(init);

	var myMap,
		myPlacemark;

	function init() {

		myMap = new ymaps.Map("map", {
			center: [<?= $site_info['coordinates'] ?>],
			zoom: 16
		});

		myPlacemark = new ymaps.Placemark([<?= $site_info['coordinates'] ?>], {
			hintContent    : '<?= $site_info['title'] ?>',
			balloonContent : '<?= $site_info['full_name'] ?>'
		});

		myMap.geoObjects.add(myPlacemark);
	}

</script>

<!-- /Yandex.Maps  -->
