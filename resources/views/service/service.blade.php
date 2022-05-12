@extends('layouts.semantic')
@section('title', 'Transaksi Peminjaman | Add barang')
@section('content')



<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Transaksi</a>
  <i class="right angle icon divider"></i>
  <a class="section">Service</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Penggantian</div>
</div>

<div class="ui divider"></div>

<div class="ui right aligned disabled header">
  <h2>SERVICE</h2>
  No. {{ $transaksi->invoice }} | Tanggal : {{ $transaksi->created_at->format('d-m-Y') }}
</div>

<div class="ui tall stacked segment">
  
    <div class="ui stackable grid">
        <div class="ten wide column">
            <table class="ui celled padded table">
                <thead>
                    <tr><th class="single line">Detail Masuk :</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="ui middle aligned list">
                                <div class="item" >
                                    <div class="header"><i class="file alternate outline icon"></i> <div class="ui basic label">{{ $transaksi_ser_in->invoice }}</div></div>
                                </div>

                                <div class="item">
                                    <div class="header"><i class="user icon"></i> <div class="ui basic label">{{ $transaksi_ser_in->pic }}</div></div>
                                </div>

                                <div class="item">
                                    <div class="header"><i class="calendar icon"></i> <div class="ui basic label">{{ $transaksi_ser_in->created_at->format('d F Y') }}</div></div>
                                </div>

                                <div class="item">
                                    <div class="header"><i class="clock icon"></i> <div class="ui basic label">{{ $transaksi_ser_in->created_at->format('H:i:s') }}</div></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="six wide column">
            <table class="ui celled padded table">
                <thead>
                    <tr><th class="single line">Detail Keluar:</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="ui middle aligned list">
                                <div class="item" >
                                    <div class="header"><i class="file alternate outline icon"></i> <div class="ui basic label">{{ $transaksi->invoice }}</div></div>
                                </div>

                                <div class="item">
                                    <div class="header"><i class="calendar icon"></i> <div class="ui basic label">{{ $transaksi->created_at->format('d F Y') }}</div></div>
                                </div>

                                <div class="item">
                                    <div class="header"><i class="clock icon"></i> <div class="ui basic label">{{ $transaksi->created_at->format('H:i:s') }}</div></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <h3 class="ui header">
        <i class="cog icon"></i>
        <div class="content">
            Barang Service
        </div>
    </h3>

    <form class="ui form" action="{{ route('peminjaman.update', ['id' => $transaksi->id]) }}" method="post">
      {{ csrf_field() }}

    <table class="ui compact celled table">
        <thead>
            <tr>
                <th class="single line">No</th>
                <th class="four wide">Barang</th>
                <th class="one wide">Unit</th>
                <th class="four wide">Serial Number / Mac</th>
                <th class="six wide">Masalah</th>
                
            </tr>
        </thead>
        <tbody>
            @php $no = 1 @endphp
            {{-- @foreach ($transaksi_ser_in->detail as $detail) --}}
            <tr>
                <td class="right aligned">{{ $no++ }}</td>
                <td>
                    {{ $barang_service->barang->kode }} - {{ $barang_service->barang->name }}
                </td>
                <td class="right aligned">{{ $barang_service->qty }}</td>
                <td>{{ $barang_service->Serial_number }}</td>
                <td>{{ $barang_service->note }}</td>
            </tr>
            {{-- @endforeach --}}
        </tbody>
        
    </table>

    <div class="field">
        <label>Keterangan (optional)</label>
        <input type="text" id="note_tambahan" placeholder="Masukkan Keterangan Jika Ada Keterangan Tambahan">
    </div>
    
    </form>

    <br>

    
    


    <div class="ui toggle checkbox">
        <input class="check_penggantian" type="checkbox" name="penggantian" value="1">
        <label>Ada Penggantian</label>
    </div>

    <br><br><br>

    <div class="penggantian">
        <h3 class="ui header">
            <i class="cog icon"></i>
            <div class="content">
                Pergantian Spare Part
            </div>
        </h3>
        <form class="ui form" action="{{ route('service.update', ['id' => $transaksi->id]) }}" method="post">
            {{ csrf_field() }}

            <table class="ui compact celled table">
                <thead>
                    <tr>
                        <th class="single line">No</th>
                        <th class="ten wide">Barang</th>
                        <th class="three wide">Unit</th>
                        <th class="two wide">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1 @endphp
                    @foreach ($transaksi->detail as $detail)
                    <tr class="sp_table">
                        <td class="right aligned">{{ $no++ }}</td>
                        <td>
                            {{ $detail->barang->kode }} - {{ $detail->barang->name }}
                        </td>
                        <td class="right aligned">{{ $detail->qty }}</td>
                        <td>
                            <a href="{{ route('service.delete_barang', ['id' => $detail->id]) }}" class="fluid ui red button">Hapus</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>
                            <div class="field">
                                <div class="ui left aligned category search barang">
                                <div class="ui icon input">
                                    <input class="prompt" type="text" placeholder="Pilih Barang">
                                    <i class="search icon"></i>
                                </div>
                                <div class="results"></div>
                                </div>
                                <input type="hidden" name="_method" value="PUT" class="form-control">
                                <input type="hidden" name="barang_id" class="barang_id" readonly>
                            </div>
                        </th>

                        <th>
                            <div class="field">
                                <input type="number" min="1" value="1" name="qty">
                            </div>
                        </th>

                        <th>
                            <button class="fluid ui teal button" type="submit">Tambah</button>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>

    

    

    <br>
    <form action="{{ route('service.selesai', [ 'id' => $transaksi->id, 'kode' => $barang_service->id ]) }}" method="post">
    {{ csrf_field() }}
        <input type="hidden" class="ada_pengg" name="ada_pengg" value="0">
        <input type="hidden" id="note2" name="note_tambahan" readonly>
        <button class="ui positive button btnSubmit" type="submit" >Selesai</button>
    </form> 
</div>







@endsection