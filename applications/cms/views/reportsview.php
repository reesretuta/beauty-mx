<table style="width:100%" class="fullheight">
	<tr>
		<td style="width: 140px;" class="menu">
			<? foreach($groups as $groupr):?>
				<a class="a_table <?=$groupr->group_name==$group->group_name?'a_table_active':''?>" href="<?=base_url()?>menu/<?=$groupr->id?>"><span class="a_table_thumb"><img src="<?=$groupr->thumbnail?>"/></span>&nbsp;<?=$groupr->group_name?></a>
			<? endforeach;?>
		</td>
		<td style="width: 120px;" class="menu">
			<? if(strtolower($group->group_name)=='reports'):?>
				<? foreach($reports as $reportr):?>
					<a class="a_table_smaller <?=$reportr->id==$dataid?'a_table_smaller_active':''?>" href="<?=base_url()?>reports/<?=$reportr->id?>"><?=$reportr->title?></a>
				<? endforeach;?>
			<? else:?>
				<? foreach ($tables as $table):?>
					<a class="a_table_smaller" href="<?=base_url()?>content/<?=$table->table_name?>"><?=humanizer(($table->table_name))?></a>
				<? endforeach;?>
			<? endif;?>
		</td>
		<td style="border:0;">
		<?	$graph_data=array();?>
		<? 	$this->load->view('includes/breadcrumbs');?>
			<? $i=0;?>
			<? foreach ($lines as $line):?>
				<? $i++;?>
				<? foreach ($line as $lvar):?>
					<? $ii=0;?>
					<? foreach ($lvar as $innervar):?>
						<? if($ii<2):?>
							<? if($ii%2===0):?>
								<? if($comparing):?>
									<? if($viewrange=='month'):?>
										<? $gd="[".(strtotime($innervar)*1000).',';?>
									<? elseif($viewrange=='day'):?>
										<? $rr=$innervar; //adjust_to_current?>
										<? $gd="[".(strtotime($rr)*1000).',';?>
									<? endif;?>
								<? else:?>
									<? $gd="[".(strtotime($innervar)*1000).',';?>
								<? endif;?>
							<? else:?>
								<? $gd=$innervar==''?0:$innervar;?>
								<? $gd.=']'?>
							<? endif;?>
							<?$graph_data[$i][]=$gd;$ii++;?>
						<? endif;?>
					<? endforeach;?>
				<? endforeach;?>
			<? endforeach;?>
			
			<? foreach($graph_data as $i=>$graph_data_child):?>
				<? $graph_data[$i]=implode('', $graph_data_child)?>
				<? $formatted_data=explode(']', $graph_data[$i])?>
				<? $graph_data[$i]=rtrim(implode('],', $formatted_data), ',');?>
			<? endforeach;?>
			
			<? if($comparing):?>
				<? $j=0;$replacer=array();?>
				<? foreach ($graph_data as $i=>$graph_data_child):?>
					<? $j++;?>
					<? if($j===1):?>
						<? $graph_data_child=explode(']',$graph_data_child);?>
						<? foreach ($graph_data_child as $gk=>$gv):?>
							<? preg_match('/[0-9]+[\.0-9]+/', $gv, $q);?>
							<? $replacer[]=($q?$q[0]:time()).','?>
						<? endforeach;?>
					<? endif;?>
					<? if($j>1):?>
						<? $graph_data_child=explode(']',$graph_data_child);?>
						<? $k=0;?>
						<? foreach($graph_data_child as $gk=>$gv):?>
							<? $graph_data_child[$gk]=preg_replace('/[0-9]+[\.0-9]+,/', $replacer[$k], $gv);?>
							<? $k++;?>
						<? endforeach;?>
						<? $graph_data_child=implode(']', $graph_data_child);?>
						<? $graph_data[$i]=$graph_data_child;?>
					<? endif;?>
				<? endforeach;?>
			<? endif;?>
			
			
			<div id="linechart" style="width: auto; height:300px;"></div>
			
		<form method="post">
			<table class="detailed_search_bar">
				<tr>
					<td>Show Me:</td>
					<td>
						<select name="range">
							<option <?=(isset($_POST) && isset($_POST['range']) && $_POST['range']=='month')?'':' selected="selected" '?> value="day">Days</option>
							<option <?=(isset($_POST) && isset($_POST['range']) && $_POST['range']=='month')?' selected="selected" ':''?> value="month">Months</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Between:</td>
					<td>
						<input type="text" name="min_date" class="date1" value="<?=(isset($_POST) && isset($_POST['min_date']))?$_POST['min_date']:date('m/d/Y', strtotime('-1 month'))?>"/> 
						&nbsp;and&nbsp;
						<input type="text" name="max_date" value="<?=(isset($_POST) && isset($_POST['max_date']))?$_POST['max_date']:date('m/d/Y', time())?>" class="date3"/>
					</td>
				</tr>
				<tr>
					<td>Compare With:</td>
					<td>
						<input type="text" name="min_compare_date" value="<?=(isset($_POST) && isset($_POST['min_compare_date']))?$_POST['min_compare_date']:''?>" class="date1"/> 
						&nbsp;and&nbsp;
						<input type="text" name="max_compare_date" value="<?=(isset($_POST) && isset($_POST['max_compare_date']))?$_POST['max_compare_date']:''?>" class="date3"/>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="Filter"/></td>
				</tr>			
			</table>
		</form>			
			
			<div style="float: right; font-weight: bold;"><a target="_blank" href="<?=base_url()?>process/export/pdf/<?=urlencode($report->y_label.' [Report]')?>">Export as PDF</a> 
			| 
			<a href="#excelsior">Export as Excel</a></div>
			<br clear="all"/>
			<? $s=0;?>
			<? $pdf=array();?>
			<? foreach($lines as $detailed_report):?>
				<h4 style="margin-bottom: 0; <? if($s>0):?>margin-top:40px;<? endif;?>"><?=$labels[$s]?></h1>
				<table width="100%;">
					<? foreach($detailed_report as $th):?>
						<? $th=(array)$th?>
						<tr><? foreach($th as $header=>$name):?><th><?=$head=($header=='id'?trim($labels[$s]):humanizer($header))?></th><? endforeach;?></tr>
						<? break;?>
					<? endforeach;?>
					<? foreach($detailed_report as $th):?>
						<tr>
							<? $n_array=array();?>
							<? foreach($th as $n=>$c):?>
								<td><?=$c?></td>
								<? $n_array[strtolower($n)]=$c;?>
							<? endforeach;?>
							<? $pdf[]=$n_array;?>
						</tr>
					<? endforeach;?>
				</table>
				<? //should probably write this to a temp file everytime and call it, should an export be asked of;?>
				<? $s++;?>				
			<? endforeach;?>
			<? unset($s)?>
			
			<? if(!$lines):?>
				<p>There are no detailed reports to show at this time</p>
			<? endif;?>
		</td>
	</tr>
