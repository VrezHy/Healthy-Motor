@extends('layouts.app')

@section('content')

<style>
  .table-header { background: #7c84d0; color: white; }
  .btn-hapus {
    background: #e53e3e; color: white; padding: 6px 16px;
    border-radius: 999px; font-size: 12px; font-weight: 700;
    border: none; cursor: pointer; transition: background 0.2s;
  }
  .btn-hapus:hover { background: #c53030; }
  .btn-ubah {
    background: #5a63a8; color: white; padding: 6px 16px;
    border-radius: 999px; font-size: 12px; font-weight: 700;
    border: none; cursor: pointer; transition: background 0.2s;
  }
  .btn-ubah:hover { background: #4a5298; }
  .btn-add {
    background: #7c84d0; color: white; width: 36px; height: 36px;
    border-radius: 10px; font-size: 22px; font-weight: 700; border: none;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: background 0.2s, transform 0.15s;
  }
  .btn-add:hover { background: #5a63a8; transform: scale(1.08); }
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(74,82,152,0.45);
    backdrop-filter: blur(2px); display: flex; align-items: center;
    justify-content: center; z-index: 50; opacity: 0;
    pointer-events: none; transition: opacity 0.25s;
  }
  .modal-overlay.active { opacity: 1; pointer-events: all; }
  .modal-box {
    background: #dde0f0; border-radius: 20px; padding: 48px 40px 36px;
    width: 100%; max-width: 580px; box-shadow: 0 24px 60px rgba(74,82,152,0.25);
    transform: translateY(20px) scale(0.97); transition: transform 0.25s; position: relative;
  }
  .modal-overlay.active .modal-box { transform: translateY(0) scale(1); }
  .modal-input {
    width: 100%; background: #c8ccdf; border: none; border-radius: 12px;
    padding: 14px 20px; font-size: 15px; color: #2d3256; outline: none;
    transition: background 0.2s, box-shadow 0.2s; margin-bottom: 12px;
  }
  .modal-input:focus { background: #bec3d8; box-shadow: 0 0 0 3px rgba(122,130,208,0.35); }
  .modal-input.is-invalid { box-shadow: 0 0 0 2px #e53e3e; }
  .modal-label { font-size: 12px; font-weight: 700; color: #5a63a8; margin-bottom: 4px; display: block; }
  .btn-save {
    background: transparent; border: none; font-size: 20px; font-weight: 800;
    color: #2d3256; cursor: pointer; letter-spacing: 1px; padding: 6px 0; transition: color 0.2s;
  }
  .btn-save:hover { color: #5a63a8; }
  .btn-close-modal {
    position: absolute; top: 16px; right: 20px; background: transparent;
    border: none; font-size: 22px; color: #7c84d0; cursor: pointer; font-weight: 700;
  }
  .btn-close-modal:hover { color: #e53e3e; }
  .alert-success {
    background: #d1fae5; border: 1.5px solid #6ee7b7; color: #065f46;
    border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 600;
    margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
  }
  tr:nth-child(even) td { background: #f3f4fc; }
  tr:nth-child(odd) td { background: white; }
  .badge-kerusakan {
    background: #ede9fe; color: #5a63a8; font-size: 11px; font-weight: 700;
    padding: 3px 10px; border-radius: 999px;
  }
  select.modal-input { cursor: pointer; }
  textarea.modal-input { resize: vertical; min-height: 90px; }
</style>

@if(session('success'))
<div class="alert-success">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
  </svg>
  {{ session('success') }}
</div>
@endif

<div class="mb-3">
  <button class="btn-add" id="btnAddOpen" title="Tambah Data Solusi">+</button>
</div>

<div class="rounded-2xl overflow-hidden shadow-sm">
  <div class="table-header px-5 py-3">
    <span class="font-bold text-sm">Tabel Data Solusi</span>
  </div>
  <div class="bg-white overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#f0f1fa;">
          <th class="text-left px-5 py-3 font-bold text-gray-700 w-12">No</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Nama Solusi</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Deskripsi</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Kerusakan</th>
          <th class="text-right px-5 py-3 font-bold text-gray-700 w-44">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($solusies as $item)
        <tr>
          <td class="px-5 py-3 font-semibold text-gray-700">{{ $loop->iteration }}</td>
          <td class="px-5 py-3 text-gray-700 font-semibold">{{ $item->nama_solusi }}</td>
          <td class="px-5 py-3 text-gray-500 text-xs">{{ Str::limit($item->deskripsi, 60) ?? '-' }}</td>
          <td class="px-5 py-3">
            <span class="badge-kerusakan">{{ $item->kerusakan->nama_kerusakan ?? '-' }}</span>
          </td>
          <td class="px-5 py-3 text-right flex items-end shrink-0justify-center flex-col gap-2">
            <form action="{{ route('admin.solusi.destroy', $item->id) }}" method="POST"
              class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-hapus">Hapus</button>
            </form>
            <button type="button" class="btn-ubah"
              data-id="{{ $item->id }}"
              data-nama="{{ $item->nama_solusi }}"
              data-deskripsi="{{ $item->deskripsi }}"
              data-kerusakan="{{ $item->kerusakan_id }}"
              onclick="openEditModal(this.dataset.id, this.dataset.nama, this.dataset.deskripsi, this.dataset.kerusakan)">
              Ubah
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-5 py-8 text-center text-gray-400 font-medium">
            Belum ada data solusi. Klik <strong>+</strong> untuk menambahkan.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalTambah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">Tambah Data Solusi</h2>
    <form action="{{ route('admin.solusi.store') }}" method="POST">
      @csrf
      <div>
        <label class="modal-label">Nama Solusi</label>
        <input type="text" name="nama_solusi" class="modal-input {{ $errors->has('nama_solusi') ? 'is-invalid' : '' }}"
          value="{{ old('nama_solusi') }}" placeholder="Nama solusi..." autocomplete="off"/>
        @error('nama_solusi')<p class="text-red-500 text-xs font-bold -mt-2 mb-2">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="modal-label">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
        <textarea name="deskripsi" class="modal-input" placeholder="Deskripsi solusi...">{{ old('deskripsi') }}</textarea>
      </div>
      <div>
        <label class="modal-label">Kerusakan Terkait</label>
        <select name="kerusakan_id" class="modal-input {{ $errors->has('kerusakan_id') ? 'is-invalid' : '' }}">
          <option value="">-- Pilih Kerusakan --</option>
          @foreach($kerusakans as $k)
            <option value="{{ $k->id }}" {{ old('kerusakan_id') == $k->id ? 'selected' : '' }}>
              {{ $k->nama_kerusakan }}
            </option>
          @endforeach
        </select>
        @error('kerusakan_id')<p class="text-red-500 text-xs font-bold -mt-2 mb-2">{{ $message }}</p>@enderror
      </div>
      <div class="flex justify-end mt-4">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL UBAH --}}
<div class="modal-overlay" id="modalUbah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalUbah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">Ubah Data Solusi</h2>
    <form id="formUbah" method="POST">
      @csrf
      @method('PUT')
      <div>
        <label class="modal-label">Nama Solusi</label>
        <input type="text" name="nama_solusi" id="inputNama" class="modal-input" placeholder="Nama solusi..." autocomplete="off"/>
      </div>
      <div>
        <label class="modal-label">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
        <textarea name="deskripsi" id="inputDeskripsi" class="modal-input" placeholder="Deskripsi solusi..."></textarea>
      </div>
      <div>
        <label class="modal-label">Kerusakan Terkait</label>
        <select name="kerusakan_id" id="selectKerusakan" class="modal-input">
          <option value="">-- Pilih Kerusakan --</option>
          @foreach($kerusakans as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kerusakan }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex justify-end mt-4">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function openModal(id) {
    document.getElementById(id).classList.add('active');
  }
  function closeModal(id) {
    document.getElementById(id).classList.remove('active');
  }
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
      if (e.target === this) this.classList.remove('active');
    });
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
  });

  document.getElementById('btnAddOpen').addEventListener('click', () => openModal('modalTambah'));

  function openEditModal(id, nama, deskripsi, kerusakanId) {
    const form = document.getElementById('formUbah');
    form.action = '/admin/solusi/' + id;
    document.getElementById('inputNama').value = nama;
    document.getElementById('inputDeskripsi').value = deskripsi;
    document.getElementById('selectKerusakan').value = kerusakanId;
    openModal('modalUbah');
  }

  @if($errors->has('nama_solusi') || $errors->has('kerusakan_id'))
    openModal('modalTambah');
  @endif
</script>
@endpush
