@extends('layouts.semantic')
@section('title', 'Transaksi In')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Transaksi</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Barang Masuk</div>
</div>

<div class="ui divider"></div>

<div class="ui  segments">
  <div class="ui segment">

    <form class="ui form" action="{{ route('transin.store') }}" method="post">
      {{ csrf_field() }}
      <div class="field">
        <label>No. Transaksi</label>
        <input type="text" name="invoice" placeholder="First Name" readonly value="{{ $faktur }}">
      </div>

      <div class="field">
        <label>Diterima Dari</label>
        <div class="ui fluid category search department">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="Pilih Department / Toko / Supplier">
            <i class="search icon"></i>
          </div>
          <div class="results"></div>
        </div>
        <input type="hidden" name="dari" class="dept" readonly>
      </div>

      <div class="field">
        <label>PIC</label>
        <input type="text" name="pic" placeholder="NIK - NAMA">
      </div>

      <div class="field">
        <label>Note</label>
        <textarea name="keterangan" rows="2"></textarea>
      </div>

      <div class="field">
        <label>Pembuat</label>
        @forelse($users as $index => $user)
        @if($user->nik == session('nik'))
        <input type="text" value="{{ $user->nik }} - {{ $user->name }}" readonly>
        <input type="hidden" name="pembuat" value="{{ $user->id }}" readonly>
        @endif
        @empty
        @endforelse
      </div>

      <button class="ui blue button" type="submit">Simpan</button>
    </form>

  </div>
</div>



@endsection