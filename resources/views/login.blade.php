<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Web Stock - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" media="all">
	  <link href="fonts/font.css" rel="stylesheet">
</head>
<body>
<section class="main">
	<div class="layer">
		<div class="content-w3ls">
			<div class="text-center icon">
				<img src="images/Indomaret.png" alt="" width="220px">
			</div>
			<div class="content-bottom">
				<form action="{{ url('/login') }}" method="post">
          {{ csrf_field() }}
					<div class="field-group">
						<span class="fa fa-user" aria-hidden="true"></span>
						<div class="wthree-field">
							<input name="nik" id="nik" type="text" value="" placeholder="Nomor Induk Karyawan" required>
						</div>
					</div>
					<div class="field-group">
						<span class="fa fa-lock" aria-hidden="true"></span>
						<div class="wthree-field">
							<input name="password" id="password" type="Password" placeholder="Password">
						</div>
					</div>
					<div class="wthree-field">
						<button type="submit" class="btn">Login</button>
					</div>
					<ul class="list-login">
						<li class="switch-agileits">
							<label class="switch">
								<input type="checkbox" disabled>
								<span class="slider round"></span>
								keep Logged in
							</label>
						</li>
						<li>
							<a href="{{ url('/forgot') }}" class="text-right">forgot password?</a>
						</li>
						<li class="clearfix"></li>
					</ul>
				</form>
			</div>
		</div>
    </div>
</section>
</body>
</html>
