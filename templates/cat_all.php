<div class="wrap">	<h1>		<?php echo __( 'Categories', 'categories' ); ?> 		<a class="page-title-action" href="admin.php?page=scategories&action=add"><?php echo __( 'Add New', 'add new' ); ?></a>	</h1>	<table class="wp-list-table widefat">		<thead>		<tr> <td width="30"></td> <td width="30"><b>ID</b></td> <td><b><?php echo __( 'Name', 'name' ); ?></b></td> <td><b><?php echo __( 'Parent', 'parent' ); ?></b></td> <td><b><?php echo __( 'Delete', 'delete' ); ?></b></td></tr>		</thead>		<tbody>		<?php		if ( sizeof($arr) > 0 ) {			$i = 1;			foreach ($arr as $key => $value) {			?>
				<tr>					<td><?php echo $i; ?></td>					<td><?php echo $value->id; ?></td>
					<td><a href="admin.php?page=scategories&action=edit&id=<?php echo $value->id; ?>"><?php echo $value->name; ?></a></td>					<td width="250"><?php category_data::get_parent_name($value->parent) ?></td>
					<td class="wcb_edcol">						<a href="admin.php?page=scategories&action=delete&id=<?php echo $value->id; ?>" onclick="return confirm('<?php echo __( 'Delete category?', 'delete category?' ); ?>')">&#10005;</a>					</td>
				</tr>			<?php				category_data::show_list($value->parent_arr, $i);				$i++;			} 		} 		?>		</tbody>	</table></div>