<?php
/*******************************************
@View Name						:		contactView
@Author							:		Matthew
@Date							:		12 June 2013
@Purpose						:		This page returns map information about FMLA
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#	RF1			Matthew			July 19,2013 	normal			added close link

if($content != false)
	echo '<div class="info-tip">'.$content."</div><br><div align='right'><a href='javascript:;' onclick='closeQtip()'>Close</a></div>";
else
	echo "No data fount.";
?>