<h1>Bukti Pembayaran</h1>

<h2>Halo {{ $data['name'] }}</h2>
<p>
@if($data['status'] == 'success')
    Terima kasih, pembayaran untuk pesanan Toimoi nomor {{ $data['toimoicode'] }} telah diterima<br />
@endif

Berikut adalah detail pembayaran via {{ $data['paymethod'] }}.
@if( $data['paymethod'] == 'transfer' )
<ul>
    <li>Nomor Rekening Pengirim : {{ $data['bank'].' '.$data['accountnumber'] }}</li>
    <li>Nama  : {{ $data['accountname'] }}</li>
    <li>Tanggal/Waktu : {{ $data['createdDate']  }}</li>
    <li>Jumlah : {{ $data['transferamount'] }}</li>
    <li>No Transaksi : {{ $data['transaction_code'] }}</li>
    <li>No. Telepon : {{ $data['phone'] }}</li>
    <li>Catatan : {{ $data['message'] }}</li>
</ul>

@else
<ul>
    <li>Tanggal/Waktu : {{ $data['createdDate']  }}</li>
    <li>No Transaksi : {{ $data['transaction_code'] }}</li>
    <li>Jumlah : {{ $data['transferamount'] }}</li>
    <li>Status : {{ $data['status'] }}</li>
</ul>
@endif

@if($data['status'] == 'success')
<p>
    Pesanan sedang dalam proses dan akan dikirimkan melalui kurir yang telah anda pilih.<br />
    Sebentar lagi anda akan mendapatkan email berisi nomor resi kurir dan tanggal pengiriman.
</p>
@else
<p>
    Silakan langsung menghubungi kami untuk metode pembayaran alternatif.
</p>
@endif
<p>
    Terima kasih!
</p>

<a href="http://www.toimoi.co.id">www. toimoi.co.id</a>
