<?php

class javo_custom_field{

	public function __construct()
	{
		$this->javo_fields =  Array(
			__('Group', 'javo_fr')			=> 'group'
			, __('Text Field', 'javo_fr')	=> 'text'
			, __('Textarea', 'javo_fr')		=> 'textarea'
			, __('Select Box', 'javo_fr')	=> 'select'
			, __('Radio', 'javo_fr')		=> 'radio'
			, __('Checkbox', 'javo_fr')		=> 'checkbox'
		);

		add_filter('javo_custom_field', Array('javo_custom_field', 'insert_field'), 10, 5);
		add_action('save_post', Array(__class__, 'javo_custom_in_post_save_callback'));
		add_action('wp_ajax_get_custom_field_form', Array($this, 'wp_ajax_get_custom_field_form_callback'));
		add_action('wp_ajax_nopriv_get_custom_field_form', Array($this, 'wp_ajax_get_custom_field_form_callback'));
	}

	public function wp_ajax_get_custom_field_form_callback(){
		$javo_get_custom_filed_id = 'id'.md5( strtotime( date( 'YmdHis' ) ).rand(10,1000000) );
		ob_start();?>
		<div class="postbox-container" style='width:100%;'>
			<div class="meta-box-sortables">
				<div class="postbox">
					<h3>
						<label style="font:0.7em/0.7em 'Trebuchet MS', Helvetica, sans-serif; color:#868686;"><?php _e('Field Attributes', 'javo_fr');?></label>
					</h3>
					<div class="inside">
						<dl>
							<dt><?php _e('Input Label', 'javo_fr');?></dt>
							<dd>
								<input type="hidden" name="javo_ts[custom_field][<?php echo $javo_get_custom_filed_id;?>][name]" value="<?php echo $javo_get_custom_filed_id;?>" data-order>
								<input type="hidden" name="javo_ts[custom_field][<?php echo $javo_get_custom_filed_id;?>][order]">
								<input type="text" name="javo_ts[custom_field][<?php echo $javo_get_custom_filed_id;?>][label]" value="">
							</dd>
						</dl>
						<dl>
							<dt><?php _e('Element Type', 'javo_fr');?></dt>
							<dd>
								<select name="javo_ts[custom_field][<?php echo $javo_get_custom_filed_id;?>][type]">
									<?php
									echo $this->insert_option( $this->javo_fields );?>
								</select>
							</dd>
						</dl>
						<dl class="group-hidden">
							<dt><?php _e('Values', 'javo_fr');?></dt>
							<dd>
								<div class="description"><small><?php _e('You must use "," as a separator for dropdown, radio, check boxes', 'javo_fr');?></small></div>
								<input name="javo_ts[custom_field][<?php echo $javo_get_custom_filed_id;?>][value]" value="">

							</dd>
						</dl>
						<dl class="group-hidden">
							<dt><?php _e('CSS Class Name', 'javo_fr');?></dt>
							<dd><input name="javo_ts[custom_field][<?php echo $javo_get_custom_filed_id;?>][css]" value=""></dd>
						</dl>
						<dl>
							<dt><?php _e('Action', 'javo_fr');?></dt>
							<dd>
								<a class="button button-warning javo-remove-custom-field"><?php _e('Remove', 'javo_fr');?></a>
							</dd>
						</dl>
					</div>
				</div><!-- PostBox End -->
			</div><!-- PostBox Sortable End -->
		</div><!-- PostBox Container End -->


		<?php
		$javo_get_this_content = ob_get_clean();
		echo json_encode(Array(
			'output'=> $javo_get_this_content
		));
		exit;
	}

	static function javo_custom_in_post_save_callback($post_id){
		$javo_query = new javo_ARRAY( $_POST );

		if( false !== (boolean)( $fields = $javo_query->get('javo_custom_field', false ) ) )
		{
			if( is_Array( $fields ) )
			{
				foreach( $fields as $key => $fields )
				{
					$javo_value = new javo_ARRAY( $fields );
					update_post_meta( $post_id, $key, $javo_value->get( 'value' ) );
				}
			}

		}


	}

	public function insert_option( $options, $default=NULL){
		$javo_this_output ="";
		foreach( (Array) $options as $key=> $value){
			$javo_this_output .= sprintf('<option value="%s"%s>%s</option>', $value, ($value == $default ? ' selected': ''), $key);
		}
		return $javo_this_output;
	}


