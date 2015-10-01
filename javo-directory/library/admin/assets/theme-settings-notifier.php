<?php
// library / define.php
global $javo_notification_deault_content; ?>

<div class="javo_ts_tab javo-opts-group-tab hidden" tar="notifier">
	<h2><?php _e('Notification', 'javo_fr'); ?> </h2>
	<table class="form-table">
	<tr><td bgcolor="#eee" colspan="2">
		<div class="update-nag">
			<h2><?php _e('Template code GuideLine', 'javo_fr');?></h2>
			<ul>
				<li><?php _e('{permalink} :  Item page url (for events, reviews will link to the item (parent) page)', 'javo_fr');?></li>
				<li><?php _e('{home_url} : Site url', 'javo_fr');?></li>
				<li><?php _e('{author_name} : Author "display_name"', 'javo_fr');?></li>
				<li><?php _e('{post_title} : Post Title', 'javo_fr');?></li>
			</ul>
		</div>
	</td></tr><tr><th>
		<?php _e('New Item', 'javo_fr');?>
		<span class="description"></span>
	</th><td>
		<h4><?php _e('Active Notification For New Items', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label style="padding-right:30px;">
				<input type="radio" name="javo_ts[new_item_notifier]" value="" <?php checked( '' ==$javo_tso->get("new_item_notifier", ''));?>>
				<?php _e('Off', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[new_item_notifier]" value="on" <?php checked( 'on' ==$javo_tso->get("new_item_notifier", ''));?>>
				<?php _e('On', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('Template Of Notification Mail.', 'javo_fr');?></h4>
		<fieldset class="inner">
			<span class="description"><?php _e('(Please add your message : html code)', 'javo_fr');?></span>
			<textarea name="javo_ts[new_item_notifier_template]" rows="10" class="large-text"><?php echo stripslashes( $javo_tso->get('new_item_notifier_template', $javo_notification_deault_content ));?></textarea>
		</fieldset>
	</td></tr><tr><th>
		<?php _e('New Event', 'javo_fr');?>
		<span class="description"></span>
	</th><td>
		<h4><?php _e('Active Notification For New Events', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label style="padding-right:30px;">
				<input type="radio" name="javo_ts[new_event_notifier]" value="" <?php checked( '' ==$javo_tso->get("new_event_notifier", ''));?>>
				<?php _e('Off', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[new_event_notifier]" value="on" <?php checked( 'on' ==$javo_tso->get("new_event_notifier", ''));?>>
				<?php _e('On', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('Template Of Notification Mail.', 'javo_fr');?></h4>
		<fieldset class="inner">
			<span class="description"><?php _e('(Please add your message : html code)', 'javo_fr');?></span>
			<textarea name="javo_ts[new_event_notifier_template]" rows="10" class="large-text"><?php echo stripslashes( $javo_tso->get('new_event_notifier_template', $javo_notification_deault_content ));?></textarea>
		</fieldset>
	</td></tr><tr><th>
		<?php _e('New Review', 'javo_fr');?>
		<span class="description"></span>
	</th><td>
		<h4><?php _e('Active Notification For New Review', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label style="padding-right:30px;">
				<input type="radio" name="javo_ts[new_review_notifier]" value="" <?php checked( '' == $javo_tso->get("new_review_notifier", ''));?>>
				<?php _e('Off', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[new_review_notifier]" value="on" <?php checked( 'on' == $javo_tso->get("new_review_notifier", ''));?>>
				<?php _e('On', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('Template Of Notification Mail.', 'javo_fr');?></h4>
		<fieldset class="inner">
			<span class="description"><?php _e('(Please add your message : html code)', 'javo_fr');?></span>
			<textarea name="javo_ts[new_review_notifier_template]" rows="10" class="large-text"><?php echo stripslashes( $javo_tso->get('new_review_notifier_template', $javo_notification_deault_content ));?></textarea>
		</fieldset>
	</td></tr>
	</table>
</div>