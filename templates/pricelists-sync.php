<div class="wrap">
	<div class="pdp-admin-page">
		<header class="pdp-admin-page__header">
			<h2 class="pdp-admin-heading"><?=get_admin_page_title(); ?></h2>
		</header>

		<main class="pdp-admin-page__body">
			<?php

			$google_api = new PDP_Core_Google();
			$client = $google_api->get_client();

			if( !$client->isAccessTokenExpired() ){
				$salons = pdp_get_salons(); ?>
				<div class="salons-list">
					<div class="salons-list__header">
						<h3><?=__( 'Салоны', 'pdp_core' ); ?></h3>
					</div>

					<div class="salons-list__body">
						<?php foreach( $salons as $salon ) : ?>
							<div class="salons-list__row <?=( !$salon->_pricelist_sheet_id ) ? 'disabled' : ''; ?>">
								<div class="salons-list__col salons-list__col_title">
									<?=$salon->post_title; ?>
								</div>

								<div class="salons-list__col salons-list__col_id">
									<?php if( $salon->_pricelist_sheet_id ){ ?>
										<?=$salon->_pricelist_sheet_id; ?>
									<?php } else { ?>
										<?=__( 'Прайслист не подвязан', 'pdp_core' ); ?>
										( <a href="<?=get_edit_post_link( $salon->ID ); ?>"><?=__( 'подвязать', 'pdp_core' ); ?></a> )
									<?php } ?>
								</div>

								<div class="salons-list__col salons-list__col_date">
									<?php if( $salon->_pricelist_last_update ){ ?>
										<?=__( 'Последнее обновление:', 'pdp_core' ); ?> <span><?=$salon->_pricelist_last_update; ?></span>
									<?php } else { ?>
										<?=__( 'Не обновлялся', 'pdp_core' ); ?>
									<?php } ?>
								</div>

								<div class="salons-list__col salons-list__col_btns">
									<button class="pdp-btn" data-update-pricelist="<?=$salon->ID; ?>" <?php echo ( !$salon->_pricelist_sheet_id ) ? 'disabled' : ''; ?>><?=__( 'Синхронизировать', 'pdp_core' ); ?></button>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="salons-list__footer">
						<button class="pdp-btn" data-update-pricelists><?=__( 'Синхронизировать все цены', 'pdp_core' ); ?></button>
					</div>
				</div>
			<?php } ?>
		</main>
	</div>
</div>
<script>
    jQuery(function($){
        $(document).ready(function(){
            $('[data-update-pricelists]').click(function(){
                let $self = $(this);
                let data = {
                    action: 'sync_pricelists'
                };

                $self.attr('disabled', true);
                $self.addClass('loading');

                $.post(ajaxurl, data, (response) => {
                    $self.removeAttr('disabled');
                    $self.removeClass('loading');
                }).fail(() => {
                    $self.removeAttr('disabled');
                    $self.removeClass('loading');
                    alert('Что-то пошло не так. Если ошибка повторится, обратитесь к администратору.')
                });
            });

            $('[data-update-pricelist]').click(function(){
                let $self = $(this);
                let data = {
                    action:     'sync_pricelist',
                    id:         $self.data('update-pricelist')
                };

                $self.attr('disabled', true);
                $self.addClass('loading');

                $.post(ajaxurl, data, (response) => {
                    $self.removeAttr('disabled');
                    $self.removeClass('loading');
                }).fail(() => {
                    $self.removeAttr('disabled');
                    $self.removeClass('loading');
                    alert('Что-то пошло не так. Если ошибка повторится, обратитесь к администратору.')
                });
            });
        });
    });
</script>