	static function gets()
	{
		global
			$post
			, $javo_tso;

		$javo_return				= Array();
		$javo_get_custom_field		= $javo_tso->get('custom_field', null);

		if( ! empty( $javo_get_custom_field ) )
		{
			// Output : Label, Value
			foreach( $javo_get_custom_field as $key => $field )
			{
				$is_group				= !empty( $field['type'] ) && $field['type'] == "group";

				$javo_return[ $field['name'] ]	= Array(
					'label'				=> $field['label']
					, 'value'			=> $is_group ? "&nbsp;" : get_post_meta( $post->ID, $field['name'], true )
					, 'type'			=> $field['type']
				);
			}
		}

		if( empty( $javo_return ) ){ return; };
		return $javo_return;
	}

	static function insert_field($label, $type, $attributes = Array(), $values = NULL, $default_value=NULL)
	{

		$javo_this_output	= Array('attribute' => '', 'values' => '');
		$javo_field_key		= "";

		$attributes['class'] .= ' form-control';
		foreach( (Array)$attributes as $key => $value){
			if($key == 'name'){
				$javo_this_output['attribute'] .= ' '.$key.'="javo_custom_field['.$value.'][value]"';
				$javo_field_key = $value;
			}else{
				$javo_this_output['attribute'] .= ' '.$key.'="'.$value.'"';
			};
		}
		$javo_this_output['attribute'] .= ">";

		switch( $type ){
			case 'textarea':
				$javo_this_output['before']		= '<textarea';
				$javo_this_output['after']		= '</textarea>';
				$javo_this_output['values']		= $default_value != NULL ? $default_value : $values;
			break;
			case 'select':
				$javo_this_output['before']		= '<select';
				if( !empty( $values ) ){
					$javo_this_values = explode(',', $values);
					foreach($javo_this_values as $value)
					{
						$javo_this_output['values'] .= sprintf('<option value="%s"%s>%s</option>'
							, trim( $value )
							, selected( trim( $value ) == trim( $default_value ), true, false)
							, trim( $value )
						);
					};
				};
				$javo_this_output['after']		= '</select>';
			break;
			case 'radio':
			case 'checkbox':

				$javo_this_output['before']		= '<div ';
				$javo_this_output['attribute']	= 'class="form-control" style="float:none;">';
				$javo_this_output['after']		= '</div>';

				$javo_this_field_array = $type == 'checkbox' ? '[]' : '';

				$javo_this_values = explode( ',', $values );
				foreach( $javo_this_values as $value )
				{
					$javo_this_output['values'] .= sprintf("<label><input type='{$type}' name='javo_custom_field[{$attributes['name']}][value]{$javo_this_field_array}' value='%s'%s>%s</label> &nbsp;"
						, trim( $value )
						, checked( !empty( $default_value ) && in_Array( trim( $value ), (Array)$default_value ), true, false )
						, trim( $value )
					);
				}
			break;
			case 'text':
				$javo_this_output['before']		= '<input type="text" value="'. ( $default_value != NULL ? $default_value : $values ).'"';
				$javo_this_output['after']		= '';
			break;
		};
		ob_start();

		if( $type != "group" ){
			?>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><?php echo $label;?></span>
					<?php echo $javo_this_output['before'].$javo_this_output['attribute'].$javo_this_output['values'].$javo_this_output['after'];?>
				</div>
				<input type="hidden" name="javo_custom_field[<?php echo $attributes['name'];?>][label]" value="<?php echo $label;?>">
			</div>
			<?php
		}else{
			?>
			<div class="form-group page-header">
				<?php echo $label;?>
				<input type="hidden" name="javo_custom_field[<?php echo $javo_field_key;?>][value]" value="|">
			</div>
			<?php
		}
		return ob_get_clean();
	}

