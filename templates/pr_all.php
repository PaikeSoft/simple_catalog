<div class="wrap">	<h1>		<?php echo __( 'Products', 'products' ); ?>		<a class="page-title-action" href="admin.php?page=sproducts&action=add"><?php echo __( 'Add New', 'add new' ); ?></a>	</h1>	<table class="wp-list-table widefat">		<thead>			<tr> <td width="30"></td> <td width="30"><b>ID</b></td> <td><b><?php echo __( 'Name', 'name' ); ?></b></td> <td width="250"><b><?php echo __( 'Category', 'category' ); ?></b></td> <td width="120"><b><?php echo __( 'Price', 'price' ); ?></b></td> <td class="wcb_edcol"><b><?php echo __( 'Delete', 'delete' ); ?></b></td></tr>		</thead>		<tbody>		<?php		$i = 1;		foreach ($arr as $key => $value) {		?>		<tr>			<td><?php echo $i; ?></td>			<td><?php echo $value -> id; ?></td>			<td><a href="admin.php?page=sproducts&action=edit&id=<?php echo $value -> id; ?>"><?php echo stripslashes($value->name); ?></a></td>			<td><?php echo product_data::get_cat_name($value->cat_id); ?></td>			<td><?php echo $value->price ?></td>			<td class="wcb_edcol">				<a href="admin.php?page=sproducts&action=delete&id=<?php echo $value->id; ?>" onclick="return confirm('<?php echo __( 'Delete product?', 'delete product?' ); ?>')">&#10005;</a>			</td>		</tr>		<?php		$i++;		}		?>		</tbody>	</table></div>