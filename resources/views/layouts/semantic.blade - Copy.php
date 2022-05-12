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

<div class="ui small top fixed menu">
  <div class="item">
    <h2 class="ui header">
    <img class="ui image" src="{{ asset('images/icon.jpg') }}">
      <div class="content">
          Web Stock
      </div>
    </h2>
  </div>
  
  <div class="left menu">
    <div class="ui dropdown item management">
      Management <i class="dropdown icon"></i>
      <div class="menu">
        <a href="{{ url('/kategori') }}" class="item Kategori {{ (request()->is('kategori*')) ? 'active' : '' }}">Kategori</a>
        <a href="{{ url('/barang') }}" class="item Barang {{ (request()->is('barang*')) ? 'active' : '' }}">Barang</a>
        <a href="{{ url('/CAD') }}" class="item Cadangan {{ (request()->is('CAD*')) ? 'active' : '' }}">Cadangan</a>
      </div>
    </div>

    <div class="ui dropdown item">
      Transaksi <i class="dropdown icon"></i>
      <div class="menu">
        <a href="{{ url('/transin/new') }}" class="item BarangMasuk {{ (request()->is('transin/new')) ? 'active' : '' }}">Barang Masuk</a>
        <a href="{{ url('/transout/new') }}" class="item BarangKeluar {{ (request()->is('transout/new')) ? 'active' : '' }}">Barang Keluar</a>
        <a href="{{ url('/peminjaman/new') }}" class="item Peminjaman {{ (request()->is('peminjaman/new')) ? 'active' : '' }}">Peminjaman</a>
        <a href="{{ url('/GO') }}" class="item GO {{ (request()->is('GO*')) ? 'active' : '' }}">GO</a>
      </div>
    </div>

    <div class="ui dropdown item">
      Service <i class="dropdown icon"></i>
      <div class="menu">
        <a href="{{ url('/service') }}" class="item TerimaService {{ (request()->is('service')) ? 'active' : '' }}">Terima Service</a>
        <a href="{{ url('/service/list') }}" class="item ServiceCabang {{ (request()->is('service/list*')) ? 'active' : '' }}">Service Cabang</a>
        <a href="{{ url('/service/ho_list') }}" class="item ServiceHO {{ (request()->is('service/ho_list*')) ? 'active' : '' }}">Service HO</a>
        <a href="{{ url('/service/ambil') }}" class="item StatusPengambilan {{ (request()->is('service/ambil*')) ? 'active' : '' }}">Status Pengambilan</a>
      </div>
    </div>

    <a href="{{ url('/approval') }}" class="item Approve {{ (request()->is('approval*')) ? 'active' : '' }}">
      Approve
    </a>

    <div class="ui dropdown item">
      Report <i class="dropdown icon"></i>
      <div class="menu">
        <a href="{{ url('/transin') }}" class="item {{ (request()->is('transin')) ? 'active' : '' }}">Laporan Transaksi Masuk</a>
        <a href="{{ url('/transout') }}" class="item {{ (request()->is('transout')) ? 'active' : '' }}">Laporan Transaksi Keluar</a>
        <a href="{{ url('/peminjaman') }}" class="item {{ (request()->is('peminjaman')) ? 'active' : '' }}">Laporan Transaksi Peminjaman</a>
        <a href="{{ url('/service/report_service') }}" class="item {{ (request()->is('service/report_service*')) ? 'active' : '' }}">Laporan Service</a>
      </div>
    </div>

    <a href="{{ url('/lpp') }}" class="item {{ (request()->is('lpp*')) ? 'active' : '' }}">
      LPP
    </a>

  </div>

  <div class="ui right dropdown item">
    Welcome {{ Session('nama') }}
    <i class="dropdown icon"></i>
    <div class="menu">
      <a href="{{ url('/logout') }}" class="item">Logout</a>
    </div>
  </div>
</div>

<br><br><br><br><br>

