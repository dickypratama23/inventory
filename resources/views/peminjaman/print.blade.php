<!DOCTYPE html>
<html>
<head>
	<title>HTML to API - Invoice</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<!-- <link rel="stylesheet" href="sass/main.css" media="screen" charset="utf-8"/> -->
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta http-equiv="content-type" content="text-html; charset=utf-8">
	<style type="text/css">
		html, body, div, span, applet, object, iframe,
		h1, h2, h3, h4, h5, h6, p, blockquote, pre,
		a, abbr, acronym, address, big, cite, code,
		del, dfn, em, img, ins, kbd, q, s, samp,
		small, strike, strong, sub, sup, tt, var,
		b, u, i, center,
		dl, dt, dd, ol, ul, li,
		fieldset, form, label, legend,
		table, caption, tbody, tfoot, thead, tr, th, td,
		article, aside, canvas, details, embed,
		figure, figcaption, footer, header, hgroup,
		menu, nav, output, ruby, section, summary,
		time, mark, audio, video {
			margin: 0;
			padding: 0;
			border: 0;
			font: inherit;
			font-size: 100%;
			vertical-align: baseline;
		}

		html {
			line-height: 1;
		}

		ol, ul {
			list-style: none;
		}

		table {
			border-collapse: collapse;
			border-spacing: 0;
		}

		caption, th, td {
			text-align: left;
			font-weight: normal;
			vertical-align: middle;
		}

		q, blockquote {
			quotes: none;
		}
		q:before, q:after, blockquote:before, blockquote:after {
			content: "";
			content: none;
		}

		a img {
			border: none;
		}

		article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
			display: block;
		}

		body {
			font-family: 'helvetica', sans-serif;
			font-weight: 300;
			font-size: 12px;
			margin: 0;
			padding: 0;
		}
		body a {
			text-decoration: none;
			color: inherit;
		}
		body a:hover {
			color: inherit;
			opacity: 0.7;
		}
		body .container {
			min-width: 500px;
			margin: 0 auto;
			padding: 0 20px;
		}
		body .clearfix:after {
			content: "";
			display: table;
			clear: both;
		}
		body .left {
			float: left;
		}
		body .right {
			float: right;
		}
		body .helper {
			display: inline-block;
			height: 100%;
			vertical-align: middle;
		}
		body .no-break {
			page-break-inside: avoid;
		}

		header {
			margin-top: 20px;
			margin-bottom: 50px;
		}
		header figure {
			float: left;
			
			height: 60px;
            margin-right: 15px;
            margin-top: -14px;
			text-align: center;
		}
		header figure img {
			margin-top: 13px;
		}
		header .company-address {
			float: left;
			line-height: 1.7em;
		}
		header .company-address .title {
			color: #8BC34A;
			font-weight: 400;
			font-size: 1.5em;
			text-transform: uppercase;
		}
		header .company-contact {
            float: right;
            color: #8BC34A;
			height: 60px;
            font-weight: 400;
			font-size: 1.5em;
			text-transform: uppercase;
		}
		header .company-contact span {
			display: inline-block;
			vertical-align: middle;
		}
		header .company-contact .circle {
			width: 20px;
			height: 20px;
			background-color: white;
			border-radius: 50%;
			text-align: center;
		}
		header .company-contact .circle img {
			vertical-align: middle;
		}
		header .company-contact .phone {
			height: 100%;
			margin-right: 20px;
		}
		header .company-contact .email {
			height: 100%;
			min-width: 100px;
			text-align: right;
		}

		section .details {
			margin-bottom: 55px;
		}
		section .details .client {
			width: 50%;
			line-height: 20px;
		}
		section .details .client .name {
			color: #8BC34A;
		}
		section .details .data {
			width: 50%;
			text-align: right;
		}
		section .details .title {
			margin-bottom: 15px;
			color: #8BC34A;
			font-size: 1.5em;
			font-weight: 400;
			text-transform: uppercase;
		}
		section table {
			width: 100%;
			border-collapse: collapse;
			border-spacing: 0;
			font-size: 0.9166em;
		}
        section table .no {
			width: 5%;
        }
        section table .qty {
			width: 5%;
		}
        section table .unit, section table .total {
			width: 25%;
		}
		section table .desc {
			width: 55%;
		}
		section table thead {
			display: table-header-group;
			vertical-align: middle;
			border-color: inherit;
		}
		section table thead th {
			padding: 5px 10px;
			background: #8BC34A;
			border-bottom: 5px solid #FFFFFF;
			border-right: 4px solid #FFFFFF;
			text-align: right;
			color: white;
			font-weight: 400;
			text-transform: uppercase;
		}
		section table thead th:last-child {
			border-right: none;
		}
		section table thead .desc, section table thead .unit {
			text-align: left;
        }
        section table thead .no {
			text-align: center;
		}
		section table thead .qty {
			text-align: center;
		}
		section table tbody td {
			padding: 10px;
			background: #E8F3DB;
			color: #777777;
			text-align: left;
			border-bottom: 5px solid #FFFFFF;
			border-right: 4px solid #E8F3DB;
		}
		section table tbody td:last-child {
			border-right: none;
		}
		section table tbody h3 {
			margin-bottom: 5px;
			color: #8BC34A;
			font-weight: 600;
		}
		section table tbody .desc {
			text-align: left;
        }
        section table tbody .no {
			text-align: center;
		}
		section table tbody .qty {
			text-align: center;
		}
		section table.grand-total {
			margin-bottom: 45px;
		}
		section table.grand-total td {
			padding: 5px 10px;
			border: none;
			color: #777777;
			text-align: right;
		}
		section table.grand-total .desc {
			background-color: transparent;
		}
		section table.grand-total tr:last-child td {
			font-weight: 600;
			color: #8BC34A;
			font-size: 1.18181818181818em;
		}

		footer {
			margin-bottom: 20px;
		}
		footer .thanks {
			margin-bottom: 40px;
			color: #8BC34A;
			font-size: 1.16666666666667em;
			font-weight: 600;
		}
		footer .notice {
			margin-bottom: 25px;
		}
		footer .end {
			padding-top: 5px;
			border-top: 2px solid #8BC34A;
			text-align: center;
		}
	</style>
