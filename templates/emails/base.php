<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<style>
		body {
			margin: 0;
			padding: 0;
			background: #F9F9F9;
		}
		table {
			border-spacing: 0;
		}
		td {
			padding: 0;
		}
        h4{
            margin: 0 0 10px;
        }
		img {
			border: 0;
		}
		.wrapper {
			width: 100%;
			padding: 40px 0;
			table-layout: fixed;
			background: #F9F9F9;
		}
		.webkit {
			max-width: 600px;
			background: #ffffff;
            border-radius: 4px;
            overflow: hidden;
		}
		.outer {
			width: 100%;
			max-width: 600px;
			margin: 0 auto;
			color: #4a4a4a;
			font-family: Helvetica, Arial, sans-serif;
			border-spacing: 0;
		}

		@media screen and (max-width: 600px) {}
		@media screen and (max-width: 400px) {}
	</style>
</head>
<body>
<center class="wrapper">
	<div class="webkit">
		<table class="outer" align="center">
			<tr>
				<td>
					<table width="100%" style="border-spacing: 0; border-bottom: 1px solid #AA957C;">
						<tr>
							<td style="padding: 10px; text-align: center;">
								<a href="<?=get_option( 'siteurl' ); ?>"><img src="<?=wp_get_attachment_image_url( carbon_get_theme_option( 'email_logo' ), 'full' ); ?>" alt="Logo" title="PIED-DE-POULE"></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td>
					<table width="100%" style="padding: 20px; border-spacing: 0;">
						<?=$data['content']; ?>
					</table>
				</td>
			</tr>
		</table>
	</div>
</center>
</body>
</html>