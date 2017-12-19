<div class="scat__order-wrap">
	<div class="scat__order-shadow"></div>
	<div class="scat__order-form">
		<div class="scat__order-content">
			<div class="scat__order-close"></div>

			<div class="scat__order-prname" style="display: none;"></div>

			<div class="scat__order-data">
				<div class="scat__order-input">
					<label><?php echo __('You name', 'you name'); ?></label>
					<input name="scat__order-name scat__input-name" class="scat__order-name" />
				</div>
				<div class="scat__order-input scat__input-phone">
					<label><?php echo __('Phone', 'phone'); ?></label>
					<input name="scat__order-phone" class="scat__order-phone" />
					<div class="error-txt"><?php echo __('Enter your phone number', 'enter your phone number'); ?></div>
				</div>
				<div class="scat__order-input scat__input-email">
					<label><?php echo __('Email', 'email'); ?></label>
					<input name="scat__order-email" class="scat__order-email" />
				</div>
				<button class="scat__order-button"><?php echo __('Buy', 'buy'); ?></button>
			</div>
			<div class="scat__order-complete">
				<div><?php echo __('Order complete', 'order complete'); ?></div>
				<?php echo __('Our managers will contact you', 'our managers will contact you'); ?>
			</div>
		</div>
	</div>
</div>