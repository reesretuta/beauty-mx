<?php $contents=(array)$contents;?>
<?php unset($contents['id']);?>
<?php unset($contents['date_added']);?>
<?php unset($contents['last_updated']);?>
<?php unset($contents['']);//should be used to unset the _id of things?>

<html>
	<head>
		<link type="text/css" rel="stylesheet" href="/media/css/styles.css"/>
		<link type="text/css" rel="stylesheet" href="/media/css/default_cms.css"/>
	</head>
		<body class="previewer">
		<?php foreach ($contents as $title=>$content):?>
			<?php if(!in_array($title,unserialize(HIDE_PREVIEW_COLUMNS)) && $content):?>
				<div class="heading"><?=humanize($title)?>:</div>
				<?php if(in_array($title, unserialize(UPLOAD_PATHS))):?>
					<div class="content"><img style="max-width: 300px; max-height: 300px; width: auto; height: auto; display: block" src="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?=$content?>"/></div>
				<?php else:?>
					<div class="content"><?=$content?></div>
				<?php endif;?>
				<?php endif;?>
		<?php endforeach;?>
		<p class="dialog">Click anywhere outside this box to close</p>
	</body>
</html>
