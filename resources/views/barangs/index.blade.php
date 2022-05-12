@extends('layouts.semantic')
@section('title', 'Management Barang')
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
    <h2>MANAGEMENT BARANG</h2>
  </div>
  <div class="ui green segment">
    <table id="exasmple" class="ui celled table responsive nowrap unstackable managementExport" style="width:100%">
      <thead class="full-width">
        <tr>
          {{-- <th>No.</th> --}}
          <th class="kode">Kode Barang</th>
          <th>Barang</th>
          <th>Kategori</th>
          <th>SN/Mac</th>
          <th>Add Time</th>
          <th>Upd Time</th>
          <th><span class="act_label">Action</span></th>
        </tr>
      </thead>
      <tbody>
        @forelse($barangs as $index => $barang)
          <tr>
            {{-- <td class="center aligned">{{ $index + 1 }}</td> --}}
            <td class="mob_icons"><i class="plus circle green icon mob_icon"></i> {{ $barang->kode }}</td>
            <td>{{ $barang->name }}</td>
            <td>{{ $barang->kategori->name }}</td>
            <td class="center aligned">@if($barang->mac) <i class="icon checkmark"></i> Out By Mac/SN @endif</td>
            <td class="center aligned">{{ $barang->created_at->format('d-m-Y H:i:s') }}</td>
            <td class="center aligned">{{ $barang->updated_at->format('d-m-Y H:i:s') }}</td>
            <td class="center aligned two wide">
              <form action="{{ url('/barang/' . $barang->id) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE" class="form-control">
                <a href="{{ url('/barang/' . $barang->id) }}" class=" ui small animated brown button act_btn" style="width:85%;">
                  <div class="visible content">Edit</div>
                  <div class="hidden content">
                    <i class="edit icon"></i>
                  </div>
                </a>

                <!--<button class="ui animated red button">
                  <div class="visible content">Hapus</div>
                  <div class="hidden content">
                    <i class="trash alternate icon"></i>
                  </div>
                </button>-->

              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td class="center aligned" colspan="5">Tidak ada data</td>
          </tr>
        @endforelse
        
      </tbody>
      <tfoot class="full-width">
        <tr>
          <th colspan="7">
            <div id="test" class="ui small primary labeled icon button">
              <i class="plus icon"></i> Tambah Barang
            </div>
          </th>
        </tr>
      </tfoot>
    </table>












    


  </div>
</div>


<div class="ui small modal">
  <i class="close icon"></i>
  <div class="header">
    Tambah Data Barang
  </div>
  <div class="content">
    <div class="description">
      <form class="ui form" action="{{ url('/barang') }}" method="post">
        {{ csrf_field() }}
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
          <input type="text" name="kode_barang" placeholder="Kode Barang">
        </div>

        <div class="field">
          <label>Nama Barang</label>
          <input type="text" name="nama_barang" placeholder="Nama Barang">
        </div>

        <div class="ui toggle checkbox">
          <input type="checkbox" name="to_by_mac" value="1">
          <label>Transaksi Out by Serial Number / Mac Address</label>
        </div>
        
    </div>
  </div>
  <div class="actions">
    <div class="ui black deny button">
      Cancel
    </div>
    <button class="ui positive right labeled icon button" type="submit"><i class="checkmark icon"></i>Submit</button>
    
  </div>
  </form>
</div>

@endsection