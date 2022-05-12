@extends('layouts.semantic')
@section('title', 'Informasi Barang Service')
@section('content')


<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Service</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Service HO</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>Service HO (Terbit Docno)</h2>
  </div>
  <div class="ui green segment">
    <table id="examsple" class="ui striped selectable celled table fold-table serviceExport" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="action">No.</th>
          <th data-field="item">Docno</th>
          <th class="collapsing" data-field="item">Total Item</th>
          <th data-field="price">Kirim HO</th>
          <th data-field="action">Action</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1 @endphp
        @forelse ($LIST_KIRIM as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned collapsing">{{ $no++ }}</td>

          <td>
            <div class="ui list">
              <div class="item">
                <div class="header">{{ $row->docno_ho }}</div>
              </div>
            </div>
          </td>

          <td class="center aligned">{{ $row->item }}</td>
          <td class="collapsing">{{ $row->updated_at->diffForHumans() }}</td>
          <td class="center aligned collapsing">
            <a href="{{ route('service.ho.detail', ['docno' => $row->docno_ho]) }}" class="ui grey icon button"
              data-content="Selesai Service HO" data-position="top center">
              <i class="search icon"></i>
            </a>

            <a href="{{ route('service.ho.cetak', ['docno' => $row->docno_ho]) }}" class="ui teal icon button"
              data-content="Selesai Service HO" data-position="top center">
              <i class="print icon"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td class="center aligned" colspan="7">Tidak ada data</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>




<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>List Barang Service HO (Proses Pembuatan Docno)</h2>
  </div>
  <div class="ui green segment">
    <table id="ss" class="ui striped selectable celled table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="action">No.</th>
          <th data-field="item">Barang</th>
          <th data-field="item">Toko</th>
          <th data-field="price">Detail</th>
          <th data-field="price">Terima</th>
          <th data-field="price">Kirim HO</th>
          <th data-field="action">Action</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1 @endphp
        @forelse ($LIST as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned collapsing">{{ $no++ }}</td>

          <td class="collapsing">
            <div class="ui list">
              <div class="item">
                <div class="header">{{ $row->barang->name }}</div>
                S/N : {{ $row->Serial_number }}
              </div>
            </div>
          </td>

          <td class="collapsing">{{ $row->transaksi->department->kdtk }}</td>

          <td>
            <ul class="ui list">
              <li>Masalah : {{ $row->note }}</li>
              <li>Pembawa : {{ $row->transaksi->pic }}</li>
              <li>Invoice : {{ $row->transaksi->invoice }}</li>
            </ul>
          </td>

          <td class="collapsing">{{ $row->transaksi->created_at->diffForHumans() }}</td>
          <td class="collapsing">{{ $row->updated_at->diffForHumans() }}</td>
          <td class="center aligned collapsing">
            <a href="{{ route('service.store', $row->id) }}" class="ui blue icon button"
              data-content="Selesai Service HO" data-position="top center">
              <i class="eject icon"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td class="center aligned" colspan="7">Tidak ada data</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    @if($no != 1)
    <a href="{{ route('generateDocno') }}" class="ui violet labeled icon button">
      <i class="file alternate icon"></i>
      Generate Docno ({{ $no - 1 }} Item)
    </a>
    @endif
  </div>
</div>


<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>Service HO (Per Barang)</h2>
  </div>
  <div class="ui green segment">
    <table id="sho_barang" class="ui striped selectable celled table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="action">No.</th>
          <th data-field="item">Kategori</th>
          <th data-field="item">Barang</th>
          <th data-field="item">Mac / SN</th>
          <th data-field="item">Toko</th>
          <th data-field="item">Kerusakan</th>
          <th data-field="item">Detail</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1 @endphp
        @forelse ($LIST_BARANG as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned collapsing">{{ $no++ }}</td>
          <td class="collapsing">{{ $row->barang->kategori->name }}</td>
          <td class="collapsing">{{ $row->barang->name }}</td>
          <td class="collapsing">{{ $row->Serial_number }}</td>
          <td class="collapsing">{{ $row->transaksi->department->kdtk }} | {{ $row->transaksi->department->name }}</td>
          <td>{{ $row->note }}</td>
          <td class="collapsing">
            <a href="{{ route('service.ho.detail', ['docno' => $row->docno_ho]) }}" class="ui grey icon button"
              data-content="Detail" data-position="top center">
              <i class="search icon"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td class="center aligned" colspan="7">Tidak ada data</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>



@endsection