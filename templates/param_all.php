<div class="wrap">
	<h1>
		<?php echo __( 'Parameters', 'parameters' ); ?> 
		<a class="page-title-action" href="admin.php?page=sparameters&action=add"><?php echo __( 'Add New', 'add new' ); ?></a>
	</h1>

<table class="wp-list-table widefat">
<thead>
<tr> <td width="30"></td> <td><b><?php echo __( 'Name', 'name' ); ?></b></td> <td></td></tr>
</thead>
<?php
if ( sizeof($arr) > 0 ) {
	$i = 1;
	foreach ($arr as $key => $value) {
		?>
			<tr>
				<td><?php echo $i; ?></td>
				<td>
					<?php 
					if ($value['hide'] == '1') $class = 'hide-el';
					else $class = ''; 
					?>
					<a href="admin.php?page=sparameters&action=edit&id=<?php echo $value['id']; ?>" class="<?php echo $class; ?>"><?php echo $value['name']; ?></a>
				</td>
				<td class="wcb_edcol">
					<a href="admin.php?page=sparameters&action=del&id=<?php echo $value['id']; ?>" onclick="return confirm('<?php echo __( 'Delete parameter', 'delete parameter' ); ?>')">&#10005;</a>
					<?php if ($i > 1) { ?>
					<a href="admin.php?page=sparameters&action=up&id=<?php echo $value['id']; ?>">&#9650;</a>
					<?php } ?>
					<?php if ($i < sizeof($arr)) { ?>
					<a href="admin.php?page=sparameters&action=down&id=<?php echo $value['id']; ?>">&#9660;</a>
					<?php } ?>
				</td>
			</tr>
		<?php
			get_options($value['child'] ,$i);
		$i++;
	} 
} 
?>
</table>
</div>

<?php
function get_options($arr, $s){
	$i = 1;
	if ( is_array($arr) ) {
		foreach ($arr as $k => $v) {
		?>
			<tr>
				<td><?php echo $s.'.'.$i ?></td>
				<td><a href="admin.php?page=sparameters&action=edit&id=<?php echo $v['id']; ?>"><?php echo $v['name']; ?></a></td>
				<td class="wcb_edcol">
					<a href="admin.php?page=sparameters&action=del&id=<?php echo $v['id']; ?>" onclick="return confirm('<?php echo __( 'Delete parameter', 'delete parameter' ); ?>')">&#10005;</a>
				</td>
			</tr>
			<?php
			if ( is_array($v['child']) ) {
				get_options($v['child'],$s.'.'.$i);
			}
			$i++;
		}
	}
}
?>