<div class="ui container"> 

  @if(Session('ttd'))
  <div class="ui warning message">
    <i class="close icon"></i>
    <div class="header">
      Info !!!
    </div>
    <p>Tanda Tangan Anda Belum Ada, Silahkan Tanda Tangan Terlebih Dahulu 
      <a href="{{ route('ttd') }}" class="ui red label">
        Disini
      </a>
    </p>
  </div>
  @endif

  @if (session('message_success')) 
    <div class="ui positive message">
      <i class="close icon"></i>
      <div class="header">
        Success!
      </div>
      <p>{{ session('message_success') }}</p>
    </div>
  @elseif(session('message_error'))
    <div class="ui negative message">
      <i class="close icon"></i>
      <div class="header">
        Error!
      </div>
      <p>{{ session('message_error') }}</p>
    </div>
  @endif

  @if (session('message_telegram')) 
    <div class="ui positive message">
      <i class="close icon"></i>
      <div class="header">
        Telegram Not Sent!
      </div>
      <p>{{ session('message_telegram') }}</p>
    </div>
  @endif


  @yield('content')  
</div>  




    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>

    
    <script type="text/javascript" src="{{ asset('js/semantic.min.js') }}"></script>
    <script src="{{ asset('js/signature_pad.min.js') }}"></script>

    <script src="{{ asset('js/jquery.mask.js') }}"></script>
    
</body>
<script>

  

$(document).ready(function() {
  $('input').attr('autocomplete','off');
  $('.prompt.mixed').mask('000000000Z | SRRRRRRRRRRRRRRR RRRRRRRRRRRRRRRRR RRRRRRRRRRRRRRRRR RRRRRRRRRRRRR', {
    //clearIfNotMatch: true,
    translation: {
      'Z': {
        pattern: /[0-9]/, optional: true
      },
      'R': {
        pattern: /[a-zA-Z]/, optional: true
      }
    }
  });

  var neg = $(".negative");
  if(neg.length > 0){
    $('.btnGo').addClass("disabled");
  }

  if({{ session('role') }} == 4){ //role 4 AWHOST & EDP OPR
    $('.BarangMasuk').addClass("disabled");
    $('.GO').addClass("disabled");
    $('.ServiceCabang').addClass("disabled");
    $('.ServiceHO').addClass("disabled");
    $('.Approve').addClass("disabled");
    $('.Kategori').addClass("disabled");
    $('.Barang').addClass("disabled");
  }else if({{ session('role') }} == 3){ //role 4 SERVICER
    $('.Approve').addClass("disabled");
  }

  $('#example').DataTable({
    "pageLength": 5,
    dom: 'Bfrtip',
    buttons: [
        {
                extend:    'copyHtml5',
                text:      '<i class="copy icon"></i>',
                titleAttr: 'Copy'
            },
            {
                extend:    'csvHtml5',
                text:      '<i class="file alternate outline icon"></i>',
                titleAttr: 'CSV'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="file pdf icon"></i>',
                titleAttr: 'PDF'
            },
            {
                extend:    'print',
                text:      '<i class="print icon"></i>',
                titleAttr: 'Print',
                autoPrint: false,
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }
            }
    ]
  });

  $('#examples').DataTable({
    "order": [[ 1, "asc" ]]
  });

  $('.button').popup();

  $('.ui.accordion').accordion();

  
});



  
$(".fold-table tr.view").on("click", function(){
  $(this).next(".fold").toggle();
});


$('.ui.dropdown').dropdown();
$('.message .close').on('click', function() {
  $(this).closest('.message').transition('fade');
});








$('.ui.search.barang').search({
  type: 'category',
  minCharacters : 0,
  apiSettings: {
    onResponse: function(serverResponse) {
          var response = { 
            results: {} 
          };
          //translate Server API response to work with search
          $.each(serverResponse.results, function(index, barang) {
              var
                  nama = barang.kategori.name || 'Unknown',
                  maxResults = 8
              ;

              

              var mac_sn = barang.mac;
              if(response.results[nama] === undefined) {
                  response.results[nama] = {
                      name: nama,
                      results: []
                  };
              }
              //add result to category
              response.results[nama].results.push({
                  title: barang.name,
                  price: barang.kode,
                  id: barang.id,
                  mac: mac_sn,
                  ctgr: barang.kategori
              });
          });
          return response;
      },
    url: 'apis/barang?q={query}'
  },
  onSelect: function(serverResponse){
    console.log(serverResponse.ctgr);
    
    $('.nama_barang').val(serverResponse.title);

    $('.barang_id').val(serverResponse.id);
    $('.barang_kode').val(serverResponse.id);
    $('.barang_kode_name').val(serverResponse.price);

    $('.kategori_id').val(serverResponse.ctgr.id);
    $('.kategori_id_name').val(serverResponse.ctgr.name);

    $('.mac_lent').val(serverResponse.mac);

    if(serverResponse.mac == 1){
      $(".mac").prop('required',true);
      $(".mac").prop('disabled',false);
    }else{
      $(".mac").prop('required',false);
      $(".mac").prop('disabled',true);
    }
  },
});

