<!DOCTYPE html>
  <html>
    <head>
        <title></title>
      <!--Import Google Icon Font-->
      
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="{{ asset('css/materialize.css') }}"  media="screen,projection"/>
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      
    </head>
    
    
    <body>
        <nav>
            <div class="nav-wrapper teal darken-3 ">
                <a href="#" data-target="slide-out" class="button-collapse sidenav-trigger show-on-large "><i class="material-icons">menu</i></a>
                <a href="#" class="brand-logo">Website Stock</a>
            </div>
        </nav>

        <div class="container">
            @yield('content')
        </div>
        
        
            
    <!--Side Nav-->
        <ul id="slide-out" class="sidenav">
            <li>
                <div class="user-view">
                    <div class="background">
                        <img src="{{ asset('images/office.jpg') }}">
                    </div>
                    <a href="#user"><img class="circle" src="{{ asset('images/yuna.jpg') }}"></a>
                    <a href="#name"><span class="white-text name">John Doe</span></a>
                    <a href="{{ route('logout') }}"><span class="white-text email">logout</span></a>
                    
                </div>
            </li>

            <!--<li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header">Master & Query<i class="material-icons">arrow_drop_down</i></a>
                        <div class="collapsible-body">
                        <ul>
                            <li><a href="#!">Tambah Kategori</a></li>
                            <li><a href="#!">Tambah Barang</a></li>
                        </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header">Transaksi<i class="material-icons">arrow_drop_down</i></a>
                        <div class="collapsible-body">
                        <ul>
                            <li><a href="#!">Barang masuk</a></li>
                            <li><a href="#!">Barang Keluar</a></li>
                            <li><a href="#!">Peminjaman</a></li>
                        </ul>
                        </div>
                    </li>
                </ul>
            </li>-->
            <li><a class="subheader">Master & Query</a></li>
            <li><a href="{{ url('/kategori') }}">Manajemen Kategori</a></li>
            <li><a href="{{ url('/barang') }}">Manajemen Barang</a></li>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Transaksi</a></li>
            <li><a href="{{ url('/transin/new') }}">Barang masuk</a></li>
            <li><a href="{{ url('/transout/new') }}">Barang Keluar</a></li>
            <li><a href="#!">Peminjaman</a></li>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Approval</a></li>
            <li><a href="{{ url('/approval') }}">Approve</a></li>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Report</a></li>
            <li><a href="{{ url('/transin') }}">Laporan Trans In</a></li>
            <li><a href="{{ url('/transout') }}">Laporan Trans Out</a></li>
            <li><a href="{{ url('/lpp') }}">Laporan Permutasi Persedian</a></li>
            
            
            
            <br><br><br><br><br>
        </ul>
    <!--End Side Nav-->

    <!--Short Cut Menu-->
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal darken-3 pulse">
          <i class="large material-icons">add</i>
        </a>
        <ul>
          <li><a class="btn-floating green tooltipped" data-position="top" data-tooltip="Transaksi In"><i class="material-icons">get_app</i></a></li>
          <li><a class="btn-floating red darken-1 tooltipped" data-position="top" data-tooltip="Transaksi Out"><i class="material-icons">publish</i></a></li>
          <li><a class="btn-floating orange tooltipped" data-position="top" data-tooltip="Peminjaman"><i class="material-icons">compare_arrows</i></a></li>
        </ul>
    </div>

    <!--End Short Cut Menu-->
            
            
      <!--JavaScript at end of body for optimized loading-->
      <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('js/materialize.min.js') }}"></script>
      
      
    </body>
    <script>
        $(document).ready(function(){
            $('.sidenav').sidenav();
            $('.collapsible').collapsible();
            $('.fixed-action-btn').floatingActionButton({
                direction: 'left',
                hoverEnabled: false,
            });
            $('.tooltipped').tooltip();

            $('.tap-target').tapTarget();
            $('.modal').modal();
            $('select').formSelect();


            













            
        }); 



        $(function(){
                $(".fold-table tr.view").on("click", function(){
                    //$(this).toggleClass("open").next(".fold").toggleClass("open");
                    
                    
                    $(this).next(".fold").toggle();
                    
                    //alert(123);
                });
            });

        

    @if (session('kat_success'))
        var toastHTML = '{{ session('kat_success') }}';
        M.toast({html: toastHTML});
    @elseif(session('kat_error'))
        var toastHTML = '{{ session('kat_error') }}';
        M.toast({html: toastHTML});
    @endif

    @if (session('bar_success'))
        var toastHTML = '{{ session('bar_success') }}';
        M.toast({html: toastHTML});
    @elseif(session('bar_error'))
        var toastHTML = '{{ session('bar_error') }}';
        M.toast({html: toastHTML});
    @endif
    </script>
  </html>