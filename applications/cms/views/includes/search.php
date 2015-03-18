<?php if(uri_string()!='login'):?>
	<div class="sitesearch">
		<div class="iconset">
			<span class="dashboard_text">&nbsp;</span>
			<a rel="dashboard" class="img dashboard" href="<?=base_url()?>"><img class="swapper" src="/media/images/icons/Dashboard_Button_static.png" static="/media/images/icons/Dashboard_Button_static.png" active="/media/images/icons/Dashboard_Button_active.png" hover="/media/images/icons/Dashboard_Button_roll.png" alt="dashboard"/></a>
			<a rel="go to site" class="img dashboard" href="/"><img class="swapper" src="/media/images/icons/GoToSite_Button_static.png" static="/media/images/icons/GoToSite_Button_static.png" active="/media/images/icons/GoToSite_Button_active.png" hover="/media/images/icons/GoToSite_Button_roll.png" alt="go to site"/></a>
			<a id="help_button" rel="help" class="img dashboard" href="#"><img class="swapper" src="/media/images/icons/Help_Button_static.png" static="/media/images/icons/Help_Button_static.png" active="/media/images/icons/Help_Button_active.png" hover="/media/images/icons/Help_Button_roll.png" alt="help"/></a>
			<a rel="logout" class="img dashboard" href="<?=base_url()?>logout/"><img class="swapper" src="/media/images/icons/Logout_Button_static.png" static="/media/images/icons/Logout_Button_static.png" active="/media/images/icons/Logout_Button_active.png" hover="/media/images/icons/Logout_Button_roll.png" alt="logout"/></a>
		</div>
		<?php if(strlen(uri_string())>0):?>
			<?=form_open(base_url().'_search', 'method="get" class="_searchform"')?>
			<span class="big_input"><input type="text" value="search..." name="search" class="_search"/></span>
			<span class="big_select">
				<select name="table" class="searchtable">
					<?php $dataset=$this->db->query("SELECT table_name FROM cms_table_rules where is_hidden=0")->result();?>
					<?php foreach($dataset as $data):?>
						<option value="<?=$data->table_name?>">in <?=humanizer(plural($data->table_name))?></option>
					<?php endforeach;?>
				</select>
			</span>
			<input type="submit" value="Go &rsaquo;"/>
			<?=form_close()?>
		<?php endif;?>
	</div>
<?php endif;?>