@extends('layouts.semantic')
@section('title', 'Management Barang CAD')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Management</a>
  <i class="right angle icon divider"></i>
  <a class="section">Barang Cadangan</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Edit</div>
</div>

<div class="ui divider"></div>

<div class="ui piled segment">
  <h2 class="ui header">Edit Data Barang Cadangan EDP</h2>
  <div class="ui divider"></div>
  <form class="ui form" action="{{ url('/CAD/' . $barang->id) }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" class="form-control">
        
        <div class="field">
          <label>Pilih Barang</label>
          <div class="fields">

            <div class="ten wide field">
              <div class="ui fluid category search barang">
                <div class="ui icon input">
                    <input class="prompt" type="text" value="{{ $barang->name }}" name="nama_barang" placeholder="Pilih Barang">
                    <i class="search icon"></i>
                    
                </div>
                <div class="results"></div>
              </div>
            </div>

            <div class="three wide field">
              <input type="hidden" class="barang_kode" value="{{ $barang->id }}" placeholder="ID Barang" readonly>
              <input type="text" class="barang_kode_name" name="barang_kode_name" value="{{ substr($barang->kode,4,7) }}" placeholder="Kode Barang" readonly>
            </div>

            <div class="three wide field">
              <input type="hidden" class="kategori_id" name="kategori" value="{{ $barang->kategori_id }}" placeholder="ID Kategori" readonly>
              <input type="text" class="kategori_id_name" value="{{ $barang ->kategori->name}}" placeholder="Kategori Barang" readonly>
            </div>
            
          </div>
        </div>





        

        

        <div class="field">
          <label>Mac Address / Serial Number Barang</label>
          <input type="text" name="mac_sn" value="{{ $barang->mac }}" placeholder="Mac/SN Barang">
        </div>

        

        <br>
        <button class="ui button" type="submit">Submit</button>
</form>
</div>

@endsection