</table>

<? $tempfile='temp.txt';?>
<? $f=fopen($tempfile, 'w');?>
<? if(is_writable($tempfile)):?>
	<? fwrite($f, serialize($pdf));?>
<? endif;?>

<!-- plots -->
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/media/js/excanvas.min.js"></script><![endif]--> 
<script language="javascript" type="text/javascript" src="/media/js/jquery.flot.min.js"></script>


<script type="text/javascript">
$(function (){
	<? $lines=array();?>
	<? $i=0;?>
	<? foreach($graph_data as $ii=>$gdc):?>
		<? $i++;?>
	 		var line<?=$i;?>=[<?=$gdc?>];
		<? $lines[]="line".$i?>
	<? endforeach;?>
	var options={
			colors: ["#afd8f8","#4da74d","#edc240","#cb4b4b","#9440ed"],
			xaxis: { 
				mode: "time", 
				inverseTransform:function (v) {return v;},
				//minTickSize: [1, "month"],
				<? if($viewrange=='month'):?>
				timeformat:'%b %y'
				<? elseif($viewrange=='day' && $comparing):?>
				timeformat:'%b'
				<? elseif($viewrange=='day'):?>
				timeformat:'%d %b %y'
				<? endif;?>
			}, 
			lines:{
				show:true,
				fill:true,
				lineWidth:3
			},
			points:{
				show:true,
				lineWidth:2
			},
			grid:{
				color:"rgb (230, 230, 230)",
				tickColor: "rgb(230, 230, 230)",
				hoverable: true,
				borderWidth:1,
				clickable:true
			},
			legend:{
				position:'ne',
				show:true
			} 
		};
	var datar=[
	      <? $s=0;?>
	      <? foreach($lines as $line):?>
	      <? $s++;?>
	     {label:'<?=$labels[$s-1]?>', data:<?=$line?>}<?=$s!=sizeof($lines)?',':''?>
	     <? endforeach;?>
	     ];
	$.plot($("#linechart"), datar , options);

	function showTooltip(x, y, contents){
		$('<div id="tooltip">' + contents + '</div>').css({
			position:'absolute',
			display:'none',
			top: y + 5,
			left: x + 5,
			border: '1px solid #fee',
			padding:'2px',
			'background-color':'#fff',
			opacity:1,
			cursor:'pointer'
			}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;

	$("#linechart").bind("plothover", function (event, pos, item){
		$(this).css('cursor', 'pointer');
		if(item){
			if(previousPoint != item.dataIndex){
				previousPoint = item.dataIndex;
				$("#tooltip").remove();
				var x = new Date((item.datapoint[0].toFixed(0) * 0.999999)+86400000);
				var y = item.datapoint[1].toFixed(2);
				
				showTooltip(item.pageX, item.pageY,
						<? if($viewrange=='month'):?>
						item.series.label + " of " + (x.getMonth()+1) + " = " + y);
						<? else:?>
						(x.getMonth()+1) + "/" + (x.getDate()) + "/" + (x.getFullYear()) + " = " + y);
						<? endif;?>
			}
		}
		else{
			$("#tooltip").remove();
			previousPoint = null;
		}
		
	});
	
	
});


</script>