<tr>
	<td>
		<table width="100%" style="margin-bottom: 30px; border-spacing: 0">
			<tr>
				<td><h4><?=__( 'Контактные данные', 'pdp_core' ); ?>:</h4></td>
			</tr>
			<tr>
				<td><?=$data['data']['name']; ?></td>
			</tr>
			<tr><td><a href="tel:<?=$data['data']['phone']; ?>"><?=$data['data']['phone']; ?></a></td></tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" style="border-spacing: 0">
			<tr>
				<td><h4><?=__( 'Салон', 'pdp_core' ); ?>:</h4></td>
			</tr>
			<tr>
				<td><?=$data['salon_name']; ?></td>
			</tr>
		</table>
	</td>
</tr>