@extends('layouts.semantic')
@section('title', 'Permintaan Pembelian')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">PP Cab/HO</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Status</div>
</div>
<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>STATUS PERMINTAAN PEMBELIAN CABANG</h2>
  </div>

  <div class="ui green segment">
    <table class="ui celled table responsive nowrap unstackable permintaan" style="width:100%">
      <thead class="full-width">
        <tr>
          <th class="center aligned">#</th>
          <th>Nomor</th>
          <th>Barang</th>
          <th>PB</th>
          <th>Min</th>
          <th>Note</th>
          <th>Status</th>
          <th>Proses</th>
          <th>Realisasi</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($dp_cabang as $index => $barang)
        <tr>
          <td class="collapsing">{{ $index + 1 }}</td>
          <td class="collapsing">{{ $barang->cabang }}</td>
          <td class="collapsing">{{ $barang->barang->name }}</td>
          <td class="right aligned positive collapsing">{{ $barang->qty }}</td>
          <td class="right aligned collapsing">{{ $barang->qty - $barang->minus }}</td>
          <td>{{ $barang->note }}</td>
          <td class="center aligned collapsing">{{ $barang->status }}</td>
          <td class="center aligned collapsing">{{ $barang->proses }}</td>
          <td class="center aligned collapsing">{{ $barang->realisasi }}</td>
          <td class="center aligned collapsing">
            @if($barang->status != 1)
            <button class="ui red icon transIn_pp button" data-content="Terima Barang"
              data-docno_permintaan="{{ $barang->auto }}" data-nomor_permintaan="{{ strtotime(date('his')) }}"
              data-barang_permintaan="{{ $barang->barang->name }}"
              data-kode_barang_permintaan="{{ $barang->barang->kode }}"
              data-kategori_barang_permintaan="{{ $barang->barang->kategori->name }}"
              data-max_permintaan="{{ $barang->qty - $barang->minus }}"
              data-note_barang_permintaan="{{ $barang->note }}" data-dept_permintaan="GA - General Affairs">
              <i class="clipboard icon"></i>
            </button>
            @endif

            @if($barang->so != 1 && $barang->status == 1)
            <div class="ui violet icon button so_acc" data-content="Sudah SO Accounting"
              data-docno_permintaan="{{ $barang->auto }}" data-kode_barang_permintaan="{{ $barang->barang->kode }}"
              data-kategori_barang_permintaan="{{ $barang->barang->kategori->name }}">
              <i class="check icon"></i>
            </div>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<br>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>STATUS PERMINTAAN PEMBELIAN HO</h2>
  </div>

  <div class="ui green segment">
    <table class="ui celled table responsive nowrap unstackable permintaan" style="width:100%">
      <thead class="full-width">
        <tr>
          <th class="center aligned">#</th>
          <th>Nomor</th>
          <th>Barang</th>
          <th>PB</th>
          <th>Min</th>
          <th>Note</th>
          <th>Status</th>
          <th>Proses</th>
          <th>Realisasi</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($dp_ho as $index => $barang)
        <tr>
          <td class="collapsing">{{ $index + 1 }}</td>
          <td class="collapsing">{{ $barang->cabang }}</td>
          <td class="collapsing">{{ $barang->barang->name }}</td>
          <td class="right aligned positive collapsing">{{ $barang->qty }}</td>
          <td class="right aligned collapsing">{{ $barang->qty - $barang->minus }}</td>
          <td>{{ $barang->note }}</td>
          <td class="center aligned collapsing">{{ $barang->status }}</td>
          <td class="center aligned collapsing">{{ $barang->proses }}</td>
          <td class="center aligned collapsing">{{ $barang->realisasi }}</td>
          <td class="center aligned collapsing">
            @if($barang->status != 1)
            <button class="ui red icon transIn_pp button" data-docno_permintaan="{{ $barang->auto }}"
              data-nomor_permintaan="{{ strtotime(date('his')) }}" data-barang_permintaan="{{ $barang->barang->name }}"
              data-kode_barang_permintaan="{{ $barang->barang->kode }}"
              data-kategori_barang_permintaan="{{ $barang->barang->kategori->name }}"
              data-max_permintaan="{{ $barang->qty - $barang->minus }}"
              data-note_barang_permintaan="{{ $barang->note }}" data-dept_permintaan="HO - HEAD OFFICE">
              <i class="clipboard icon"></i>
            </button>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<br>

