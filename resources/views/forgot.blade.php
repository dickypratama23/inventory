<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Web Stock - Lupa Password</title>
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
				<form action="{{ url('/otps') }}" method="post">
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
							<input name="otp" id="otp" type="text" placeholder="Kode OTP">
						</div>
					</div>
					<div class="wthree-field">
						<button type="submit" class="btn">Reset Password</button>
					</div>
					<ul class="list-login">
						
						<li>
       <a href="#" onclick="otp()" class="text-right"><u>Kirim OTP Ke Telegram </u></a>
       <button type="submit" id="btnOTP" formaction="{{ url('otp') }}" style="display:none">Reset Password</button>
						</li>
						<li class="clearfix"></li>
					</ul>
				</form>
			</div>
		</div>
    </div>
</section>
</body>
<script>
function otp()
{
 document.getElementById('btnOTP').click();
}

@if (session('error')) 
 alert("{{ session('error') }}");
@endif
</script>
</html>
