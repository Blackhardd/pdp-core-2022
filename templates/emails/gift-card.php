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
			<tr><td><?=__( 'Подарочный сертификат', 'pdp' ); ?>: <?=$data['color']; ?> (<?=$data['amount']; ?> грн)</td></tr>
            <tr><td><?=__( 'Количество', 'pdp' ); ?>: <?=$data['qty']; ?></td></tr>
		</table>
	</td>
</tr>