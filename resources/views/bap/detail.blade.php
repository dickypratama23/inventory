@extends('layouts.semantic')
@section('title', 'Laporan Transaksi In')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Report</a>
  <i class="right angle icon divider"></i>
  <a href="{{ url('/bap') }}" class="section">Laporan BAP</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Detail</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN BAP</h2>
  </div>
  <div class="ui green segment">
    <table id="examples" class="ui striped selectable celled  table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">Kode</th>
          <th data-field="item">Barang</th>
          <th data-field="item">Serial Number</th>
          <th data-field="item">Keterangan</th>
          <th data-field="price">Qty</th>
          <th data-field="price">Buat</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($bap->detail as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="collapsing">{{ $row->barang->kode }}</td>
          <td class="collapsing">{{ $row->barang->name }}</td>
          <td class="collapsing">{{ $row->Serial_number }}</td>
          <td>{{ $row->note }}</td>
          <td class="right aligned collapsing">{{ $row->qty }}</td>
          <td class="collapsing">{{ $bap->created_at }}</td>
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
  <div class="content">
    assasa
  </div>
</div>

@endsection