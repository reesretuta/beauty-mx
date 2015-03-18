		<?php 
		###
		$e=$this->session->userdata('user');
		$f=$e['access'];
		###?>
<table class="mainpage">
	<tr class="bt bb">
		<td class="left">Pages:</td>
		<td class="right">
			<?php  $i=0;?>
			<?php  foreach($groups as $group):?>
				<a class="img mainspan_img" href="<?php echo base_url()?>menu/<?php echo $group->id?>"><img class="swapper" static="<?php echo $group->icon_path?>" active="<?php echo $group->icon_path_active?>" hover="<?php echo $group->icon_path_hover?>" src="<?php echo $group->icon_path?>"/></a>
				<?php  $i++?>
				<?php echo $i%5===0?'<br clear="all"/>':''?>
			<?php  endforeach;?>
		</td>
	</tr>
	<tr>
		<td class="left">Shortcuts:</td>
		<td class="right">
			<?php  $i=0;?>
			<?php  foreach ($tables as $table):?>
			<?php  if(in_array($table->table_name, $f)):?>
				<a class="mainspan_text <?php echo $i<2?'bt':''?>" href="<?php echo base_url()?>content/<?php echo $table->table_name?>"><?php echo str_pad($i+1, 2, 0, STR_PAD_LEFT)?>&nbsp;&nbsp;<?php echo humanizer(plural($table->table_name))?></a>
				<?php echo $i%2===0?'':'<br clear="all"/>'?>
				<?php $i++?>
				<?php endif;?>
			<?php endforeach;?>
		</td>
	</tr>
</table>

<ul style="display: none;">
	<?php foreach ($tables as $table):?>
	<li><a href="<?php echo base_url()?>content/<?php echo $table->table_name?>"><?php echo humanizer(plural($table->table_name))?></a></li>
	<?php endforeach;?>
</ul>
