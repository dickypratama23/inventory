@extends('layouts.semantic')
@section('title', 'Management Kategori')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Management</a>
  <i class="right angle icon divider"></i>
  <a class="section">Kategori</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Edit</div>
</div>

<div class="ui divider"></div>

<div class="ui piled segment">
  <h2 class="ui header">Edit Data Kategori</h2>
  <div class="ui divider"></div>
  <form class="ui form" action="{{ url('/kategori/' . $kategori->id) }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" class="form-control">

        <div class="field">
          <label>Nama Kategori</label>
          <input type="text" name="nama_kategori" value="{{ $kategori->name }}" placeholder="Kode Barang">
        </div>

        <div class="field">
          <label>Deskripsi</label>
          <input type="text" name="desk_kategori" value="{{ $kategori->deskripsi }}" placeholder="Nama Barang">
        </div>
  
  <button class="ui animated positive button" tabindex="0">
  <div class="visible content">Simpan</div>
  <div class="hidden content">
    <i class="right arrow icon"></i>
  </div>
</button>
</form>
</div>  


@endsection