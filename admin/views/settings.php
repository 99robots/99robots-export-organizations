<div class="nnr-wrap">

	<?php require_once('header.php'); ?>

	<div class="nnr-container">

		<h1 id="nnr-heading"><?php _e('Export Organizations', self::$text_domain); ?></h1>

		<form class="form-horizontal" method="get">

			<div class="form-group">
				<label for="<?php echo self::$prefix_dash; ?>organization-category" class="col-sm-3 control-label"><?php _e('Organization Category', self::$text_domain); ?></label>
				<div class="col-sm-9">
					<select id="<?php echo self::$prefix_dash; ?>organization-category" name="<?php echo self::$prefix_dash; ?>organization-category">
						<?php foreach ( $organization_categories as $organization_category ) { ?>
						<option value="<?php echo $organization_category->term_id; ?>"><?php echo $organization_category->name; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="<?php echo self::$prefix_dash; ?>organization-contacts" class="col-sm-3 control-label"><?php _e('Contacts', self::$text_domain); ?></label>
				<div class="col-sm-9">
					<select id="<?php echo self::$prefix_dash; ?>organization-contacts" name="<?php echo self::$prefix_dash; ?>organization-contacts">
						<option value="all_contacts"><?php _e('All Contacts', self::$text_domain); ?></option>
						<option value="first_contact"><?php _e('First Contact', self::$text_domain); ?></option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<input type="submit" class="btn btn-info" value="<?php _e('Export', self::$text_domain); ?>" />
				</div>
			</div>

			<input type="hidden" name="page" value="<?php echo self::$settings_page; ?>" />
			<input type="hidden" name="file" value="true" />

		</form>

	</div>

	<?php require_once('footer.php'); ?>

</div>