<div class="ui small modal">
  <i class="close icon"></i>
  <div class="header">
    Transaksi Barang Masuk
  </div>
  <div class="content">
    <div class="description">
      <form action="{{ route('lpp.baru.proses') }}" method="POST">
        {{ csrf_field() }}
        <div class="ui tiny form">
          <div class="two fields">
            <div class="eight wide field">
              <label>Docno</label>
              <input class="docno_permintaan" type="text" name="docno_permintaan" readonly>
            </div>
            <div class="three wide field">
              <label>Nomor Proses</label>
              <input class="nomor_permintaan" type="text" name="nomor_permintaan" readonly>
            </div>
          </div>

          <div class="two fields">
            <div class="four wide field">
              <label>Dari</label>
              <input type="text" class="dept_permintaan" readonly>
            </div>
            <div class="six wide field error">
              <label>Pic</label>
              <input type="text" class="pic" name="pic" autofocus>
            </div>
          </div>

          <div class="two fields">
            <div class="six wide field">
              <label>Barang</label>
              <input class="barang_permintaan" type="text" readonly>
            </div>
            <div class="two wide field">
              <label>Max Terima</label>
              <input class="qty_pb" type="number" name="qty_pb" readonly>
            </div>
            <div class="two wide field error">
              <label>Qty</label>
              <input class="max_permintaan" type="number" name="qty_terima" min="1" value="1">
            </div>
            <div class="two wide field">
              <label>Kode</label>
              <input class="kode_barang_permintaan" type="text" name="kode_barang_permintaan" readonly>
            </div>
            <div class="four wide field">
              <label>Kategori</label>
              <input class="kategori_barang_permintaan" type="text" readonly>
            </div>
          </div>

          <div class="two fields">
            <div class="ten wide field">
              <label>Note (optional)</label>
              <input class="note_barang_permintaan" type="text" name="note">
            </div>
          </div>

        </div>

    </div>
  </div>

  <div class="actions">
    <div class="ui black deny button">
      Keluar
    </div>
    <button class="ui positive right labeled icon button">
      Terima Barang
      <i class="checkmark icon"></i>
    </button>
    </form>
  </div>
</div>

{{-- <div class="ui small modal so">
  <i class="close icon"></i>
  <div class="header">
    Transaksi Barang Masukssss
  </div>
  <div class="content">
    <div class="description">
      <form action="{{ route('lpp.baru.proses') }}" method="POST">
{{ csrf_field() }}
<div class="ui tiny form">
  <div class="two fields">
    <div class="eight wide field">
      <label>Docno</label>
      <input class="docno_permintaan" type="text" name="docno_permintaan" readonly>
    </div>
  </div>

  <div class="two fields">
    <div class="six wide field">
      <label>Barang</label>
      <input class="barang_permintaan" type="text" readonly>
    </div>
    <div class="two wide field">
      <label>Max Terima</label>
      <input class="qty_pb" type="number" name="qty_pb" readonly>
    </div>
    <div class="two wide field error">
      <label>Qty</label>
      <input class="max_permintaan" type="number" name="qty_terima" min="1" value="1">
    </div>
    <div class="two wide field">
      <label>Kode</label>
      <input class="kode_barang_permintaan" type="text" name="kode_barang_permintaan" readonly>
    </div>
    <div class="four wide field">
      <label>Kategori</label>
      <input class="kategori_barang_permintaan" type="text" readonly>
    </div>
  </div>

  <div class="two fields">
    <div class="ten wide field">
      <label>Note (optional)</label>
      <input class="note_barang_permintaan" type="text" name="note">
    </div>
  </div>

</div>

</div>
</div>

<div class="actions">
  <div class="ui black deny button">
    Keluar
  </div>
  <button class="ui positive right labeled icon button">
    Terima Barang
    <i class="checkmark icon"></i>
  </button>
  </form>
</div>
</div> --}}



@endsection