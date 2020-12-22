<?php
$banner_1x = forminator_plugin_url() . 'assets/images/forminator-black-friday-modal.png';
$banner_2x = forminator_plugin_url() . 'assets/images/forminator-black-friday-modal@2x.png';
?>

<div
	id="forminator-black-friday"
	class="sui-dialog sui-dialog-onboard"
	aria-hidden="true"
>

	<div class="sui-dialog-overlay sui-fade-out" data-a11y-dialog-hide="forminator-black-friday" aria-hidden="true"></div>

	<div
		class="sui-dialog-content sui-fade-out"
		role="dialog"
	>

		<div class="sui-slider forminator-feature-modal" data-prop="forminator_black_modal_dismissed" data-nonce="<?php echo esc_attr( wp_create_nonce( 'forminator_dismiss_blackfriday' ) ); ?>">

			<ul role="document" class="sui-slider-content">

				<li class="sui-current sui-loaded" data-slide="1">

					<div class="sui-box">

						<div class="sui-box-banner" role="banner" aria-hidden="true">
							<img
								src="<?php echo esc_url( $banner_1x ); ?>"
								srcset="<?php echo esc_url( $banner_1x ); ?> 1x, <?php echo esc_url( $banner_2x ); ?> 2x"
								class="sui-image"
								alt="Forminator"
							/>
						</div>

						<div class="sui-box-header">
							<button data-a11y-dialog-hide="forminator-black-friday" style="z-index: 2" class="sui-dialog-close forminator-dismiss-new-feature" aria-label="<?php esc_html_e( 'Close this dialog window', Forminator::DOMAIN ); ?>"></button>
						</div>

						<div class="sui-box-body">

							<div id="fui-black-modal-content" class="sui-border-frame">

								<h3 id="hustle-dialog--black-friday-sale-title" class="fui-black-title"><?php esc_html_e( 'Forminator Pro 60% OFF', Forminator::DOMAIN ); ?></h3>

								<p id="hustle-dialog--black-friday-sale-desc" class="sui-description"><?php esc_html_e( 'For all your form needs.', Forminator::DOMAIN ); ?></p>

								<p class="sui-screen-reader-text" style="margin-bottom: 0;"><?php esc_html_e( 'Before $60 per year, now $24 per year. A total of 8 months free!', Forminator::DOMAIN ); ?></p>

								<div class="fui-black-price" aria-hidden="true">

									<p>
										<del>$60</del>
										<ins>$24</ins>
										<span>/<?php esc_html_e( 'year', Forminator::DOMAIN ); ?></span>
									</p>

									<p><?php esc_html_e( 'Total of 8 months free!', Forminator::DOMAIN ); ?></p>

								</div>

								<p><a href="https://premium.wpmudev.org/project/forminator-pro/?coupon=BF2020FORMINATOR&checkout=0&utm_source=forminator&utm_medium=plugin&utm_campaign=forminator_bf2020modal_cta" class="sui-button sui-button-purple" target="_blank"><?php esc_html_e( 'Get 60% Off Forminator Pro', Forminator::DOMAIN ); ?></a></p>

								<h4 class="fui-black-benefits-title"><?php esc_html_e( 'Forminator Pro Benefits', Forminator::DOMAIN ); ?></h4>

								<ul class="fui-black-benefits-list">

									<li class="fui-black-benefits-first">
										<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
										<?php esc_html_e( 'eSignature Module', Forminator::DOMAIN ); ?>
									</li>

									<li class="fui-black-benefirst-subscriptions">
										<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
										<?php esc_html_e( 'Subscription Payments', Forminator::DOMAIN ); ?><span class="sui-tag sui-tag-sm sui-tag-blue"><?php esc_html_e( 'Coming Soon', Forminator::DOMAIN ); ?></span>
									</li>

									<li class="fui-black-benefits-first">
										<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
										<?php esc_html_e( 'All Future Pro Extensions', Forminator::DOMAIN ); ?>
									</li>

									<li>
										<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
										<?php esc_html_e( '24/7 Support', Forminator::DOMAIN ); ?>
									</li>

								</ul>

							</div>

						</div>

						<div class="sui-box-footer">

							<a href="https://premium.wpmudev.org/project/forminator-pro/?coupon=BF2020FORMINATOR&checkout=0&utm_source=forminator&utm_medium=plugin&utm_campaign=forminator_bf2020modal_checkallplansbutton" class="sui-button sui-button-ghost" target="_blank">
								<span class="sui-icon-open-new-window sui-sm" aria-hidden="true"></span>
								<?php esc_html_e( 'Check All Plans', Forminator::DOMAIN ); ?>
							</a>

						</div>

					</div>

				</li>

			</ul>

		</div>

	</div>

</div>

<script type="text/javascript">
	jQuery( '#forminator-black-friday .forminator-dismiss-new-feature' ).on( 'click', function( e ) {
		e.preventDefault();

		var $notice = jQuery( e.currentTarget ).closest( '.forminator-feature-modal' );
		var ajaxUrl = '<?php echo forminator_ajax_url();// phpcs:ignore ?>';

		jQuery.post(
			ajaxUrl,
			{
				action: 'forminator_dismiss_blackfriday',
				prop: $notice.data('prop'),
				_ajax_nonce: $notice.data('nonce')
			}
		).always( function() {
			$notice.hide();
		});
	});
</script>
