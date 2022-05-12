@extends('layouts.semantic')
@section('title', 'Management Kategori')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Management</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Kategori</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>MANAGEMENT KATEGORI</h2>
  </div>
  <div class="ui green segment">
    



    <table class="ui celled striped table">
  <thead>
    <tr><th colspan="4">
      KATEGORI BARANG
    </th>
  </tr></thead>
  <tbody>
    @forelse($kategoris as $index => $kategori)
    <tr>
      <td class="collapsing">
        {{ $kategori->name }}
      </td>
      <td>{{ $kategori->deskripsi }}</td>
      <td class="right aligned collapsing">{{ $kategori->created_at->diffForHumans() }}</td>
      <td class="center aligned two wide">
        <form action="{{ url('/kategori/' . $kategori->id) }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="DELETE" class="form-control">
          <a href="{{ url('/kategori/' . $kategori->id) }}" class="fluid ui small animated brown button">
            <div class="visible content">Edit</div>
            <div class="hidden content">
              <i class="edit icon"></i>
            </div>
          </a>
          <!--<button class="waves-effect waves-light btn red btn-small"><i class="material-icons left">clear</i>Hapus</button>-->
        </form>
      </td>
    </tr>
    @empty
    @endforelse
  </tbody>
  <tfoot class="full-width">
    <tr>
      <th colspan="4" class="right aligned">
        <div id="test" class="ui  primary labeled icon button">
              <i class="plus icon"></i> Tambah Kategori
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
    Tambah Data Kategori
  </div>
  <div class="content">
    <div class="description">
      <form class="ui form" action="{{ url('/kategori') }}" method="post">
        {{ csrf_field() }}

        <div class="field">
          <label>Nama Kategori</label>
          <input type="text" name="nama_kategori" placeholder="Nama Kategori">
        </div>

        <div class="field">
          <label>Deskripsi Kategori</label>
          <input type="text" name="desk_kategori" placeholder="Deskripsi Kategori">
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