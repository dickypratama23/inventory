<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Stock - @yield('title')</title>
    <link type="text/css" rel="stylesheet" href="{{ asset('css/semantic.css') }}"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('DataTables/jquery.dataTables.min.css') }}"  media="screen,projection"/>
</head>
<body>

<br>

<div class="ui container"> 
<h2 class="ui center aligned icon header">
  <i class="pencil icon"></i>
  <div class="content">
    Register Your Signature
  </div>
</h2>

<div class="ui wrapper segment">
 <canvas id="signature-pad" class="signature-pad" width=300 height=200></canvas>
</div>

<form id="myForm" method="POST" action="{{ route('ttd.reg', session('nik')) }}">
 {{ csrf_field() }}
 <input name="imageData" id="imageData" type="hidden"/>
</form>

<button id="save" class="large ui teal labeled icon button">
 <i class="save icon"></i>
  Simpan
</button>
<button id="clear" class="large ui orange labeled icon button">
 <i class="undo icon"></i>
  clear
</button>


</div>  






    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>

    
    <script type="text/javascript" src="{{ asset('js/semantic.min.js') }}"></script>
    <script src="{{ asset('js/signature_pad.min.js') }}"></script>

    <script src="{{ asset('js/jquery.mask.js') }}"></script>
    
</body>
<script>

var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
  backgroundColor: 'rgba(255, 255, 255, 0)',
  penColor: 'rgb(0, 0, 0)'
});
var saveButton = document.getElementById('save');
var cancelButton = document.getElementById('clear');

saveButton.addEventListener('click', function (event) {
   
  if ( signaturePad.isEmpty() ) {
	  alert('Draw Your Signature');
  }
  else
  {
	  var dataUrl = signaturePad.toDataURL('image/png');
	  //document.getElementById('imageid').src = dataUrl;
	  
	  var imagen = dataUrl.replace(/^data:image\/(png|jpg);base64,/, "");
	  $('#imageData').val(imagen);
	  
	  
	  $("#myForm").submit(); 
	  //$.ajax({
	  //  url: 'signature_pad.php',
	  //  type: 'POST',
	  //  data: {
	  //  imageData: imagen
	  //	  },
	  //}).done(function(msg) {
		  // Image saved successfuly.
	//	  console.log("success: " + msg);
	//	  //document.getElementById("my_form").submit(); //I do this to save other information.
	//  }).fail(function(msg) {
	//	  console.log("error: " + msg);
	//  });
  }
  
});

cancelButton.addEventListener('click', function (event) {
  signaturePad.clear();
});
</script>
</html>