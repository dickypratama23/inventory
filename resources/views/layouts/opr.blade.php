<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Web Stock - @yield('title')</title>
  <link type="text/css" rel="stylesheet" href="{{ asset('css/semantic.css') }}" media="screen,projection" />
  <link type="text/css" rel="stylesheet" href="{{ asset('DataTables/jquery.dataTables.min.css') }}"
    media="screen,projection" />
  <link type="text/css" rel="stylesheet" href="{{ asset('css/hamburger.css') }}" media="screen,projection" />

  <link rel="stylesheet" href="{{ asset('assets/bootstrap-datepicker/css/datepicker3.css') }}">
  <style>
    .mobile {
      display: none !important;
    }

    .mob_icon {
      display: none !important;
    }


    @media only screen and (max-width: 630px) {

      /* DATA TABLES */
      .paginate_button.item.active {
        display: block !important;
      }

      [data-dt-idx="2"],
      [data-dt-idx="3"],
      [data-dt-idx="4"],
      [data-dt-idx="5"],
      [data-dt-idx="6"] {
        display: none !important;
      }

      .dt-buttons.ui.basic.buttons {
        width: 100%;
      }

      .dataTables_filter {
        width: 100% !important;
      }

      .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em !important;
        width: 100% !important;
      }
    }

    @media only screen and (max-width: 700px) {

      /* GENERAL */
      .mobile {
        display: block !important;
      }

      .mob_icon {
        display: inline-block !important;

      }

      .desktop {
        display: none !important;
      }

      .ui.table[class*="center aligned"],
      .ui.table [class*="center aligned"] {
        text-align: left;
      }

      .ui.table[class*="right aligned"],
      .ui.table [class*="right aligned"] {
        text-align: left;
      }

      .ui.table[class*="center aligned two wide"],
      .ui.table [class*="center aligned two wide"] {
        text-align: center;
      }

      table.dataTable>tbody>tr.child span.dtr-title {
        display: inline-block;
        min-width: 75px;
        font-weight: bold;
      }

      table.dataTable>tbody>tr.child ul.dtr-details {
        list-style-type: none;
      }

      table.dataTable>tbody>tr.child ul.dtr-details>li:first-child {
        padding-top: 0;
      }

      table.dataTable>tbody>tr.child ul.dtr-details>li {
        border-bottom: 1px solid #efefef;
        padding: 0.5em 0;
        padding-top: 0.5em;
      }

      .dtr-details {
        padding: 0.5em 0;
      }

      .act_label {
        display: none !important;
      }

      .act_btn {
        width: 93% !important;
      }

      .ui.stackable.grid {
        width: auto;
        margin-left: -1em !important;
        margin-right: -1em !important;
      }

      .info_kirim .kdtk {
        border: 1px dotted red;
      }
    }
  </style>
</head>

<body>

  <div class="ui pointing menu stackable">
    <div class="item desktop">
      <h2 class="ui header">
        <img class="ui image" src="{{ asset('images/icon.jpg') }}">
        <div class="content">
          Web Stock
        </div>
      </h2>
    </div>
    <a class="item header mobile">
      Web Stock | @yield('title')
    </a>
    <div class="left menu">

      <div class="ui dropdown item">
        BTB <i class="dropdown icon"></i>
        <div class="menu">
          <a href="{{ route('btbtoko') }}"
            class="item BTBTOKO {{ (request()->is('OPR/BTBTOKO')) ? 'active' : '' }}">TOKO</a>
          <a href="{{ route('btbopr') }}"
            class="item BTBOPR {{ (request()->is('OPR/BTBOPR')) ? 'active' : '' }}">OPR</a>
        </div>
      </div>

    </div>

    <div class="ui right dropdown item">
      Welcome {{ Session('nama') }}
      <i class="dropdown icon"></i>
      <div class="menu">
        <a href="{{ url('/logout') }}" class="item">Logout</a>
      </div>
    </div>

    <div class="hamburger ">
      <span class="hamburger-bun"></span>
      <span class="hamburger-patty"></span>
      <span class="hamburger-bun"></span>
    </div>


  </div>

  <div class="ui fluid container">

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

    {{-- MODAL TUTUPAN --}}

    @if(session('tutupan') != date('Y|m'))
    <div class="ui basic tutupan modal">
      <div class="ui icon header">
        <i class="archive icon"></i>
        Tutupan Bulanan
      </div>
      <div class="content">
        <p>Anda Belum Melakukan Tutupan, Harap Lakukan Tutupan Terlebih Dahulu Sebelum Melanjutkan Transaksi</p>
      </div>
      <div class="actions">
        <a href="{{ url('/tutupan') }}" class="ui green ok inverted button">
          <i class="checkmark icon"></i>
          Lakukan Tutupan
        </a>
      </div>
    </div>
    @endif


    @yield('content')
  </div>

  {{-- @yield('content') --}}


  <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/semantic.min.js') }}"></script>
  <script src="{{ asset('js/signature_pad.min.js') }}"></script>

  <script src="{{ asset('js/jquery.mask.js') }}"></script>
  <script src="{{ asset('js/hamburger.js') }}"></script>

  <script src="{{ asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>

