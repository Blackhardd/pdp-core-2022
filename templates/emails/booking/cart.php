<?php

$currency = carbon_get_theme_option( 'currency_' . pll_default_language() );

?>

<tr>
	<td>
		<table width="100%" style="border-spacing: 0">
			<tr>
				<td colspan="2"><h4><?=__( 'Услуги', 'pdp_core' ); ?></h4></td>
			</tr>
			<?php foreach( $data['services'] as $service ) : ?>
				<tr>
					<td><?=$service['name']; ?></td>
					<td><?=$service['price'] . ' ' . $currency; ?></td>
				</tr>
			<?php endforeach; ?>
			<tr>
                <td colspan="2" style="padding-top: 10px;"><b><?=__( 'Итого', 'pdp_core' ); ?>:</b> <?=$data['total'] . ' ' . $currency; ?></td>
			</tr>
		</table>
	</td>
</tr>