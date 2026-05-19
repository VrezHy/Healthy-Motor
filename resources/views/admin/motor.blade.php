@extends('layouts.app')

@section('content')

<style>
  .table-header {
    background: #7c84d0;
    color: white;
  }

  .badge-pending  { background: #fef3c7; color: #92400e; border-radius: 999px; padding: 2px 12px; font-size: 11px; font-weight: 700; }
  .badge-proses   { background: #dbeafe; color: #1e40af; border-radius: 999px; padding: 2px 12px; font-size: 11px; font-weight: 700; }
  .badge-selesai  { background: #d1fae5; color: #065f46; border-radius: 999px; padding: 2px 12px; font-size: 11px; font-weight: 700; }

  tr:nth-child(even) td { background: #f3f4fc; }
  tr:nth-child(odd)  td { background: white; }

  .info-banner {
    background: #e0e3f8;
    border: 1.5px solid #a5aee8;
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 13px;
    font-weight: 600;
    color: #3730a3;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
</style>

{{-- Info banner --}}
<div class="info-banner">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z" />
  </svg>
  Data motor di bawah bersumber dari <strong>log riwayat mekanik</strong>. Untuk menambah / mengubah data, gunakan
  <a href="{{ route('mekanik.riwayat') }}" class="underline ml-1">Dashboard Mekanik</a>.
</div>

{{-- Tabel --}}
<div class="rounded-2xl overflow-hidden shadow-sm">
  <div class="table-header px-5 py-3">
    <span class="font-bold text-sm">🏍️ Tabel Data Motor (dari Log Riwayat Mekanik)</span>
  </div>
  <div class="bg-white overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#f0f1fa;">
          <th class="text-left px-4 py-3 font-bold text-gray-700 w-10">#</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Nama Pemilik</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Merk / Tipe</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Plat Nomor</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Keluhan</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Kerusakan</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Status</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Catatan Mekanik</th>
          <th class="text-left px-4 py-3 font-bold text-gray-700">Tanggal</th>
        </tr>
      </thead>
      <tbody>
        @forelse($motors as $item)
        <tr>
          <td class="px-4 py-3 font-semibold text-gray-500">{{ $loop->iteration }}</td>
          <td class="px-4 py-3 font-semibold text-gray-800">{{ $item->nama_pemilik }}</td>
          <td class="px-4 py-3 text-gray-700">{{ $item->merk_motor }}</td>
          <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $item->plat_nomor }}</td>
          <td class="px-4 py-3 text-gray-600" style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->keluhan }}">
            {{ $item->keluhan }}
          </td>
          <td class="px-4 py-3 text-gray-700">{{ $item->kerusakan?->nama_kerusakan ?? '—' }}</td>
          <td class="px-4 py-3">
            @if($item->status === 'pending')
              <span class="badge-pending">Pending</span>
            @elseif($item->status === 'proses')
              <span class="badge-proses">Proses</span>
            @else
              <span class="badge-selesai">Selesai</span>
            @endif
          </td>
          <td class="px-4 py-3 text-gray-500 text-xs" style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->catatan_mekanik }}">
            {{ $item->catatan_mekanik ?? '—' }}
          </td>
          <td class="px-4 py-3 text-gray-500 text-xs">{{ $item->created_at->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="px-5 py-10 text-center text-gray-400 font-medium">
            Belum ada data motor. Mekanik belum menambahkan log riwayat.
            <a href="{{ route('mekanik.riwayat') }}" class="text-indigo-500 underline ml-1">Buka Dashboard Mekanik</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
