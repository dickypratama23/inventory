@extends('layouts.semantic')
@section('title', 'Laporan Transaksi In')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Report</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Laporan Transaksi In</div>
</div>

<div class="ui divider"></div>

<div class="ui placeholder segment">

  <form class="ui form" action="{{ route('transin.filter') }}">
    <h4 class="ui dividing header">Filter</h4>
    <div class="fields">

      <div class="three wide field">
        <label>Periode</label>
        <div class="ui left icon input">
          <input type="text" value="{{ $PERIODE_FIL }}" placeholder="Pilih periode..." name="filter[periode]"
            data-plugin-datepicker class="datepicker_periode">
          <i class="calendar icon"></i>
        </div>
      </div>

      <div class="five wide field">
        <label>Dari</label>
        <div class="ui fluid category search department">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="Pilih Department / Toko / Supplier">
            <i class="search icon"></i>
          </div>
          <div class="results"></div>
        </div>
        <input type="hidden" name="filter[dari]" class="dept" readonly>
      </div>

      <div class="five wide field">
        <label>Barang</label>
        <div class="ui fluid category search barang">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="Pilih Barang">
            <i class="search icon"></i>
          </div>
          <div class="results"></div>
        </div>
        <input type="hidden" name="_method" value="PUT" class="form-control">
        <input type="hidden" name="filter[barang]" class="barang_id" readonly>
      </div>

    </div>

    <button type="submit" class="ui button" tabindex="0">Filter</button>
  </form>
</div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN TRANSAKSI IN</h2>
  </div>
  <div class="ui green segment">
    <table id="exssample" class="ui striped selectable celled  table fold-table exportIns" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Invoice</th>
          <th data-field="item">Dari</th>
          <th data-field="price">Item</th>
          <th data-field="price">Note</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($transaksi as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned collapsing">{{ $no++ }}</>
          <td class="collapsing">
            <ul class="ui list">
              <li>Invoice : {{ substr($row->invoice, -10) }}</li>
              <li>Tanggal : {{ $row->created_at->format('d/m/Y') }}</li>
              <li>Jam : {{ $row->created_at->format('H:i:s') }}</li>
            </ul>
          </td>

          <td class="collapsing">
            <ul class="ui list">
              <li>Dari : {{ $row->department->kdtk }}</li>
              <li>PIC : {{ $row->pic }}</li>
              <li>Pembuat : {{ $row->user->nik }} | {{ $row->user->name }}</li>
            </ul>
          </td>

          <td class="collapsing">

            <ul class="ui list">
              @foreach ($row->detail as $detail)
              <li>
                {{ $detail->barang->name }} ({{ $detail->qty }} item)
                {{ $detail->Serial_number ? "S/N : (" . $detail->Serial_number . ")" : "" }}
              </li>
              @endforeach
            </ul>





          </td>
          <td>{{ $row->note }}</td>
          <td class="collapsing center aligned">
            <a href="{{ route('transin.print', $row->id) }}" target="_blank" class="ui teal icon button">
              <i class="print icon"></i>
            </a>

            @if($row->lampiran)
            <a href="{{ url('storage/photo_sj/' . $row->lampiran) }}" target="_blank" class="ui grey icon button">
              <i class="paperclip icon"></i>
            </a>
            @else
            <button class="ui violet icon button upload_sj" data-id-transaksi="{{ $row->id }}">
              <i class="upload icon"></i>
            </button>
            @endif

          </td>
        </tr>
        @empty
        <tr>
          <td class="center aligned" colspan="6">Tidak ada data</td>
        </tr>
        @endforelse

      </tbody>
    </table>
  </div>
</div>

<div class="ui mini modal">
  <i class="close icon"></i>
  <div class="header">
    Pilih Scan Surat Jalan Untuk Di Upload
  </div>
  <div class="content">
    <form action="{{ route('transin.upload') }}" method="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="description">

        <div class="twelve wide field">
          <div class="ui action input">
            <input type="hidden" id="id-transaksi" name="id_transaksi">
            <input type="text" id="_attachmentName">
            <label for="attachmentName" class="ui icon button btn-file">
              <i class="upload icon"></i>
              <input type="file" id="attachmentName" name="attachmentName" style="display: none">
            </label>
          </div>
        </div>

      </div>
  </div>

  <div class="actions">
    <div class="ui black deny button">
      Keluar
    </div>
    <button class="ui positive right labeled icon button">
      Upload
      <i class="checkmark icon"></i>
    </button>
    </form>
  </div>
</div>

@endsection