$('.ui.search.department').search({
  type: 'category',
  minCharacters : 0,
  apiSettings: {
    onResponse: function(serverResponse) {
          var response = { 
            results: {} 
          };
          //translate Server API response to work with search
          $.each(serverResponse.results, function(index, dept) {
              var
                  tipe = dept.jenis || 'Unknown',
                  maxResults = 5
              ;
              
              if(index >= maxResults) {
                return false;
              }

              var nama_toko = dept.kdtk + ' - ' + dept.name;
              if(response.results[tipe] === undefined) {
                  response.results[tipe] = {
                      name: tipe,
                      results: []
                  };
              }
              //add result to category
              response.results[tipe].results.push({
                  title: nama_toko,
                  id: dept.id
              });
          });
          return response;
      },
    url: 'apis/department?q={query}'
  },
  onSelect: function(serverResponse){
    console.log(serverResponse.id);
    $('.dept').val(serverResponse.id);

    // $.ajax({
    //   url:"/STOCK/apis/pinjam",
    //   type:"GET",
    //   data:{
    //     kdtk_id: serverResponse.id,
    //   },
    //   success:function(response) {
    //     $.each(response.results, function(index, hasil) {
    //       console.log(hasil.kode);
    //     });
    //   },
    //   error:function(){
    //     alert("error");
    //   }
    // });
  },
});


  $('.ui.search.karyawan').search({
      apiSettings: {
          url: 'apis/karyawan?q={query}'
      },
      fields: {
          results : 'results',
          title   : 'KARYAWAN',
          description   : 'deskripsi'
      },
      onSelect: function(result){
          console.log(result.id);
      },
      minCharacters: 0
  });

  var rows= $('tbody .sp_table').length;

  if (rows > 0) {
    $('.check_penggantian').prop('checked', true);
    $('.ada_pengg').val(1);
  }else{
    $('.check_penggantian').prop('checked', false);
    $('.ada_pengg').val(0);
  }

  if ($('.check_penggantian').prop('checked')) {
    $('.penggantian').show();
  }else{
    $('.penggantian').hide();
  }

  $('.ui.checkbox').checkbox({
      onChecked: function() {
        $('.penggantian').show();
      },
      onUnchecked: function() {
        $('.penggantian').hide();
      }
  });






  $('.ui.search.kategori').search({
      apiSettings: {
          url: 'apis/department?q={query}'
      },
      fields: {
          results : 'results',
          title   : 'name',
          description   : 'deskripsi'
      },
      onSelect: function(result){
          console.log(result.id);
      },
      minCharacters: 0
  });

  $('.ui.dropdown.kategori').dropdown({
    fields: { name: "name", value: "id" },
    apiSettings: {
      url: 'api/kategori?q={query}'
    },
    minCharacters: 0,
  });

  

  $('.ui.dropdown.karyawan').dropdown({
    fields: { name: "KARYAWAN", value: "KARYAWAN" },
    apiSettings: {
      url: 'apis/karyawan?q={query}'
    },
    minCharacters: 3
  });


  



$("#test").click(function(){
  $('.ui.modal').modal('show');
});

$(".ambil").click(function(){
  $('.ui.modal').modal('show');
  $('#invoice').val($(this).data("id"));
});






var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
  backgroundColor: 'rgba(255, 255, 255, 0)',
  penColor: 'rgb(0, 0, 0)'
});
var saveButton = document.getElementById('save');
var cancelButton = document.getElementById('clear');

saveButton.addEventListener('click', function (event) {
   
  if ( signaturePad.isEmpty() ) {
	alert(123);
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