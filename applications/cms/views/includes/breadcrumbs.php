<?php if($this->uri->total_segments()>0): ?>
	<?php $i=0;?>
	<?php $urls=explode('/', $this->uri->uri_string());?>
	
	
	<div class="breadcrumb" <?php if(in_array('edit',$urls) || in_array('add', $urls)):?>style="border-bottom: 0;"<?php endif;?>>
		<a href="<?=base_url()?>">home</a>
		<?php if(isset($breadcrumbs)):?>
			<?php if(sizeof($breadcrumbs)>0):?>
				<?php foreach ($breadcrumbs as $k=>$v): ?>
					&nbsp;&rsaquo;&nbsp;
					<?php if($k!='none'):?>
						<a href="<?=base_url().$k;?>"><?=$v;?></a>
					<?php else: ?>
						<?=$v;?>
					<?php endif;?>
				<?php endforeach;?>
			<?php endif;?>
		<?php endif;?>
	</div>
<?php endif;?>