</head>

<body>
	<header class="clearfix">
		<div class="container">
			<figure>
				<img src="../../images/idm.png" width="170" alt="">
			</figure>
			<div class="company-address">
				<h2 class="title">PT. Indomarco Prismatama</h2>
				<p>
                    Komplek Ruko Trikarsa Ekualita Blok 1 No. 1A-1B<br>
					Kel. Sadai, Kec. Bengkong Kota Batam (29457)
				</p>
			</div>
			<div class="company-contact">
				<div class="phone left">
					<h2 class="title">Surat Jalan Peminjaman</h2>
				</div>
			</div>
		</div>
	</header>

	<section>
		<div class="container">
			<div class="details clearfix">
				<div class="client left">
					<p>Penerima:</p>
                    <p class="name">{{ $transaksi->department->kdtk }} - {{ $transaksi->department->name }}</p>
                    <p class="name">{{ $transaksi->pic }}</p>
				</div>
				<div class="data right">
					<div class="title">{{ $transaksi->invoice }}</div>
					<div class="date">
						Tanggal Terima: {{ $transaksi->created_at->format('d-m-Y') }}
					</div>
				</div>
			</div>

			<table border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
							<th class="no">No.</th>
							<th class="unit">Nama Barang</th>
							<th class="qty">Quantity</th>
							<th class="mac">Serial Number</th>
							<th class="desc">Description</th>
					</tr>
				</thead>
				<tbody>
					@php $no = 1 @endphp
                    @foreach ($transaksi->detail as $row)
                    <tr>
                        <td class="no">{{ $no++ }}</td>
                        <td class="unit">{{ $row->cad->kode }} - {{ $row->cad->name }}</td>
																								<td class="qty">{{ $row->qty }}</td>
																								<td class="mac">{{ $row->Serial_number }}</td>
                        <td class="desc">{{ $row->note }}</td>
                    </tr>
                    @endforeach
				</tbody>
			</table>
		</div>
	</section>
    <br><br>
	<footer>
		<div class="container">
			<div class="thanks right"><center> Pembuat </center> <br><br><br><br><br><br>
			{{ $transaksi->user->nik }} - {{ $transaksi->user->name }}
			</div>
		</div>
		<div class="container">
			<div class="thanks right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		</div>
		<div class="container">
			<div class="thanks right"><center> Penerima </center> <br><br><br><br><br><br>
			{{ $transaksi->pic }}
			</div>
		</div>
	</footer>

</body>

</html>
