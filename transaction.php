<?php
   include('session.php');
   include('header.html');
   include('nav.php');
   include('./locale/'.$_SESSION['lang'].'.php');
   
   if (isset($_GET['mov_id'])){
    $mov_delete = mysqli_real_escape_string($db, $_GET['mov_id']);
	$sql_del_mov = "delete from movement where mov_id=$mov_delete";
    $result_del_mov = mysqli_query($db, $sql_del_mov);
   }
   
   $sql_mov = "select * from mov";
   
   if(isset($sql_mov)){
    $result_mov = mysqli_query($db,$sql_mov);
   }
?>

<script>
$(document).ready(function() {
    $('#tra_table').DataTable({
	"order": [ 2, 'desc' ],
	"language": {
            "lengthMenu": "<?php echo $lang['26'];?>",
            "zeroRecords": "<?php echo $lang['27'];?>",
            "info": "<?php echo $lang['28'];?>",
            "infoEmpty": "<?php echo $lang['29'];?>",
	        "infoFiltered": "<?php echo $lang['30'];?>",
			"lengthMenu":     "<?php echo $lang['31'];?>",
			"loadingRecords": "<?php echo $lang['32'];?>",
			"processing":     "<?php echo $lang['33'];?>",
			"search":         "<?php echo $lang['34'];?>",
			"paginate": {
				"first":      "<?php echo $lang['35'];?>",
				"last":       "<?php echo $lang['36'];?>",
				"next":       "<?php echo $lang['37'];?>",
				"previous":   "<?php echo $lang['38'];?>"
			},
			"aria": {
				"sortAscending":  ": <?php echo $lang['39'];?>",
				"sortDescending": ": <?php echo $lang['39'];?>"}}
	});
} );
</script>

<div class="col-md-12">
<div class="container">
<div class="table-responsive">
<table id="tra_table" class="table table-striped">
    <thead>
        <tr>
            <th><?php echo $lang['14'];?></th>
			<th><?php echo $lang['15'];?></th>
			<th><?php echo $lang['24'];?></th>
			<th><?php echo $lang['17'];?></th>
			<th><?php echo $lang['25'];?></th>
			<th><?php echo $lang['41'];?></th>
        </tr>
    </thead>
    <tbody>
	 <?php while($row_mov = mysqli_fetch_array($result_mov,MYSQLI_ASSOC)){
      echo '<tr>
	   <td style="color:#'.$row_mov['color'].';">'.$row_mov['cat_name'].'</td>
	   <td data-order="'.str_replace('.', ',',$row_mov['val']).'">'.str_replace('.', ',',$row_mov['val']).' &euro;</td>
	   <td data-order="'.date('Ymd',strtotime($row_mov['dat_mov'])).'">'.date('d/m/Y',strtotime($row_mov['dat_mov'])).'</td>
	   <td>'.$row_mov['usr_mov'].'</td>
	   <td>'.$row_mov['note'].'</td>
	   <td><a href="?mov_id='.$row_mov['mov_id'].'">Cancella</a></td>
	  </tr>';
     }?>
	</tbody>
</table>
 </div>
</div>
</div>

</html>
</body>