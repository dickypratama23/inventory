@extends('layouts.semantic')
@section('title', 'Assembly Barang')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Management</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Barang</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>MANAGEMENT ASSEMBLY (TOKO)</h2>
  </div>
  <div class="ui green segment">
    <table id="exasmple" class="ui celled table responsive nowrap unstackable assembly" style="width:100%">
      <thead class="full-width">
        <tr>
          <th class="kode">Kode Barang</th>
          <th>Barang</th>
          <th>Kategori</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>SN/Mac</th>
          <th>SN Assembly</th>
        </tr>
      </thead>
      <tbody>
        @foreach ( $data_toko as $index => $row )
        <tr>
          <td class="collapsing">{{ $row->barang->kode }}</td>
          <td class="collapsing">{{ $row->barang->name }}</td>
          <td class="collapsing">{{ $row->barang->kategori->name }}</td>
          <td class="collapsing">{{ $row->department->kdtk }}</td>
          <td>{{ $row->department->name }}</td>
          <td class="collapsing">{{ $row->sn_general }}</td>
          <td class="collapsing">{{ $row->sn_assembly }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<br>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>MANAGEMENT ASSEMBLY (DEPARTMENT)</h2>
  </div>
  <div class="ui green segment">
    <table id="exasmple" class="ui celled table responsive nowrap unstackable assembly" style="width:100%">
      <thead class="full-width">
        <tr>
          <th class="kode">Kode Barang</th>
          <th>Barang</th>
          <th>Kategori</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>SN/Mac</th>
          <th>SN Assembly</th>
          <th>DAT</th>
        </tr>
      </thead>
      <tbody>
        @foreach ( $data_dept as $index => $row )
        <tr>
          <td class="collapsing">{{ $row->barang->kode }}</td>
          <td class="collapsing">{{ $row->barang->name }}</td>
          <td class="collapsing">{{ $row->barang->kategori->name }}</td>
          <td class="collapsing">{{ $row->department->kdtk }}</td>
          <td>{{ $row->department->name }}</td>
          <td class="collapsing">{{ $row->sn_general }}</td>
          <td class="collapsing">{{ $row->sn_assembly }}</td>
          <td class="collapsing">{{ $row->DAT }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>


@endsection