	public function form(){
		global
			$javo_tso
			, $edit;

		$temp = $edit;

		if( empty( $edit ) )
		{
			$edit		= new stdClass();
			$edit->ID	= 0;

		}

		$javo_get_custom_field = $javo_tso->get('custom_field', null);
		$javo_get_custom_variables = Array();


		ob_start();?>
		<?php
		if( !empty( $javo_get_custom_field ) ){

			foreach( $javo_get_custom_field as $key => $field){
				if( !empty( $javo_get_custom_variables[$field['name']] )){
					$javo_this_form_data = new javo_ARRAY( $javo_get_custom_variables[$field['name']] );
				};
				echo apply_filters(
					'javo_custom_field'
					, $field['label']
					, $field['type']
					, Array(
						'name'			=> $field['name']
						, 'class'		=> $field['css']
					), $field['value']
					, get_post_meta( $edit->ID, "{$field['name']}", true )
				);
			};
		};

		// Repaire Variable
		$edit = $temp;
		return ob_get_clean();
	}
	public function admin()
	{
		global $javo_tso;
		$javo_get_custom_field = $javo_tso->get('custom_field', null);
		ob_start(); ?>
		<div class="javo_custom_field_forms">
			<?php
			if( !empty($javo_get_custom_field) ){
				foreach($javo_get_custom_field as $key => $field){
					$javo_field_string = new javo_Array($field);
					?>
					<div class="postbox-container" style='width:100%;'>
						<div class="meta-box-sortables">
							<div class="postbox">
								<h3>
									<label style="font:0.7em/0.7em 'Trebuchet MS', Helvetica, sans-serif; color:#868686;"><?php _e('Field Attributes', 'javo_fr');?></label>
								</h3>
								<div class="inside">
									<dl>
										<dt><?php _e('Input Slug', 'javo_fr');?></dt>
										<dd><input type="text" name="javo_ts[custom_field][<?php echo $key;?>][name]" value="<?php echo esc_attr( $field['name'] );?>" style="width:500px;"></dd>
									</dl>
									<dl>
										<dt><?php _e('Input Label', 'javo_fr');?></dt>
										<dd>
											<input type="hidden" name="javo_ts[custom_field][<?php echo $key;?>][name]" value="<?php echo $field['name'];?>">
											<input type="hidden" name="javo_ts[custom_field][<?php echo $key;?>][order]" data-order>
											<input type="text" name="javo_ts[custom_field][<?php echo $key;?>][label]" value="<?php echo $javo_field_string->get('label');?>">
										</dd>
									</dl>
									<dl>
										<dt><?php _e('Element Type', 'javo_fr');?></dt>
										<dd>
											<select name="javo_ts[custom_field][<?php echo $key;?>][type]">
												<?php
												echo $this->insert_option( $this->javo_fields , $field['type']);?>
											</select>
										</dd>
									</dl>
									<dl class="group-hidden">
										<dt><?php _e('Values', 'javo_fr');?></dt>
										<dd>
											<div class="description"><small><?php _e('You must use "," as a separator for dropdown, raido, check boxes', 'javo_fr');?></small></div>
											<input name="javo_ts[custom_field][<?php echo $key;?>][value]" value="<?php echo $javo_field_string->get('value');?>">
										</dd>
									</dl>
									<dl class="group-hidden">
										<dt><?php _e('CSS Class Name', 'javo_fr');?></dt>
										<dd>
											<input name="javo_ts[custom_field][<?php echo $key;?>][css]" value="<?php echo $javo_field_string->get('css');?>">
										</dd>
									</dl>
									<dl>
										<dt><?php _e('Action', 'javo_fr');?></dt>
										<dd>
											<a class="button button-cancel javo-remove-custom-field"><?php _e('Remove', 'javo_fr');?></a>
										</dd>
									</dl>
								</div>
							</div><!-- PostBox End -->
						</div><!-- PostBox Sortable End -->
					</div><!-- PostBox Container End -->
					<?php
				};
			};?>
		</div>
		<a class="button button-primary javo-add-custom-field"><?php _e('Add New Field', 'javo_fr');?></a>
		<script type="text/javascript">
		(function($){
			"use strict";

			var el_type = "select[name^='javo_ts[custom_field]']";

			; $(document)
				.on('click', '.javo-add-custom-field', function(){
					var $this = $(this);
					var javo_get_custom_field_form_ajax = {
						url:'<?php echo admin_url("admin-ajax.php");?>'
						, type: 'post'
						, dataType: 'json'
						, data:{ action:'get_custom_field_form' }
						, complete: function(){
							$( el_type ).trigger( 'change' );

						}
						, success:function(d){
							$('body').find('.javo_custom_field_forms').append( d.output );
							$this.removeClass('disabled');
						}
					};
					if( !$this.hasClass('disabled') ){
						$this.addClass('disabled');
						$.ajax( javo_get_custom_field_form_ajax );
					};
				})

				.on('click', '.javo-remove-custom-field', function(){
					$(this).closest('.postbox-container').remove();
				})

				.on( 'change', el_type, function(){
					var parent = $(this).closest( 'div.inside' ).find( 'dl.group-hidden' );
					parent.removeClass('hidden');
					if( $(this).val() == "group" && parent.hasClass('group-hidden') ){
						parent.addClass("hidden");
					}
				})

			; $( el_type ).trigger( 'change' );




		})(jQuery);


		</script>

		<?php
		return ob_get_clean();
	}

};
global $javo_custom_field;
$javo_custom_field = new javo_custom_field();