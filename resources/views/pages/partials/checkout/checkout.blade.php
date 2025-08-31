@extends('layouts.guest.index')
@section('title', 'Form Pemesanan - PT.RAJAWALI PRIMA ANDALAS')
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-md-8">
                <h2 class="mb-4 text-success">Form Pemesanan</h2>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5>Daftar Produk:</h5>
                            <ul>
                                @foreach ($cartItems as $item)
                                    <li>{{ $item->produk->nama }} ({{ $item->quantity }}) -
                                        {{ rupiah($item->produk->harga) }}</li>
                                    <input type="hidden" name="checkout_items[]" value="{{ $item->id }}">
                                @endforeach
                            </ul>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" name="telepon" class="form-control"
                                        placeholder="Contoh: 081234567890" required>
                                    <small class="text-muted">Masukkan nomor telepon yang bisa dihubungi, tanpa spasi atau tanda hubung.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Negara</label>
                                    <input type="text" name="negara" class="form-control"
                                        placeholder="Contoh: Indonesia" required>
                                    <small class="text-muted">Negara tujuan pengiriman barang.</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3"
                                        placeholder="Masukkan alamat lengkap termasuk kota, provinsi, dan kode pos" required></textarea>
                                <small class="text-muted">Pastikan alamat lengkap agar pengiriman tidak tertunda.</small>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah" class="form-control" min="1"
                                        value="{{ $totalJumlah }}" readonly>
                                    <small class="text-muted">Jumlah total produk yang Anda pesan.</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total Berat</label>
                                    <input type="text" name="total_berat" class="form-control"
                                        value="{{ format_stok($totalBerat) }}" readonly>
                                    <small class="text-muted">Berat total semua produk, digunakan untuk menghitung ongkos kirim.</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Pengiriman</label>
                                <select name="jenis_pengiriman" id="jenis_pengiriman" class="form-select" required>
                                    <option value="ditanggung_pembeli">Ditanggung Pembeli</option>
                                    <option value="ditanggung_penjual">Ditanggung Penjual</option>
                                    <option value="ditanggung_bersama">Ditanggung Bersama</option>
                                </select>
                                <div class="mt-2">
                                    <small id="info_pengiriman" class="text-muted d-block">
                                        Pilih siapa yang menanggung biaya pengiriman. Ini akan mempengaruhi total harga akhir.
                                    </small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="total_harga_display" class="form-control"
                                        value="{{ rupiah($totalHarga) }}" readonly>
                                    <input type="hidden" name="total_harga" value="{{ $totalHarga }}">
                                </div>
                                <div class="mt-2">
                                    <small id="info_harga" class="text-info d-block fw-bold">
                                        Total harga termasuk biaya produk dan ongkos kirim sesuai pilihan pengiriman.
                                    </small>
                                </div>
                            </div>


                            <input type="hidden" name="no_resi" value="">
                            <input type="hidden" name="status" value="menunggu">
                            <input type="hidden" name="id_pelanggan" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="buy_now" value="{{ $buyNow ?? false }}">
                            <input type="hidden" name="produk_id" value="{{ $produkId ?? '' }}">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Proses Pesanan</button>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
