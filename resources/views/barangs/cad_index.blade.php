@extends('layouts.semantic')
@section('title', 'Management Barang CAD')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Management</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Barang Cadangan EDP</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>MANAGEMENT BARANG CADANGAN EDP</h2>
  </div>
  <div class="ui green segment">

    <table id="S" class="ui celled table responsive nowrap unstackable managementExport" style="width:100%">
      <thead class="full-width">
        <tr>
          <th>No.</th>
          <th>Kode Barang</th>
          <th>SN/Mac</th>
          <th>Status</th>
          <th>Toko</th>
          <th><span class="act_label">Action</span></th>
        </tr>
      </thead>
      <tbody>
        @forelse($barangs as $index => $barang)
        <tr>
          <td class="center aligned collapsing">{{ $index + 1 }}</td>
          <td class="mob_icons collapsing">
            <ul class="ui list">
              <li>Kode : {{ $barang->kode }}</li>
              {{-- <li>Kategori : {{ $barang->kategori->name }}</li> --}}
              <li>Nama Barang : {{ $barang->name }}</li>
            </ul>
          </td>
          <td class="center aligned">{{ $barang->mac }}</td>
          <td class="center aligned collapsing">
            @if($barang->recid == 0)
            @elseif($barang->recid == 3)
            <a class="ui tag blue label">ALOKASI TOKO</a>
            @elseif($barang->recid == 5)
            <a class="ui tag red label">Rusak</a>
			@elseif($barang->recid == 9)
			<a class="ui tag yellow label">N/A</a>
            @else
            <a class="ui tag olive label">Dipinjamkan</a>
            @endif
          </td>
          <td class="center aligned collapsing">
            @if($barang->department_id == 0)
            @else
            <div class="ui left labeled button" tabindex="0">
              <div class="ui basic right pointing label">
                {{ $barang->department->kdtk }} ({{ $barang->updated_at->diffForHumans() }})
              </div>
              <a href="{{ url('/CAD/RETURN/' . $barang->id) }}" class="ui animated teal button">
                <div class="visible content">Return</div>
                <div class="hidden content">
                  <i class="sync alternate icon"></i>
                </div>
              </a>
            </div>
            @endif
          </td>
          <td class="center aligned two wide collapsing">
            <form action="{{ url('/CAD/' . $barang->id) }}" method="POST">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="DELETE" class="form-control">
              <a href="{{ url('/CAD/' . $barang->id) }}" class="ui animated brown button act_btn">
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
          <th colspan="6">
            <div id="test" class="ui small primary labeled icon button">
              <i class="plus icon"></i> Tambah Barang
            </div>
          </th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>


<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LPP CADANGAN</h2>
  </div>
  <div class="ui green segment">

    <table id="S" class="ui celled table responsive nowrap unstackable" style="width:100%">
      <thead class="full-width">
        <tr>
          <th>No.</th>
          <th>Barang</th>
          <th>Total</th>
          <th>Ready</th>
          <th>Pinjam</th>
          <th>Alokasi</th>
          <th>Rusak</th>
		  <th>N/A</th>
        </tr>
      </thead>
      <tbody>
        @foreach($lpp_cad as $index => $data)
        <tr>
          <td class="center aligned collapsing">{{ $index + 1 }}</td>
          <td>{{ $data->NAME }}</td>
          <td class="right aligned collapsing">{{ $data->SEMUA }}</td>
          <td class="right aligned positive collapsing">{{ $data->READY }}</td>
          <td class="right aligned collapsing">{{ $data->PINJAM }}</td>
          <td class="right aligned collapsing">{{ $data->ALOKASI }}</td>
          <td class="right aligned warning collapsing">{{ $data->RUSAK }}</td>
		  <td class="right aligned error collapsing">{{ $data->NA }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>

{{-- MODAL --}}

<div class="ui small modal">
  <i class="close icon"></i>
  <div class="header">
    Tambah Data Barang
  </div>
  <div class="content">
    <div class="description">
      <form class="ui form" action="{{ url('/CAD') }}" method="post">
        {{ csrf_field() }}


        <div class="field">
          <label>Pilih Barang</label>
          <div class="fields">

            <div class="ten wide field">
              <div class="ui fluid category search barang">
                <div class="ui icon input">
                  <input class="prompt" type="text" name="nama_barang" placeholder="Pilih Barang">
                  <i class="search icon"></i>

                </div>
                <div class="results"></div>
              </div>
            </div>

            <div class="three wide field">
              <input type="hidden" class="barang_kode" placeholder="ID Barang" readonly>
              <input type="text" class="barang_kode_name" name="kode_barang" placeholder="Kode Barang" readonly>
            </div>

            <div class="three wide field">
              <input type="hidden" class="kategori_id" name="kategori" placeholder="ID Kategori" readonly>
              <input type="text" class="kategori_id_name" placeholder="Kategori Barang" readonly>
            </div>

          </div>
        </div>

        <div class="field">
          <label>Mac Address / Serial Number Barang</label>
          <input type="text" name="mac_sn" placeholder="Mac/SN Barang">
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