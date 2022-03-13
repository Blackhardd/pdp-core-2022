<tr>
	<td>
		<table width="100%" style="margin-bottom: 30px; border-spacing: 0">
			<tr>
				<td><h4><?=__( 'Контактные данные', 'pdp' ); ?>:</h4></td>
			</tr>
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

			<?php if( $data['white'] ) : ?>
				<tr><td><?=$data['white']; ?> x <?=$data['white_qty']; ?></td></tr>
			<?php endif; ?>

			<?php if( $data['pink'] ) : ?>
				<tr><td><?=$data['pink']; ?> x <?=$data['pink_qty']; ?></td></tr>
			<?php endif; ?>

			<?php if( $data['black'] ) : ?>
				<tr><td><?=$data['black']; ?> x <?=$data['black_qty']; ?></td></tr>
			<?php endif; ?>
		</table>
	</td>
</tr>