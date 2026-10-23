<div>

<x-admin.topbar judul="Dashboard" sub="Ringkasan data Disporabudpar Kabupaten Jember" />
<div id="view"><div class="stats">
<div class="card stat"><small>Destinasi wisata</small><b>{{ $jumlahDestinasi }}</b><span>{{ $jumlahUnggulan }} unggulan</span></div>
<div class="card stat"><small>Berita terbit</small><b>{{ $jumlahBerita }}</b><span>{{ $jumlahDraf }} draf</span></div>
<div class="card stat"><small>Kunjungan hotel (Sep)</small><b>3.310</b><span>domestik + mancanegara</span></div>
<div class="card stat"><small>Pelaku ekraf dan kelompok seni</small><b>6</b><span>terdata</span></div></div>
<div class="grid2"><div class="card"><h3>Kunjungan wisata per bulan, 2026 (contoh)</h3><div class="bars"><div class="bar"><div class="b" style="height:18px"></div><div class="a" style="height:93px"></div>Jan</div><div class="bar"><div class="b" style="height:12px"></div><div class="a" style="height:87px"></div>Feb</div><div class="bar"><div class="b" style="height:24px"></div><div class="a" style="height:106.5px"></div>Mar</div><div class="bar"><div class="b" style="height:18px"></div><div class="a" style="height:99px"></div>Apr</div><div class="bar"><div class="b" style="height:30px"></div><div class="a" style="height:120px"></div>Mei</div><div class="bar"><div class="b" style="height:24px"></div><div class="a" style="height:111px"></div>Jun</div><div class="bar"><div class="b" style="height:36px"></div><div class="a" style="height:138px"></div>Jul</div><div class="bar"><div class="b" style="height:30px"></div><div class="a" style="height:132px"></div>Agu</div><div class="bar"><div class="b" style="height:24px"></div><div class="a" style="height:118.5px"></div>Sep</div></div><div class="leg"><span><i></i>Domestik (ribu)</span><span><i class="b"></i>Mancanegara (ribu)</span></div></div>
<div class="card"><h3>Destinasi unggulan</h3>@forelse ($unggulan as $d)<div class="li"><div class="th">{{ $d->kategori?->ikon ?? "📍" }}</div><div><b>{{ $d->nama }}</b><small>{{ $d->alamat }}, Kec. {{ $d->kecamatan?->nama }}</small></div></div>@empty<div class="empty">Belum ada destinasi unggulan.</div>@endforelse</div></div>

</div>
