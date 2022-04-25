<?php if( isset( $data['data']['page_title'] ) && isset( $data['data']['page_url'] ) ) : ?>
    <tr>
        <td>
            <table width="100%" style="margin-bottom: 30px; border-spacing: 0">
                <tr>
                    <td><h4 style="margin: 0;"><?=__( 'Аналитика', 'pdp_core' ); ?>:</h4></td>
                </tr>
                <tr>
                    <td><?=__( 'Страница', 'pdp_core' ); ?>: <a href="<?=$data['data']['page_url']; ?>"><?=$data['data']['page_title']; ?></a></td>
                </tr>
                <?php if( isset( $data['data']['utm_source'] ) ) : ?>
                    <tr>
                        <td><?=__( 'Источник', 'pdp_core' ); ?>: <?=$data['data']['utm_source']; ?></td>
                    </tr>
                    <tr>
                        <td><?=__( 'Тип трафика', 'pdp_core' ); ?>: <?=$data['data']['utm_medium']; ?></td>
                    </tr>
                    <tr>
                        <td><?=__( 'ID кампании', 'pdp_core' ); ?>: <?=$data['data']['utm_campaign']; ?></td>
                    </tr>
                    <tr>
                        <td><?=__( 'ID объявления', 'pdp_core' ); ?>: <?=$data['data']['utm_content']; ?></td>
                    </tr>
                    <tr>
                        <td><?=__( 'Ключевое слово', 'pdp_core' ); ?>: <?=$data['data']['utm_term']; ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </td>
    </tr>
<?php endif; ?>

<?php if( isset( $data['salon_name'] ) ) : ?>
    <tr>
        <td>
            <table width="100%" style="margin-bottom: 30px; border-spacing: 0">
                <tr>
                    <td><h4 style="margin: 0;"><?=__( 'Салон', 'pdp_core' ); ?>:</h4></td>
                </tr>
                <tr>
                    <td><?=$data['salon_name']; ?></td>
                </tr>
            </table>
        </td>
    </tr>
<?php endif; ?>

<tr>
	<td>
		<table width="100%" style="margin-bottom: 30px; border-spacing: 0">
			<tr>
				<td><h4><?=__( 'Контактные данные', 'pdp_core' ); ?>:</h4></td>
			</tr>
			<tr>
				<td><?=$data['data']['name']; ?></td>
			</tr>
			<?=( isset( $data['data']['email'] ) ) ? "<tr><td><a href='mailto:{$data['data']['email']}'>{$data['data']['email']}</a></td></tr>" : ''; ?>
			<?=( $data['data']['phone'] ) ? "<tr><td><a href='tel:{$data['data']['phone']}'>{$data['data']['phone']}</a></td></tr>" : ''; ?>
		</table>
	</td>
</tr>

<?php if( isset( $data['data']['promotion'] ) ) : ?>
    <tr>
        <td>
            <table width="100%" style="margin-bottom: 30px; border-spacing: 0">
                <tr>
                    <td><h4 style="margin: 0;"><?=__( 'Акция', 'pdp_core' ); ?>:</h4></td>
                </tr>
                <tr>
                    <td><?=$data['data']['promotion']; ?></td>
                </tr>
            </table>
        </td>
    </tr>
<?php endif; ?>

<?php if( isset( $data['data']['cart']['master_option'] ) || isset( $data['data']['is_hair_services'] ) ) :
	$hair_length = pdp_get_hair_length_title( $data['data']['cart']['hair_length'] ); ?>
    <tr>
        <td>
            <table width="100%" style="margin-bottom: 30px; border-spacing: 0">
                <tr>
                    <td><h4><?=__( 'Дополнительная информация', 'pdp_core' ); ?>:</h4></td>
                </tr>
                <?=( $data['data']['cart']['master_option'] ) ? "<tr><td>Старший мастер.</td></tr>" : ''; ?>
                <?=( $data['data']['is_hair_services'] ) ? "<tr><td>Длина волос {$hair_length}.</td></tr>" : ''; ?>
            </table>
        </td>
    </tr>
<?php endif; ?>