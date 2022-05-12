@extends('layouts.semantic')
@section('title', 'List Service Toko')
@section('content')


<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Service</a>
  <i class="right angle icon divider"></i>
  <div class="active section">List Service Toko</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>List Service Toko</h2>
  </div>
  <div class="ui green segment">
    <table id="exampleSSS" class="ui striped selectable celled table fold-table" style="width:100%">
      <thead class="full-width">
        <tr>
            {{-- <th data-field="no">Status</th> --}}
            <th data-field="action">No.</th>
            <th data-field="item">Docno</th>
            <th data-field="item">Toko</th>
            <th data-field="item">PIC</th>
            <th data-field="price">Total Item</th>
            <th data-field="price">Tanggal</th>
            <th data-field="action">Action</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1 @endphp
        @forelse ($Barang_Service as $row)                    
          <tr class="view" style="cursor: pointer;">
            {{-- <td><a class="ui @if($row->RECID1 == 0) blue @elseif($row->RECID1 == 1 && $row->RECID2 == 0) orange @elseif($row->RECID1 == 1 && $row->RECID2 == 1 && $row->RECID3 == 0) teal @else green @endif ribbon label">@if($row->RECID1 == 0) Diajukan @elseif($row->RECID1 == 1 && $row->RECID2 == 0) In Progress @elseif($row->RECID1 == 1 && $row->RECID2 == 1 && $row->RECID3 == 0) Selesai Service @else Selesai @endif</a></> --}}
            <td class="center aligned">{{ $no++ }}</td>
            <td>
              <div class="ui accordion">
                <div class="title">
                  <i class="dropdown icon"></i>
                  {{ substr($row->docno,7, 6) }}
                </div>
                <div class="content">
                  <p class="transition center aligned hidden">{{ $row->docno }}</p>
                </div>
              </div>
            </td>
            <td>
              <div class="ui accordion">
                <div class="title">
                  <i class="dropdown icon"></i>
                  {{ substr($row->dari, 0,4) }}
                </div>
                <div class="content">
                  <p class="transition center aligned hidden">{{ substr($row->dari, strpos($row->dari, "|") + 1) }}</p>
                </div>
              </div>
            </td>
            <td>
              {{ $row->dibuat }}
            </td>
            <td>
                <div class="ui accordion">
                    <div class="title">
                        <i class="dropdown icon"></i>
                        {{ $row->jml_item }} item
                    </div>
                    <div class="content">
                        <p class="transition hidden">
                            @foreach ($Barang_Service_Item as $detail)
                             @if($row->docno == $detail->docno)
                            <div class="ui list">
                                <div class="item">
                                    <div class="content">
                                        <table class="ui table">
                                            <tbody>
                                                <tr>
                                                  <td class="collapsing">    
                                                    <div class="ui threaded comments">
                                                      <div class="comment">
                                                        <div class="avatar">
                                                          <i class="microchip large icon"></i>
                                                        </div>
                                                        <div class="content">
                                                          <a class="author">{{ $detail->nama_barang }} ({{ $detail->sn }}) @if($detail->cad == 1) (Cadangan EDP) @endif </a>
                                                          <div class="metadata">
                                                          </div>
                                                          <div class="text">
                                                            {{ $detail->ket }}
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>  
                                                  </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </p>
                    </div>
                </div>
            </td>
            <td>{{ substr($row->tgl, 0,10) }}</td>
            <td class="center aligned">
                @if($row->RECID1 == 0)
                <a href="{{ route('service.terima', str_replace('/','_',$row->docno)) }}" class="ui teal icon button">
                    <i class="reply all icon"></i>
                </a>
                @endif
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