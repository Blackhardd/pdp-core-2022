<tr>
	<td>
		<table width="100%" style="margin-bottom: 30px; border-spacing: 0">
			<tr>
				<td><h4><?=__( 'Контактные данные', 'pdp' ); ?>:</h4></td>
			</tr>
			<tr><td><?=$data['name']; ?></td></tr>
			<tr><td><a href="mailto:<?=$data['email']; ?>"><?=$data['email']; ?></a></td></tr>
			<tr><td><a href="tel:<?=$data['phone']; ?>"><?=$data['phone']; ?></a></td></tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" style="border-spacing: 0">
			<tr>
				<td><h4><?=__( 'Заказ', 'pdp' ); ?>:</h4></td>
			</tr>

			<?php if( $data['card_type'] && $data['card_qty'] ) : ?>
				<tr><td><?=__( 'Сертификат', 'pdp' ); ?>: <?=$data['card_type']; ?> x <?=$data['card_qty']; ?></td></tr>
			<?php endif; ?>

			<?php if( $data['box_type'] && $data['box_qty'] ) : ?>
				<tr><td><?=__( 'Бокс', 'pdp' ); ?>: <?=$data['box_type']; ?> x <?=$data['box_qty']; ?></td></tr>
			<?php endif; ?>
		</table>
	</td>
</tr>