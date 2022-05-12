@extends('layouts.semantic')
@section('title', 'Approve')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Approve</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>APPROVE TRANSAKSI</h2>
  </div>
  <div class="ui green segment">
    <table id="examSple" class="ui striped selectable celled table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Invoice</th>
          <th data-field="item">Ke</th>
          <th data-field="item">PIC</th>
          <th data-field="price">Total Item</th>
          <th data-field="price">Note</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($transaksi as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned">{{ $no++ }}</>
          <td>{{ $row->invoice }}</td>
          <td>{{ $row->department->kdtk }} :: {{ $row->department->name }}</td>
          <td>{{ $row->pic }}</td>
          <td class="">
            <div class="ui accordion">
              <div class="title">
                <i class="dropdown icon"></i>
                {{ $row->detail->count() }} item
              </div>
              <div class="content">
                <p class="transition hidden">
                  @foreach ($row->detail as $detail)
                  <div class="ui list">
                    <div class="item">
                      <div class="content">
                        <table class="ui table">
                          <tbody>
                            <tr>
                              <td class="collapsing">
                                <i class="microchip icon"></i> {{ $detail->barang->name }}
                              </td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </p>
              </div>
            </div>
          </td>
          <td>{{ $row->note }}</td>
          <td class="center aligned">
            <a href="{{ route('approve.out', $row->id) }}" class="ui green icon button">
              <i class="check icon"></i>
            </a>

            <a href="{{ route('reject.out', $row->id) }}" class="ui red icon button">
              <i class="close icon"></i>
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
    <h2>APPROVE TRANSAKSI PEMINJAMAN</h2>
  </div>
  <div class="ui green segment">
    <table id="examSple" class="ui striped selectable celled table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Invoice</th>
          <th data-field="item">Ke</th>
          <th data-field="item">PIC</th>
          <th data-field="price">Total Item</th>
          <th data-field="price">Note</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($pinjam as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned">{{ $no++ }}</>
          <td>{{ $row->invoice }}</td>
          <td>{{ $row->department->kdtk }} :: {{ $row->department->name }}</td>
          <td>{{ $row->pic }}</td>
          <td class="">
            <div class="ui accordion">
              <div class="title">
                <i class="dropdown icon"></i>
                {{ $row->detail->count() }} item
              </div>
              <div class="content">
                <p class="transition hidden">
                  @foreach ($row->detail as $detail)
                  <div class="ui list">
                    <div class="item">
                      <div class="content">
                        <table class="ui table">
                          <tbody>
                            <tr>
                              <td class="collapsing">
                                <i class="microchip icon"></i> {{ $detail->cad->name }} ({{ $detail->Serial_number }})
                              </td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </p>
              </div>
            </div>
          </td>
          <td>{{ $row->note }}</td>
          <td class="center aligned">
            <a href="{{ route('approve.lent', $row->id) }}" class="ui green icon button">
              <i class="check icon"></i>
            </a>

            <a href="{{ route('reject.lent', $row->id) }}" class="ui red icon button">
              <i class="close icon"></i>
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
    <h2>APPROVE SERVICE</h2>
  </div>
  <div class="ui green segment">
    <table id="examSple" class="ui striped selectable celled table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Barang</th>
          <th data-field="item">Serial Number</th>
          <th data-field="item">Toko</th>
          <th data-field="price">Penggantian</th>
          <th data-field="price">Note</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($service as $row)
        @foreach ($service_in as $row_in)
        @if($row_in->invoice == $row->inv_relation)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned">{{ $no++ }}</>
          <td>{{ $row->barang->name }}</td>
          <td>@foreach ($row_in->detail as $detail) @if($detail->barang_id == $row->barang_id)
            {{ $detail->Serial_number }} @endif @endforeach </td>
          <td>{{ $row->department->kdtk }} :: {{ $row->department->name }}</td>
          <td class="">
            @if($row->detail->count() > 0)
            <div class="ui accordion">
              <div class="title">
                <i class="dropdown icon"></i>
                {{ $row->detail->count() }} item Spare Part
              </div>
              <div class="content">
                <p class="transition hidden">
                  @foreach ($row->detail as $detail)
                  <div class="ui list">
                    <div class="item">
                      <div class="content">
                        <table class="ui table">
                          <tbody>
                            <tr>
                              <td class="collapsing">
                                <i class="microchip icon"></i> {{ $detail->barang->name }} ({{ $detail->qty }}X)
                              </td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </p>
              </div>
            </div>
            @else
            Tidak Ada (Service Biasa)
            @endif
          </td>
          <td>{{ $row->note }}</td>
          <td class="center aligned">
            <a href="{{ route('approve.service', [ 'id' => $row->id, 'sid' => $row_in->id, 'bi' => $row->barang_id]) }}"
              class="ui green icon button">
              <i class="check icon"></i>
            </a>
          </td>
        </tr>
        @endif
        @endforeach
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
    <h2>APPROVE BAP</h2>
  </div>
  <div class="ui green segment">
    <table id="examSple" class="ui striped selectable celled table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Barang</th>
          <th data-field="item">Ke</th>
          <th data-field="price">PIC</th>
          <th>Item</th>
          <th>Note</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($bap as $row)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned">{{ $no++ }}</>
          <td>{{ $row->invoice }}</td>
          <td>{{ $row->department->kdtk }} :: {{ $row->department->name }}</td>
          <td>{{ $row->pic }}</td>
          <td class="">
            <div class="ui accordion">
              <div class="title">
                <i class="dropdown icon"></i>
                {{ $row->detail->count() }} item
              </div>
              <div class="content">
                <p class="transition hidden">
                  @foreach ($row->detail as $detail)
                  <div class="ui list">
                    <div class="item">
                      <div class="content">
                        <table class="ui table">
                          <tbody>
                            <tr>
                              <td class="collapsing">
                                <i class="microchip icon"></i> {{ $detail->barang->name }}
                                ({{ $detail->Serial_number }})
                              </td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </p>
              </div>
            </div>
          </td>
          <td>{{ $row->note }}</td>
          <td class="center aligned">
            <a href="{{ route('approve.bap', $row->id) }}" class="ui green icon button">
              <i class="check icon"></i>
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












<!--
<blockquote>
    <h5>Approve Transaksi</h5>
</blockquote>

      <div class="row">
        <table class="col s12 m12 l12">
            <table class="highlight responsive-table fold-table">
                <thead>
                <tr>
                    <th data-field="no">No</th>
                    <th data-field="item">Invoice</th>
                    <th data-field="item">Ke</th>
                    <th data-field="item">PIC</th>
                    <th data-field="price">Total Item</th>
                    <th data-field="price">Note</th>
                </tr>
                </thead>
                <tbody>
                    @php $no = 1 @endphp
                    @forelse ($transaksi as $row)                    
                        <tr class="view" style="cursor: pointer;">
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->invoice }}</td>
                            <td>{{ $row->department->kdtk }} :: {{ $row->department->name }}</td>
                            <td>{{ $row->pic }}</td>
                            <td><span class="new badge" data-badge-caption="">{{ $row->detail->count() }}</span></td>
                            <td>{{ $row->note }}</td>
                        </tr>
                        <tr class="fold" style="display: none;">
                            <td colspan="6">
                                <table>
                                    <thead>
                                        <tr class="green lighten-3">
                                            <th></th>
                                            <th>Kode Barang</th>
                                            <th colspan="2">Nama Barang</th>
                                            <th colspan="2">Jenis Barang</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach ($row->detail as $detail)
                                            <tr class="green lighten-4">
                                                <td></td>
                                                <td>{{ $detail->barang->kode }}</td>
                                                <td colspan="2">{{ $detail->barang->name }}</td>
                                                <td colspan="2">{{ $detail->barang->kategori->name }}</td>
                                                <td>{{ $detail->qty }}</td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table> 

                                @if($row->status == 1)
                                <br>
                                    <div class="right-align">
                                        <a href="{{ route('approve.out', $row->id) }}" class="btn-small blue">Approve</a>
                                        <a href="{{ route('reject.out', $row->id) }}" class="btn-small red">Reject</a>
                                    </div>
                                    
                                @endif
                            </td>
                        </tr>  
                    @empty       
                        <tr>
                            <td class="center-align" colspan="6">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </table>
        </div>
      </div>-->
@endsection