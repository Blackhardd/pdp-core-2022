<tr>
    <td>
        <table width="100%" style="margin-bottom: 30px; border-spacing: 0">
            <tr>
                <td><h4><?=__( 'Вакансия', 'pdp_core' ); ?>:</h4></td>
            </tr>
            <tr>
                <td><?=$data['data']['vacancy']; ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
	<td>
		<table width="100%" style="margin-bottom: 30px; border-spacing: 0">
			<tr>
				<td><h4><?=__( 'Контактные данные', 'pdp_core' ); ?>:</h4></td>
			</tr>
			<tr>
				<td><?=$data['data']['name']; ?></td>
			</tr>
			<?=( $data['data']['email'] ) ? "<tr><td><a href='mailto:{$data['data']['email']}'>{$data['data']['email']}</a></td></tr>" : ''; ?>
			<?=( $data['data']['phone'] ) ? "<tr><td><a href='tel:{$data['data']['phone']}'>{$data['data']['phone']}</a></td></tr>" : ''; ?>
		</table>
	</td>
</tr>

<?php if( $data['data']['message'] ) : ?>
    <tr>
        <td>
            <table width="100%" style="border-spacing: 0">
                <tr>
                    <td><h4><?=__( 'Сообщение', 'pdp_core' ); ?>:</h4></td>
                </tr>
                <tr>
                    <td><?=$data['data']['message']; ?></td>
                </tr>
            </table>
        </td>
    </tr>
<?php endif; ?>