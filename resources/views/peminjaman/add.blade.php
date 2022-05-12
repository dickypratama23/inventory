@extends('layouts.semantic')
@section('title', 'Transaksi Peminjaman | Add barang')
@section('content')



<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Transaksi</a>
  <i class="right angle icon divider"></i>
  <a class="section">Peminjaman</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Tambah Barang</div>
</div>

<div class="ui divider"></div>

<div class="ui right aligned disabled header">
  <h2>SURAT JALAN PEMINJAMAN</h2>
  No. {{ $transaksi->invoice }} | Tanggal : {{ $transaksi->created_at->format('d-m-Y') }}
</div>

<div class="ui tall stacked segment">
  
    <div class="ui stackable grid">
        <div class="nine wide column">
            <table class="ui celled padded table">
                <thead>
                    <tr><th class="single line">To :</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="ui list">
                              <div class="item">
                                <i class="shopping cart icon"></i>
                                <div class="content">
                                  <div class="header">{{ $transaksi->department->kdtk }} - {{ $transaksi->department->name }}</div>
                                  <div class="description">{{ $transaksi->pic }}</div>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="seven wide column">
            <table class="ui celled padded table">
                <thead>
                    <tr><th class="single line">From :</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="ui list">
                              <div class="item">
                                <i class="suitcase icon"></i>
                                <div class="content">
                                  <div class="header">EDP - Elektronic Data Processing</div>
                                  <div class="description">{{ session('nik').' | '.session('nama') }}</div>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <form class="ui form" action="{{ route('peminjaman.update', ['id' => $transaksi->id]) }}" method="post">
      {{ csrf_field() }}

      <h3 class="ui block inverted blue header">
        PEMINJAMAN SEBELUMNYA (BELUM KEMBALI)
      </h3>

      <table class="ui compact celled inverted blue table">
        <thead>
            <tr>
                <th class="single line one wide">No</th>
                <th class="four wide">Barang</th>
                <th class="three wide">Serial Number / Mac</th>
                <th class="four wide">Tanggal</th>
                <th class="two wide">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1 @endphp
            @forelse ($cads as $cad)
            <tr>
                <td class="right aligned">{{ $no++ }}</td>
                <td>
                    {{ $cad->name }}
                </td>
                <td>{{ $cad->mac }}</td>
                <td>{{ $cad->created_at->format('d/m/Y H:i:s') }}</td>
                <td>
                    <a href="{{ route('cad.return', ['id' => $cad->id]) }}" class="fluid ui green button">Return</a>
                </td>
            </tr>
            @empty
            <tr>
                <td class="center aligned" colspan="6">TIDAK ADA PEMINJAMAN</td>
            </tr>
            @endforelse
        </tbody>
        
    </table>


      <h3 class="ui block header">
        PEMINJAMAN SEKARANG
      </h3>

    <table class="ui compact celled table">
        <thead>
            <tr>
                <th class="single line">No</th>
                <th class="four wide">Barang</th>
                <th class="two wide">Unit</th>
                <th class="three wide">Serial Number / Mac</th>
                <th class="four wide">Note</th>
                <th class="two wide">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1 @endphp
            @foreach ($transaksi->detail as $detail)
            <tr>
                <td class="right aligned">{{ $no++ }}</td>
                <td>
                    {{ $detail->cad->name }}
                </td>
                <td class="right aligned">{{ $detail->qty }}</td>
                <td>{{ $detail->Serial_number }}</td>
                <td>{{ $detail->note }}</td>
                <td>
                    <a href="{{ route('transout.delete_barang', ['id' => $detail->id]) }}" class="fluid ui red button">Hapus</a>
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
                        <input type="number" min="1" value="1" name="qty" readonly>
                    </div>
                </th>

                <th>
                    <div class="field">
                        <input type="text" placeholder="SN / Mac Address" class="mac_lent" name="serial_number" readonly>
                    </div>
                </th>

                <th>
                    <div class="field">
                        <input type="text" placeholder="Buat Catatan Jika Ada" name="note">
                    </div>
                </th>

                <th>
                    <button class="fluid ui teal button" type="submit">Tambah</button>
                </th>
            </tr>
        </tfoot>
    </table>
    </form>

    <br>
    <form action="{{ route('peminjaman.selesai', ['id' => $transaksi->id]) }}" method="post">
    {{ csrf_field() }}
        <button class="ui positive button" type="submit">Selesai</button>
    </form>
</div>






@endsection