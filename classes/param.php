<?php 
class param_data { 

	/* 
	 * Show childrens param for select in parameter page 
	*/
	public function get_arr_param ($arr_data, $count, $id) 
	{
		$str = '';
		for ($i = 0; $i <= $count; $i++ ){
			$str .= '&nbsp;&nbsp;';	
		}
	
		foreach ($arr_data as $k => $v) {
			$selected = '';
			if ( $v['id'] != $_REQUEST['id'] ) {
				echo '<option value="'.$v['id'].'">'.$str.$v['name'].'</option>';
				if ( is_array($v['child']) ) {
					$count++;
					self::get_arr_param($v['child'], $count, $v['id']);
				}
			}
			else {
				break;
			} 
		}
	} 


	/* 
	 * create parameters array form DB `types`
	*/
	function get_param_type($id = '')
	{
		global $wpdb;
	
		if ($id == '') {
			$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_types` ORDER BY `id`');
		}
		else {
			$arr = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'scat_types` WHERE `id`="'.$id.'"');
		}
	
		return $arr;
	}


	/* 
	 * create parameters array form DB `types`
	*/
	function get_param ($id, $arr_data, $params, $pr_id) 
	{
		$html = '';
		foreach ($arr_data as $k => $v) {
			if ( isset( $params[$id][$v['id']] ) ) {
				if ( $params[$id][$v['id']] != 0 ) {
					$value = $params[$id][$v['id']];
	
					if ( $value > 0 ) {
						$html .= '<tr>';
						$html .= '<td class="td-param-value">';
						$html .= '<input class="i_'.$pr_id.'_'.$id.'" name="i_'.$pr_id.'_'.$id.'" id="i_'.$pr_id.'_'.$v['id'].'" type="radio" value="'.$value.'">';
						$html .= '<label for="i_'.$pr_id.'_'.$v['id'].'">'.$v['name'].'</label>';
						$html .= '</td>';
						$html .= '<td class="td-param-value">'.$value.' грн</td>';
						$html .= '</tr>';
					}
					else {
						$html .= '<tr class="tr-disable i_'.$pr_id.'_'.$id.'">';
						$html .= '<td class="td-param-value" colspan="2">';
						$html .= '<input class="i_'.$pr_id.'_'.$id.'" name="i_'.$pr_id.'_'.$id.'" id="i_'.$pr_id.'_'.$v['id'].'" type="checkbox" value="0">';
						$html .= '<label for="i_'.$pr_id.'_'.$v['id'].'">'.$v['name'].'</label>';
						$html .= '</td>';
						$html .= '</tr>';
					}
				}
			}			
		}
		return $html;
	}
}

?> 