@extends('layouts.semantic')
@section('title', 'Laporan Service')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Service</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Laporan Service Acc Gl</div>
</div>

<div class="ui divider"></div>


<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN SERVICE ACC GL</h2>
  </div>
  <div class="ui green segment">
    <table id="" class="ui striped selectable celled table fold-table barang_gl" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="item">Tanggal</th>
          <th data-field="item">Toko / Dept</th>
          <th data-field="item">Barang</th>
          <th data-field="item">Item Penggantian</th>
          <th data-field="item">Qty</th>
          <th data-field="item">Action</th>

        </tr>
      </thead>
      <tbody>

        @foreach ($SER_OUT as $index => $row_out)
        <tr>
          <td>{{ $row_out->created_at->format('Y-m-d') }}</td>
          <td>{{ $row_out->transaksi->department->kdtk }}</td>
          <td>
            {{ $row_out->transaksi->barang->name }} ({{ $row_out->transaksi->invr->detail[0]->Serial_number }})
            {{-- <strong>BARANG :</strong> {{ $row_out->transaksi->barang->name }} <br>
            <strong>S/N :</strong> {{ $row_out->transaksi->invr->detail[0]->Serial_number }} <br>
            {{ $row_out->id }} --}}
          </td>
          <td>{{ $row_out->barang->name }}</td>
          <td>{{ $row_out->qty }}</td>
          <td class="center aligned">
            <a href="{{ route('gl.process', $row_out->id) }}" class="ui teal icon button upd_status"
              data-content="Proses Region" data-position="top center">
              <i class="check icon"></i>
            </a>
          </td>
        </tr>
        @endforeach


      </tbody>

    </table>
  </div>
</div>

<script>
  window.addEventListener('load', function () {
    $('.barang_gl').DataTable({
      "order": [[ 1, "asc" ]]
    });
  });
</script>

@endsection