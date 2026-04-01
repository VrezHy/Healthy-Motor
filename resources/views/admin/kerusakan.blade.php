@extends('layouts.app')

@section('content')

{{-- ===== STYLE KHUSUS HALAMAN INI ===== --}}
<style>
  .table-header {
    background: #7c84d0;
    color: white;
  }

  .btn-hapus {
    background: #e53e3e;
    color: white;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
  }

  .btn-hapus:hover {
    background: #c53030;
  }

  .btn-ubah {
    background: #5a63a8;
    color: white;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
  }

  .btn-ubah:hover {
    background: #4a5298;
  }

  .btn-add {
    background: #7c84d0;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    font-size: 22px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, transform 0.15s;
  }

  .btn-add:hover {
    background: #5a63a8;
    transform: scale(1.08);
  }

  /* Modal */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(74, 82, 152, 0.45);
    backdrop-filter: blur(2px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s;
  }

  .modal-overlay.active {
    opacity: 1;
    pointer-events: all;
  }

  .modal-box {
    background: #dde0f0;
    border-radius: 20px;
    padding: 48px 40px 36px;
    width: 100%;
    max-width: 580px;
    box-shadow: 0 24px 60px rgba(74, 82, 152, 0.25);
    transform: translateY(20px) scale(0.97);
    transition: transform 0.25s;
    position: relative;
  }

  .modal-overlay.active .modal-box {
    transform: translateY(0) scale(1);
  }

  .modal-input {
    width: 100%;
    background: #c8ccdf;
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    font-size: 15px;
    color: #2d3256;
    outline: none;
    transition: background 0.2s, box-shadow 0.2s;
  }

  .modal-input:focus {
    background: #bec3d8;
    box-shadow: 0 0 0 3px rgba(122, 130, 208, 0.35);
  }

  .modal-input.is-invalid {
    box-shadow: 0 0 0 2px #e53e3e;
  }

  .btn-save {
    background: transparent;
    border: none;
    font-size: 20px;
    font-weight: 800;
    color: #2d3256;
    cursor: pointer;
    letter-spacing: 1px;
    padding: 6px 0;
    transition: color 0.2s;
  }

  .btn-save:hover {
    color: #5a63a8;
  }

  .btn-close-modal {
    position: absolute;
    top: 16px;
    right: 20px;
    background: transparent;
    border: none;
    font-size: 22px;
    color: #7c84d0;
    cursor: pointer;
    font-weight: 700;
    line-height: 1;
  }

  .btn-close-modal:hover {
    color: #e53e3e;
  }

  /* Alert sukses */
  .alert-success {
    background: #d1fae5;
    border: 1.5px solid #6ee7b7;
    color: #065f46;
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  tr:nth-child(even) td {
    background: #f3f4fc;
  }

  tr:nth-child(odd) td {
    background: white;
  }
</style>

{{-- Alert sukses --}}
@if(session('success'))
<div class="alert-success">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
  </svg>
  {{ session('success') }}
</div>
@endif

{{-- Tombol tambah + --}}
<div class="mb-3">
  <button class="btn-add" id="btnAddOpen" title="Tambah Data Kerusakan">+</button>
</div>

{{-- Tabel --}}
<div class="rounded-2xl overflow-hidden shadow-sm">
  <div class="table-header px-5 py-3">
    <span class="font-bold text-sm">Tabel Data Penyakit</span>
  </div>
  <div class="bg-white overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#f0f1fa;">
          <th class="text-left px-5 py-3 font-bold text-gray-700 w-16">ID</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Data Kerusakan</th>
          <th class="text-right px-5 py-3 font-bold text-gray-700 w-44">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kerusakans as $item)
        <tr>
          <td class="px-5 py-3 font-semibold text-gray-700">{{ $loop->iteration }}</td>
          <td class="px-5 py-3 text-gray-700">{{ $item->nama_kerusakan }}</td>
          <td class="px-5 py-3 text-right">
            {{-- Tombol Hapus --}}
            <form action="{{ route('admin.kerusakan.destroy', $item->id) }}" method="POST"
              class="inline-block"
              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-hapus mr-2">Hapus</button>
            </form>

            {{-- Tombol Ubah --}}
            <button type="button" class="btn-ubah"
              data-id="{{ $item->id }}"
              data-nama="{{ $item->nama_kerusakan }}"
              onclick="openEditModal(this.dataset.id, this.dataset.nama)">
              Ubah
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" class="px-5 py-8 text-center text-gray-400 font-medium">
            Belum ada data kerusakan. Klik <strong>+</strong> untuk menambahkan.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


{{-- ===== MODAL TAMBAH ===== --}}
<div class="modal-overlay" id="modalTambah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalTambah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">
      Masukkan Kerusakan pada Kendaraan?
    </h2>

    <form action="{{ route('admin.kerusakan.store') }}" method="POST" id="formTambah">
      @csrf
      <div>
        <input
          type="text"
          name="nama_kerusakan"
          id="inputTambah"
          class="modal-input {{ $errors->has('nama_kerusakan') && !session('edit_id') ? 'is-invalid' : '' }}"
          value="{{ old('nama_kerusakan') }}"
          placeholder="Nama kerusakan..."
          autocomplete="off" />
        @error('nama_kerusakan')
        @if(!session('edit_id'))
        <p class="text-red-500 text-sm font-bold mt-2">{{ $message }}</p>
        @endif
        @enderror
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>


{{-- ===== MODAL UBAH ===== --}}
<div class="modal-overlay" id="modalUbah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalUbah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">
      Ubah Kerusakan pada Kendaraan?
    </h2>

    <form id="formUbah" method="POST">
      @csrf
      @method('PUT')
      <div>
        <input
          type="text"
          name="nama_kerusakan"
          id="inputUbah"
          class="modal-input {{ $errors->has('nama_kerusakan') && session('edit_id') ? 'is-invalid' : '' }}"
          value="{{ session('edit_id') ? old('nama_kerusakan') : '' }}"
          placeholder="Nama kerusakan..."
          autocomplete="off" />
        @error('nama_kerusakan')
        @if(session('edit_id'))
        <p class="text-red-500 text-sm font-bold mt-2">{{ $message }}</p>
        @endif
        @enderror
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>

@endsection


@push('scripts')
<script>
  // ===== MODAL HELPERS =====
  function openModal(id) {
    const overlay = document.getElementById(id);
    overlay.classList.add('active');
    setTimeout(() => {
      const input = overlay.querySelector('input[name="nama_kerusakan"]');
      if (input) input.focus();
    }, 200);
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('active');
  }

  // Klik di luar box → tutup
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
      if (e.target === this) {
        this.classList.remove('active');
      }
    });
  });

  // ESC → tutup semua modal
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
  });

  // ===== TOMBOL + → buka modal tambah =====
  document.getElementById('btnAddOpen').addEventListener('click', function() {
    openModal('modalTambah');
  });

  // ===== UBAH → isi form ubah lalu buka modal =====
  function openEditModal(id, nama) {
    const form = document.getElementById('formUbah');
    const input = document.getElementById('inputUbah');

    // Set action URL dengan ID
    form.action = '/admin/kerusakan/' + id;
    input.value = nama;

    openModal('modalUbah');
  }

  // ===== AUTO-BUKA MODAL jika ada error validasi =====
  @if($errors -> has('nama_kerusakan') && !session('edit_id'))
  openModal('modalTambah');
  @endif

  @if($errors -> has('nama_kerusakan') && session('edit_id'))
  openEditModal({
    {
      session('edit_id')
    }
  }, @json(old('nama_kerusakan')));
  @endif
</script>
@endpush