</body>
<script>
  $(document).ready(function() {

    //DATEPICKER
    $('.datepicker_periode').datepicker({
        viewMode: 'years',
        format: 'yyyy-mm',
        minViewMode: "months",
        autoclose: true
    })

    // DATA TABLE
    $('.permintaan_cabang').DataTable({
      "order": [[ 12, "desc" ]]
    });

    $('.permintaan_ho').DataTable({
      "order": [[ 12, "desc" ]]
    });

    $('.permintaan').DataTable();

    $('.assembly').DataTable({
      "order": [[ 3, "asc" ]]
    });

    $('.managementExport').DataTable({
        "pageLength": 10,
        dom: 'Bfrtip',
        buttons: [
                {
                    text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                    action: function ( e, dt, node, config ) {
                        window.location = 'managementExport';
                    }
                }
        ],
    });

    $('.serviceExport').DataTable({
      "pageLength": 10,
      dom: 'Bfrtip',
      buttons: [
              {
                  text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                  action: function ( e, dt, node, config ) {
                      window.location = 'serviceExport';
                  }
              }
      ],
    });

    $('.lppExport').DataTable({
      "pageLength": 10,
      dom: 'Bfrtip',
      buttons: [
              {
                  text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                  action: function ( e, dt, node, config ) {
                      window.location = 'lppExport';
                  }
              }
      ],
    });

    $('.exportIns').DataTable({
      "pageLength": 10,
      dom: 'Bfrtip',
      buttons: [
              {
                  text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                  action: function ( e, dt, node, config ) {
                      window.location = 'transin/export_excel';
                  }
              }
      ],
    });

    $('.exportOuts').DataTable({
      "pageLength": 10,
      dom: 'Bfrtip',
      buttons: [
              {
                  text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                  action: function ( e, dt, node, config ) {
                      window.location = 'transout/export_excel';
                  }
              }
      ],
    });

    $('.exportLents').DataTable({
      "pageLength": 10,
      dom: 'Bfrtip',
      buttons: [
              {
                  text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                  action: function ( e, dt, node, config ) {
                      window.location = 'peminjaman/export_excel';
                  }
              }
      ],
    });

    $('.exportServices').DataTable({
      "pageLength": 10,
      dom: 'Bfrtip',
      buttons: [
              {
                  text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                  action: function ( e, dt, node, config ) {
                      window.location = 'service/export_excel';
                  }
              }
      ],
    });

    $('.exportAlokasi').DataTable({
      "pageLength": 10,
      dom: 'Bfrtip',
      buttons: [
              {
                  text: 'Export All To Excel <i class="file alternate outline icon"></i>',
                  action: function ( e, dt, node, config ) {
                      window.location = 'alokasi/export_excel';
                  }
              }
      ],
    });

    $('.example').DataTable({
      "pageLength": 10,
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
                  titleAttr: 'PDF',
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
      ],

    });
      
    $('#sho_barang').DataTable({
      "order": [[ 0, "asc" ]]
    });

    $('#examples').DataTable({
      "order": [[ 1, "asc" ]]
    });

    $('#tableDocno').DataTable({
      "order": [[ 2, "asc" ]]
    });
    
    // MOBILE VIEW HAMBURGER MENU
    $('.ui.sidebar').sidebar({context: $('.bottom.segment')}).sidebar('attach events', '.mobile');

    //AUTO COMPLETE AND MASKING INPUT
    $('input').attr('autocomplete','off');
    $('.prompt.mixed').mask('000000000Z | SSSRRRRRRRRRRRRR RRRRRRRRRRRRRRRRR RRRRRRRRRRRRRRRRR RRRRRRRRRRRRR', {
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

    //AVOID NEGATIVE VALUE INPUT
    var neg = $(".negative");
    if(neg.length > 0){
      $('.btnGo').addClass("disabled");
    }

    //USER ROLE MENU
    if({{ session('role') }} == 4){ //role 4 AWHOST
      $('.BarangMasuk').addClass("disabled");
      $('.GO').addClass("disabled");
      $('.ServiceHO').addClass("disabled");
      $('.Approve').addClass("disabled");
      $('.Kategori').addClass("disabled");
      $('.Barang').addClass("disabled");
      $('.Alokasi').addClass("disabled");
      $('.AlokasiOut').addClass("disabled");
    }else if({{ session('role') }} == 5){ //role OPR
      $('.management').addClass("disabled");
      $('.BarangMasuk').addClass("disabled");
      $('.BarangKeluar').addClass("disabled");
      $('.Peminjaman').addClass("disabled");
      $('.GO').addClass("disabled");
      $('.Service').addClass("disabled");
      $('.ServiceHO').addClass("disabled");
      $('.Approve').addClass("disabled");
      $('.Alokasi').addClass("disabled");
    }else if({{ session('role') }} == 3){ //role 4 SERVICER
      $('.Approve').addClass("disabled");
      $('.AlokasiOut').addClass("disabled");
    }

    //POP UP JS
    $('.button').popup();

    //ACCORDION JS
    $('.ui.accordion').accordion();

  });

  /////////////////////////////////////////////////////
  //THIS SEGMEN JQUERY NO WAITING PAGE LOADING COMPLETE
  /////////////////////////////////////////////////////


  //TES AJA

  function format ( d ) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
      '<tr>'+
        '<td>Full name:</td>'+
        '<td>'+d.name+'</td>'+
        '</tr>'+
      '<tr>'+
        '<td>Extension number:</td>'+
        '<td>'+d.extn+'</td>'+
        '</tr>'+
      '<tr>'+
        '<td>Extra info:</td>'+
        '<td>And any further details here (images etc)...</td>'+
        '</tr>'+
      '</table>';
  }

  var table = $('#tesaja').DataTable( {
        "ajax": "objects.txt",
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "name" },
            { "data": "position" },
            { "data": "office" },
            { "data": "salary" }
        ],
        "order": [[1, 'asc']]
    } );
     
    // Add event listener for opening and closing details
    $('#example tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );




  //MODAL
  $('.basic.tutupan.modal').modal('setting', 'closable', false).modal('show');

  //OTHER
  $( ".mob_icons" ).click(function() {
    $( this ).children('i').toggleClass( 'plus minus green red' );   
  });
  
  $(".fold-table tr.view").on("click", function(){
    $(this).next(".fold").toggle();
  });

  $('.ui.dropdown').dropdown();

  $('.message .close').on('click', function() {
    $(this).closest('.message').transition('fade');
  });

  //AJAX SEARCH FROM DB
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
                    maxResults = 5
                ;

                if(index >= maxResults) {
                  return false;
                }

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

        $('.qty').prop('readonly', true);
      }else{
        $(".mac").prop('required',false);
        $(".mac").prop('disabled',true);

        $('.qty').prop('readonly', false);
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

  $('.ui.search.opr').search({
      apiSettings: {
          url: 'apis/Opr?q={query}'
      },
      fields: {
          results : 'results',
          title   : 'OPR',
          description   : 'deskripsi'
      },
      onSelect: function(result){
          console.log(result.id);
      },
      minCharacters: 0
  });

  //JIKA ADA PENGGANTIAN SPAREPART
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

  //AJAX SEARCH FROM DB
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

  $( ".btnSubmit" ).click(function() {
      $('#note2').val($('#note_tambahan').val());
  });

  //MODAL
  $(".note").click(function(){
    $('.ui.modal').modal('show');
    $('#invoice').val($(this).data("id"));
    $('#note').val($(this).data("note"));
  });

  $("#test").click(function(){
    $('.ui.modal').modal('show');
  });

  $(".ambil").click(function(){
    $('.ui.modal').modal('show');
    $('#invoice').val($(this).data("id"));
  });

  $(".upd_status").click(function(){
    $('.ui.modal').modal('show');
    $('#nomor_pp').val($(this).data("nomor_pp"));
    $('#barang_id').val($(this).data("barang_id"));
    $('#nama_barang').val($(this).data("nama_barang"));
  });

  $(".transIn_pp").click(function(){
    $('.ui.modal').modal('show');
    $('.docno_permintaan').val($(this).data("docno_permintaan"));
    $('.nomor_permintaan').val($(this).data("nomor_permintaan"));
    $('.barang_permintaan').val($(this).data("barang_permintaan"));
    $('.kode_barang_permintaan').val($(this).data("kode_barang_permintaan"));
    $('.kategori_barang_permintaan').val($(this).data("kategori_barang_permintaan"));
    $('.max_permintaan').attr('max',$(this).data("max_permintaan"));
    $('.qty_pb').val($(this).data("max_permintaan"));
    $('.note_barang_permintaan').val($(this).data("note_barang_permintaan"));
    $('.dept_permintaan').val($(this).data("dept_permintaan"))

    $('.max_permintaan').val(1);
    $('.pic').val('');
  });

  $(".so_acc").click(function(){
    $('.ui.modal.so').modal('show');
  });
  //SIGN PAD
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