@extends('layouts.semantic')
@section('title', 'Laporan Transaksi Peminjaman')
@section('content')


<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Report</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Laporan Transaksi Peminjaman</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN TRANSAKSI PEMINJAMAN</h2>
  </div>
  <div class="ui green segment">
    <table id="examsssple" class="ui striped selectable celled table fold-table exportLents" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="action">No.</th>
          <th data-field="item">Invoice</th>
          <th data-field="item">Ke</th>
          <th data-field="price">Item</th>
          <th data-field="price">Note</th>
          <th data-field="action">Action</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1 @endphp
        @forelse ($transaksi as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned collapsing">{{ $no++ }}</td>
          <td class="collapsing">
            <ul class="ui list">
              <li>Invoice : {{ substr($row->invoice, -10) }}</li>
              <li>Tanggal : {{ $row->created_at->format('d/m/Y') }}</li>
              <li>Jam : {{ $row->created_at->format('H:i:s') }}</li>
            </ul>
          </td>

          <td class="collapsing">
            <ul class="ui list">
              <li>Ke : {{ $row->department->kdtk }}</li>
              <li>PIC : {{ $row->pic }}</li>
              <li>Pembuat : {{ $row->user->nik }} | {{ $row->user->name }}</li>
              <li>Approve : @foreach ($row->detail as $detail) @endforeach {{ $detail->user->nik }} |
                {{ $detail->user->name }}</li>
            </ul>
          </td>

          <td class="collapsing">
            <ol class="ui list">
              @foreach ($row->detail as $detail)
              <li value="*">
                {{ $detail->cad->name }}
                <ol>
                  <li value="-">S/N : <strong>{{ $detail->Serial_number }}</strong></li>
                </ol>
              </li>
              @endforeach
            </ol>
          </td>
          <td>{{ $row->note }}</td>
          <td class="collapsing center aligned">
            @if($row->sign == '' )
            <a href="{{ route('peminjaman.sign', $row->id) }}" class="ui teal icon button">
              <i class="icons">
                <i class="pencil alternate icon"></i>
                <i class="file alternate outline icon"></i>
              </i>
            </a>
            @else
            @if($row->status == 2)
            <a href="{{ route('peminjaman.print', $row->id) }}" target="_blank" class="ui teal icon button">
              <i class="print icon"></i>
            </a>
            @endif

            @if($row->lampiran)
            <a href="{{ url('storage/photo_sj/' . $row->lampiran) }}" target="_blank" class="ui grey icon button">
              <i class="paperclip icon"></i>
            </a>
            @else
            <button class="ui violet icon button upload_sj" data-id-transaksi="{{ $row->id }}">
              <i class="upload icon"></i>
            </button>
            @endif

            @endif
          </td>
        </tr>

        @empty
        <tr>
          <td class="center aligned" colspan="9">Tidak ada data</td>
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
    <form action="{{ route('peminjaman.upload') }}" method="POST" enctype="multipart/form-data">
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