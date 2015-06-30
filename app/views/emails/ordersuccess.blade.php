<h1>Bukti Pemesanan</h1>
<h2>Halo {{ $data['name'] }}</h2>
<p>
Terima kasih telah berbelanja di toimoi.co.id.<br />
Berikut adalah detail pemesanan kamu.
<ul>
    <li>Nomor Pesanan : {{ $data['session_id'] }}</li>
    <li>Tanggal/Waktu Pemesanan : </li>
    <li>Nama Lengkap : {{ $data['by_name'] }}</li>
    <li>Email : {{ $data['email'] }}</li>
    <li>Alamat Pengiriman : {{ $data['by_address'] }}</li>
    <li>No. Telepon : {{ $data['by_phone'] }}</li>
</ul>
{{ $data['itemtable'] }}
</p>
<p>
    <b>Pembayaran</b><br />
    Lakukan pembayaran ke salah satu rekening berikut:<br />
    Bank BCA<br />
    No. Rekening 286 301 3348<br />
    Atas Nama PT Toimoi Indonesia<br />
    Cabang Kemang
</p>

<p>
    Kami akan memproses order kamu setelah kami menerima bukti atau pembayaran yang telah kamu lakukan.</p>
<p>
    Bila dalam waktu 2x24 jam dari waktu pemesanan kami tidak menerima bukti atau konfirmasi pembayaran dari kamu, kami menganggap kamu telah membatalkan order kamu.<br />
    Silahkan untuk memesan ulang kembali melalui www.toimoi.co.id
</p>
<p>
    <b>PERHATIAN:</b><br />
    Untuk menghindari penipuan, berhati-hatilah terhadap email, maupun sms yang mengatas namakan Toimoi Indonesia dengan nomor rekening berbeda. Toimoi Indoneisa tidak bertanggung-jawab atas segala jenis transaksi yang tidak melalui nomor rekening di atas.
</p>
<p>
    <b>Konfirmasi Pembayaran</b><br />
    Setelah melakukan pembayaran, silakan konfirmasi melalui email ke ask@toimoi.co.id<br />
    Format Email : KONFIRMASI Nomor Pesanan Nama Tanggal Transfer Nama Pembayar (pemilik rekening)<br />
    Terima kasih atas perhatian dan kepercayaan kamu.
</p>

<a href="http://www.toimoi.co.id">www. toimoi.co.id</a>
