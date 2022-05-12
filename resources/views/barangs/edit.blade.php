@extends('layouts.semantic')
@section('title', 'Management Barang')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Management</a>
  <i class="right angle icon divider"></i>
  <a class="section">Barang</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Edit</div>
</div>

<div class="ui divider"></div>

<div class="ui piled segment">
  <h2 class="ui header">Edit Data Barang</h2>
  <div class="ui divider"></div>
  <form class="ui form" action="{{ url('/barang/' . $barang->id) }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" class="form-control">
        <div class="field">
          <label>Kategori</label>
          <div class="ui kategori search selection dropdown">
            <input type="hidden" name="kategori">
            <i class="dropdown icon"></i>
            <div class="default text">Pilih Kategori</div>
          </div>
        </div>

        <div class="field">
          <label>Kode Barang</label>
          <input type="text" name="kode_barang" value="{{ $barang->kode }}" placeholder="Kode Barang">
        </div>

        <div class="field">
          <label>Nama Barang</label>
          <input type="text" name="nama_barang" value="{{ $barang->name }}" placeholder="Nama Barang">
        </div>

        <div class="field">
          <div class="ui toggle checkbox">
            <input type="checkbox" name="to_by_mac" value="1" @if($barang->mac == 1) checked @endif>
            <label>Transaksi Out by Serial Number / Mac Address</label>
          </div>
        </div>
        <br>
        <button class="ui button" type="submit">Submit</button>
</form>
</div>

@endsection