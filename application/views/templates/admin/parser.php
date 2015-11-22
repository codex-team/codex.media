<?
	$all_news = DB::query(Database::SELECT , "SELECT id_forum, title, is_achieve , `text`, `date`   FROM forums , forums_posts WHERE forums_posts.id_parent = forums.id_forum AND is_main = 1 AND is_news = 1 AND status = 'opened' ORDER BY id_forum DESC")->execute()->as_array();
?>

<? $styleREGEXP = '/\s(style=".*?")/i' ?>
<? $widthREGEXP = '/\s(width=".*?")/i' ?>
<? $heightREGEXP = '/\s(height=".*?")/i' ?>



<table class="admin_table">
	<? foreach ($all_news as $news): ?>
		<tr>
			<td>
				<h2><?= $news['title'] ?></h2>

				<? $text = strip_tags(trim($news['text']), '<br><del><p><a><strike><blockquote><ul><li><ol><img><tr><table><td><th><h1><h2><h3><iframe>' ); ?>

				<? $text = preg_replace($styleREGEXP, "", $text) ?>
				<? $text = preg_replace($widthREGEXP, "", $text) ?>
				<? $text = preg_replace($heightREGEXP, "", $text) ?>

				<?= Debug::vars(Security::xss_clean( $text )); ?></td>
			<td><?= $news['date'] ?></td>
		</tr>
	<? endforeach ?>
</table>