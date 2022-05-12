<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Web Stock | List Sign</title>
  <link type="text/css" rel="stylesheet" href="{{ asset('css/semantic.css') }}" media="screen,projection" />
  <link type="text/css" rel="stylesheet" href="{{ asset('DataTables/jquery.dataTables.min.css') }}"
    media="screen,projection" />
</head>

<body>

  <br>

  <div class="ui container">
    <div class="ui one cards">
      @forelse ($transaksi as $row)

      @if(substr($row->invoice,0,3) == 'LEN')

      <a href="{{ route('Sign', $row->id) }}" class="ui card">
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="meta">
            <span class="category">{{ $row->note }}</span> <br>
            <span class="category">{{ $row->created_at }}</span> <br>
            <span class="category">{{ $row->detail->count() }} item</span>
          </div>
        </div>
        <div class="extra content">
          <span class="left floated like">
            {{ $row->department->kdtk }}
          </span>
          <span class="right floated star">
            {{ $row->pic }}
          </span>
        </div>
      </a>

      @elseif(substr($row->invoice,0,6) == 'IN/BTM')

      @if($row->status == 1)
      <a href="{{ route('Sign', $row->id) }}" class="ui card">
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="meta">
            <span class="category">{{ $row->note }}</span> <br>
            <span class="category">{{ $row->created_at }}</span> <br>
            <span class="category">{{ $row->detail->count() }} item</span>
          </div>
        </div>
        <div class="extra content">
          <span class="left floated like">
            {{ $row->department->kdtk }}
          </span>
          <span class="right floated star">
            {{ $row->pic }}
          </span>
        </div>
      </a>
      @endif

      @elseif(substr($row->invoice,0,3) == 'OUT')

      @if($row->status == 2)
      <a href="{{ route('Sign', $row->id) }}" class="ui card">
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="meta">
            <span class="category">{{ $row->note }}</span> <br>
            <span class="category">{{ $row->created_at }}</span> <br>
            <span class="category">{{ $row->detail->count() }} item</span>
          </div>
        </div>
        <div class="extra content">
          <span class="left floated like">
            {{ $row->department->kdtk }}
          </span>
          <span class="right floated star">
            {{ $row->pic }}
          </span>
        </div>
      </a>
      @endif

      @elseif(substr($row->invoice,0,3) == 'BAP')

      @if($row->status == 2)
      <a href="{{ route('Sign', $row->id) }}" class="ui card">
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="meta">
            <span class="category">{{ $row->note }}</span> <br>
            <span class="category">{{ $row->created_at }}</span> <br>
            <span class="category">{{ $row->detail->count() }} item</span>
          </div>
        </div>
        <div class="extra content">
          <span class="left floated like">
            {{ $row->department->kdtk }}
          </span>
          <span class="right floated star">
            {{ $row->pic }}
          </span>
        </div>
      </a>
      @endif

      @elseif(substr($row->invoice,0,6) == 'SERV/O')

      @if($row->pic != 'NIK - NAMA PERSONIL TOKO')
      <a href="{{ route('Sign', $row->id) }}" class="ui card">
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="meta">
            <span class="category">{{ $row->note }}</span> <br>
            <span class="category">{{ $row->created_at }}</span> <br>
            <span class="category">{{ $row->detail->count() }} item</span>
          </div>
        </div>
        <div class="extra content">
          <span class="left floated like">
            {{ $row->department->kdtk }}
          </span>
          <span class="right floated star">
            {{ $row->pic }}
          </span>
        </div>
      </a>
      @endif

      @elseif(substr($row->invoice,0,3) == 'ALO')
      @if($row->status == 2)
      <a href="{{ route('Sign', $row->id) }}" class="ui card">
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="meta">
            <span class="category">{{ $row->note }}</span> <br>
            <span class="category">{{ $row->created_at }}</span> <br>
            <span class="category">{{ $row->detail->count() }} item</span>
          </div>
        </div>
        <div class="extra content">
          <span class="left floated like">
            {{ $row->department->kdtk }}
          </span>
          <span class="right floated star">
            {{ $row->pic }}
          </span>
        </div>
      </a>
      @endif

      @endif
      @empty
      <div class="ui positive message">
        <div class="header">
          No Data To Sign
        </div>
      </div>
      @endforelse
    </div>





    <div class="ui divided selection list">
      @forelse ($transaksi as $row)

      @if(substr($row->invoice,0,3) == 'LEsN')






      <a href="{{ route('Sign', $row->id) }}" class="item">
        <i class="file icon"></i>
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="description">{{ $row->note }}</div>
          <div class="list">

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Dari : {{ $row->department->kdtk }} - {{ $row->department->name }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Tanggal : {{ $row->created_at }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Item : {{ $row->detail->count() }} item</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Pembuat : {{ $row->user->nik }} - {{ $row->user->name }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Penerima : {{ $row->pic }}</div>
              </div>
            </div>
          </div>
        </div>
      </a>

      @elseif(substr($row->invoice,0,6) == 'SEdRV/O')
      @if($row->pic != 'NIK - NAMA PERSONIL TOKO')
      <a href="{{ route('Sign', $row->id) }}" class="item">
        <i class="file icon"></i>
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="description">{{ $row->note }}</div>
          <div class="list">

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Dari : {{ $row->department->kdtk }} - {{ $row->department->name }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Tanggal : {{ $row->created_at }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Item : {{ $row->detail->count() }} item</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Pembuat : {{ $row->user->nik }} - {{ $row->user->name }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Penerima : {{ $row->pic }}</div>
              </div>
            </div>
          </div>
        </div>
      </a>
      {{-- {{ $row->pic }} --}}
      @endif
      @elseif(substr($row->invoice,0,3) == 'ALaO')

      <a href="{{ route('Sign', $row->id) }}" class="item">
        <i class="file icon"></i>
        <div class="content">
          <div class="header">{{ $row->invoice }}</div>
          <div class="description">{{ $row->note }}</div>
          <div class="list">

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Dari : {{ $row->department->kdtk }} - {{ $row->department->name }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Tanggal : {{ $row->created_at }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Item : {{ $row->detail->count() }} item</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Pembuat : {{ $row->user->nik }} - {{ $row->user->name }}</div>
              </div>
            </div>

            <div class="item">
              <i class="linkify icon"></i>
              <div class="content">
                <div class="header">Penerima : {{ $row->pic }}</div>
              </div>
            </div>
          </div>
        </div>
      </a>

      @elseif(substr($row->invoice,0,3) == 'LEN')

      @elseif(substr($row->invoice,0,6) == 'SERV/O')
      @if($row->detail->count() > 0)

      @php
      foreach($row->detail as $det){
      $app = $det->approve;
      }
      @endphp

      @else
      @endif
      @endif
      @empty
      <div class="ui positive message">
        <div class="header">
          No Data To Sign
        </div>
      </div>
      @endforelse
    </div>

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

  if (signaturePad.isEmpty()) {
   alert('Draw Your Signature');
  } else {
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