<div class="wrap">
	<div class="pdp-admin-page">
		<header class="pdp-admin-page-header">
			<h2 class="pdp-admin-heading"><?=get_admin_page_title(); ?></h2>
		</header>

		<main class="pdp-admin-page-body">
			<?php

			$instagram = new PDP_Core_Instagram();

			if( $instagram->is_token_expired() ) : ?>
				<a href="<?=$instagram->get_login_url(); ?>" class="pdp-btn"><?=__( 'Войти в Instagram', 'pdp' ); ?></a>
			<?php else : ?>
				<?=sprintf( __( 'Сайт связан с аккаунтом %s' ), "<a href='https://instagram.com/{$instagram->get_user_profile()['username']}' target='_blank'>@{$instagram->get_user_profile()['username']}</a>" ); ?>
				<div style="margin-top: 12px;">
                    <button type="button" class="pdp-btn" data-ajax="instagram_feed_sync"><?=__( 'Синхронизировать', 'pdp' ); ?></button>
                    <button type="button" class="pdp-btn" data-ajax="instagram_unlink"><?=__( 'Отвязать', 'pdp' ); ?></button>
                </div>
			<?php endif; ?>
		</main>
	</div>
</div>
<script>
    jQuery(function($){
        $(document).ready(function(){
            $('[data-ajax]').click(function(){
                let $self = $(this)
                let data = {
                    action: $self.data('ajax')
                }

                $self.attr('disabled', true)
                $self.addClass('loading')

                $.post(ajaxurl, data, (response) => {
                    $self.removeAttr('disabled')
                    $self.removeClass('loading')
                }).fail(() => {
                    $self.removeAttr('disabled')
                    $self.removeClass('loading')
                    alert('Что-то пошло не так. Если ошибка повторится, обратитесь к администратору.')
                });
            });
        });
    });
</script>