<?
/* *******************************************************************************************************
MODUL NAME 			: 
FILE NAME 			: string.func.php
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Functions to handle string operation
***************************************************************************************************** */



/* fungsi untuk mengatur tampilan mata uang
 * $value = string
 * $digit = pengelompokan setiap berapa digit, default : 3
 * $symbol = menampilkan simbol mata uang (Rupiah), default : false
 * $minusToBracket = beri saudara kurung pada nilai negatif, default : true
 */
function setinfo($kondisi="")
{
	if($kondisi == 8 or $kondisi == 12)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Logika Sub test 1</div>
			
			<div class="keterangan">
			<p>Pada sub tes ini, saudara akan menemukan 4 kotak soal dan 6 kotak pilihan jawaban. Tugas saudara hanya memilih 1 jawaban yang sesuai untuk mengisi kotak yang kosong. Tekan pada pilihan jawaban yang menurut saudara benar</p>
			<p style="color: red">Jika dalam pengerjaan terdapat kesalahan menjawab, saudara bisa menekan tabel yang berisi nomer dari soal. Kemudian benarkan jawaban saudara</p>
			<span><img src="images/selesai.png" align="center" style="padding-top: 20px; padding-bottom: 20px"></span>
			
			
			<p>Jika sudah siap tekan tombol <img src="images/contoh/ok.jpg">di bawah ini</p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 9 or $kondisi == 13)
	{
		$statement= '
		<div class="area-instruksi">
			<div class="judul">Tes Logika Sub test 2</div>
			
			<div class="keterangan">
			
			<p>Tugas saudara memilih <b> 2</b> yang berbeda dari <b>5</b> gambar yang terdapat dalam kotak.</p>
			<p>Tekan pada jawaban yang menurut saudara benar</p>
			<p>Jika sudah siap tekan tombol <img src="images/contoh/ok.jpg">di bawah ini</p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 10 or $kondisi == 14)
	{
		$statement= '
		<div class="area-instruksi">
			<div class="judul">Tes Logika Sub test 3</div>
			
			<div class="keterangan">
			<p>Pada subtes ini Saudara akan menemukan kotak besar yang berisi 4 kotak kecil. 3 dari kotak-kotak kecil itu sudah terisi, tetapi ada 1 kotak kecil yang kosong.</p> 
			<p>Tugas saudara adalah memilih 1 jawaban yang sesuai di antara 6 kotak kecil di bawah gambar soal. Tekan pada pilihan jawaban yang menurut saudara benar. Lanjutkan dengan contoh lainnya.</p>
			<p>Jika sudah siap tekan tombol <img src="images/contoh/ok.jpg">di bawah ini</p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 11 or $kondisi == 15)
	{
		$statement= '
		<div class="area-instruksi">
			<div class="judul">Tes Logika Sub test 4</div>
			
			<div class="keterangan">
			<p>Pada subtes ini saudara diminta membayangkan ada titik di setiap kotak soal.</p>
			<p>Tugas saudara adalah mencari titik dan mencari prinsip dari titik tersebut. Pilih satu (1) jawaban yang menurut saudara sesuai diantara lima (5) pilihan jawaban dan tekan pada pilihan jawaban tersebut</p>
			
			<p>Gunakanlah imajinasi Saudara untuk mencari kemungkinan letak titik tersebut.</p>

		<p>Jika sudah siap tekan tombol <img src="images/contoh/ok.jpg">di bawah ini</p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
// 	elseif($kondisi == 12)
// 	{
// 		$statement= '
// 		<div class="area-instruksi">
		 
// 			<div class="judul">Tes Logika B Sub test 1</div>
			
// 			<div class="keterangan"> 
// 			<p>Lihatlah gambar-gambar pada contoh pada tes ini. Kita lihat contoh pada baris pertama. Disitu Saudara lihat lingkaran-lingkaran dari yang terbesar berurutan sampai yang paling kecil, mulai dari gambar di kotak pertama sampai pada kotak ketiga.</p>
		 
// 			<span><img src="images/contoh/b_subtes1_contoh1.jpg"></span>
		 
			
// 			<p>Dapatkah Saudara memilih 1 (satu) kotak selanjutnya diantara 6 (enam) kotak yang telah tersedia?</p>
			
// 			<p>Kotak <b>"c"</b> adalah yang tepat untuk gambar lingkaran yang paling terkecil. Maka pilihlah <b>"c"</b> untuk jawaban Saudara.</p>
			
		 		
// 			<p>Sekarang lihatlah contoh kedua. Saudara dapat lihat beberapa batang kayu (stick) yang berdiri sejajar sama tinggi, dari satu batang lalu bertambah menjadi dua batang, tiga dan seterusnya bertambah satu persatu. Tentunya Saudara tahu, di kotak mana yang berisi kayu lebih banyak.</p>
			
// 			<span><img src="images/contoh/b_subtes1_contoh2.jpg"></span>
			
// 			<p>Kotak <b>"e"</b> adalah yang tepat untuk gambar selanjutnya, maka pilihlah jawaban <b>"e"</b>.</p>
			
// 			<p>Sekarang kita beralih pada contoh ketiga. Lihatlah gambar seperti dua stick golf dan sebuat titik di atara keduanya. Dari tegak, miring dan makin lama merebah. Bagaimana Saudara mengetahui kelanjutan dari gambar-gambar tersebut?</p>
			
//              <span><img src="images/contoh/b_subtes1_contoh3.jpg"></span>
						
// 			<p>Kotak <b>"e"</b> adalah yang tepat untuk gambar selanjutnya.</p>
			
// 			<p>Mulailah dari no. 1 – 13. <b>Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</b></p>
			
// 			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
// 			</div>

// 		</div>
// 		';
// 	}
// 	elseif($kondisi == 13)
// 	{
// 		$statement= '
// 		<div class="area-instruksi">
		 
// 			<div class="judul">Tes Logika B Sub test 2</div>
			
// 			<div class="keterangan"> 
// 			<p>Lihatlah gambar-gambar contoh pada baris pertama. Disitu ada 3 (tiga) kotak yang mempunyai bentuk gambar yang sama, tetapi 2 (dua) kotak lain yang mempunyai bentuk gambar yang berbeda.
// Tugas Saudara adalah mencari pada setiap baris soal, 2 kotak yang gambarnya berbeda dengan gambar 3 kotak lainnya. Apabila Saudara telah menemukan 2 kotak yang berbeda tersebut, maka pilihlah 2 (dua) huruf jawaban yang mewakili kotak tersebut.
// </p>
			
// 			<span><img src="images/contoh/b_subtes2_contoh1.jpg"></span>
			
// 			<p>Pada gambar di garis pertama, Saudara melihat ada 3 (tiga) kotak yang gambarnya seperti beduk dengan lingkaran hitam di atasnya, dan 2 kotak lain yang bergambar <ul>lingkaran</ul>. Jadi, 2 kotak yang bergambar <ul>lingkaran</ul> itulah jawabannya.</p>
			
// 			<p>Kedua gambar itu berada di kotak dengan huruf <b>"b"</b> dan <b>"d"</b>. Maka Saudara harus memilih jawaban  <b>"b"</b> dan <b>"d"</b>.</p>
			
		 		
// 			<p>Sekarang lihatlah contoh pada baris kedua. Carilah 2 (dua) gambar yang berbeda dengan 3 (tiga) gambar lainnya.</p>
			
// 			<span><img src="images/contoh/b_subtes2_contoh2.jpg"></span>
			
// 			<p>Jawabannnya adalah <b>"c"</b> dan <b>"e"</b>.</p>
					 
// 			<p>Sekali lagi, tugas Saudara adalah memilih 2 kotak atau 2 gambar yang berbeda dengan gambar-gambar yang lainnya pada setiap baris soal. Jadi, saudara harus memilih 2 jawaban.</p>
			
// 			<p><b>Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</b></p>
			
// 			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
// 			</div>

// 		</div>
// 		';
// 	}
// 	elseif($kondisi == 14)
// 	{
// 		$statement= '
// 		<div class="area-instruksi">
		 
// 			<div class="judul">Tes Logika B Sub test 3</div>
			
// 			<div class="keterangan"> 
// 			<p>Perhatikan gambar-gambar yang berisi 3 garis tegak lurus di dalam setiap kotak, tapi ada 1 kotak yang kosong atau hilang. Kotak yang hilang ini terletak di antara 6 kotak kecil yang berada di sebelah kanan. Pilihlah salah satu.
// </p>
			
// 			<span><img src="images/contoh/b_subtes3_contoh1.jpg"></span>
			
// 			<p>Perhatikan, kotak kedua lah yang tepat untuk mengisi kotak yang kosong itu. Kotak kedua ini terletak pada huruf <b>"b"</b>. Maka pilihlah jawaban <b>"b"</b>.</p>
			
		 
// 			<p>Sekarang lihatlah contoh kedua. Manakah jawaban yang benar?</p>
			
// 			<span><img src="images/contoh/b_subtes3_contoh2.jpg"></span>
			
// 			<p>Kotak yang terletak pada huruf <b>"c"</b> yang tepat untuk mengisi kotak kecil yang hilang tersebut, karena yang hilang itu adalah gambar <ul>telunjuk tangan berbintik</ul> yang menghadap ke kanan. Maka pilihlah jawaban sesuai dengan yang saudara anggap benar tersebut.</p>
					 
// 			<p>Sekarang perhatikan kotak ketiga. Di dalam kotak yang besar, berisi 4 (empat) kotak yang terdapat garis-garis tebal. Kotak yang 1 (satu) garis tebal berwarna berpasangan dengan kotak yang bergaris 2 (dua) tebal berwarna pula. Sedangkan dibawahnya ada 1 (satu) kotak yang bergaris satu putih, Saudara harus mencari kotak pasangan yang bergaris dua putih.</p>
			
// 			<span><img src="images/contoh/b_subtes3_contoh3.jpg"></span>
			
// 			<p>Kotak yang terletak pada  huruf <b>"f"</b> itulah jawabannya.</p>
			
// 			<b><p>Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</p></b>
			
// 			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
// 			</div>

// 		</div>
// 		';
// 	}
// 	elseif($kondisi == 15)
// 	{
// 		$statement= '
// 		<div class="area-instruksi">
		 
// 			<div class="judul">Tes Logika B Sub test 4</div>
			
// 			<div class="keterangan"> 
// 			<p>Lihatlah gambar contoh yang pertama. Saudara akan melihat 1 (satu) gambar di sebelah kiri dan 5 (lima) gambar di sebelah kanannya.
// Pada gambar di sebelah kiri, Saudara lihat di dalam kotak ada gambar lingkaran, kotak persegi panjang dan sebuah titik. Perhatikan titik tersebut. titik tersebut berada di dalam kotak persegi panjang namun di luar lingkaran.
// </p>
// 			<p>Sekarang lihatlah pada 5 gambar yang berada di sebelah kanan. Gambar manakah yang mungkin diletakkan sebuah titik di dalam kotak persegi panjang namun di luar lingkaran?</p>
			
// 			<span><img src="images/contoh/b_subtes4_contoh1.jpg"></span>
			
// 			<p>Jawabannya adalah gambar yang terletak di atas huruf <b>"c"</b>.</p>
			
		 
// 			<p>Ingat, Saudara diminta untuk mencari titik dalam kondisi yang sama seperti gambar soal (kiri).</p>
			
// 			<p>Sekarang lihat contoh kedua. Perhatikan gambar dalam kotak sebelah kiri. Dimanakah kemungkinan letak titik tersebut?</p>
			
// 			<span><img src="images/contoh/b_subtes4_contoh2.jpg"></span>
			
// 			<p>Titik tersebut berada di dalam 2 (dua) buah segitiga. Sekarang, lihatlah contoh ketiga. Lihat gambar pada kotak sebelah kiri. Dimanakah letak titik tersebut?</p>
			
// 			<p>Jawabannya adalah gambar yang terletak dibawah huruf <b>"d"</b>. Maka pilihlah jawaban <b>"d"</b>.</p>
					 
// 			<p>Contoh ketiga. Lihatlah gambar yang terletak di sebelah kiri. Dimanakah letak titik?
// Titik itu terletak di dalam segitiga, dan di atas garis lengkung. Sekarang lihat gambar-gambar di sebelah kanan. Carilah kemungkinan gambar yang letak titiknya serupa dengan yang dipersoalkan.
// </p>
			
// 			<span><img src="images/contoh/b_subtes4_contoh3.jpg"></span>
			
// 			<p>Gambar yang benar adalah yang berada pada huruf <b>"b"</b>.</p>
// 			<p>Gunakanlah imajinasi Saudara untuk mencari kemungkinan letak titik tersebut.</p>
			
// 			<b><p>Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</p></b>
			
// 			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
// 			</div>

// 		</div>
// 		';
// 	}
	elseif($kondisi == 3)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Pengetahuan Umum</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 4)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Bahasa Inggris</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 5)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Tes Mekanikal</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 6)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">TKD 1</div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 7)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Personality Test A <br><span style="color: red">(Waktu sangat terbatas dan soal harus terisi "seluruhnya. Kerjakan dengan cepat, teliti dan tepat)</span> </div>
			 
			<div class="keterangan"> 
			<p>Pada halaman-halaman berikut, saudara akan membaca sejumlah pernyataan dalam pasangan tentang berbagai hal yang mungkin menggambarkan diri saudara atau tidak menggambarkan diri saudara</p>
			<p>Tugas saudara adalah untuk menentukan mana yang menggambarkan tentang diri saudara. Tidak ada jawaban Salah. Kerjakan dengan teliti dan jangan ada soal yang terlewati </p>
			<p>Perhatikan contoh di bawah ini : </p>
			<ul style="border: 1px solid #000;">
				<p style="padding-left: 20px;"> ⚪Saya suka memberi petunjuk kepada orang bagaimana melakukan sesuatu</p>
				<p style="padding-left: 20px;"> ⚪Saya ingin melakukan sesuatu sebaik mungkin</p>
			</ul>
			<br>
			<p>Bila saudara lebih <strong>suka memberi petunjuk kepada orang tentang bagaimana melakukan sesuatu</strong> dari pada ingin melakukan sesuatu sebaik mungkin, maka saudara hendaknya menekan (klik) dalam kolom jawaban disamping soal yang saudara pilih</p>

			<p>Contoh :</p>
			<ul style="border: 1px solid #000;">
				<p style="padding-left: 20px;"> ⚫Saya suka memberi petunjuk kepada orang bagaimana melakukan sesuatu</p>
				<p style="padding-left: 20px;"> ⚪Saya ingin melakukan sesuatu sebaik mungkin</p>
			</ul>
			<br>
			<p>Tetapi bila saudara ingin <strong>melakukan sesuatu sebaik mungkin </strong>dari pada memberi petunjuk pada orang lain tentang bagaimana melakukan sesuatu, maka saudara hendaknya menekan (klik) dalam kolom jawaban disamping soal yang saudara pilih </p>

			<p>Contoh : </p>

			<ul style="border: 1px solid #000;">
				<p style="padding-left: 20px;"> ⚪Saya suka memberi petunjuk kepada orang bagaimana melakukan sesuatu</p>
				<p style="padding-left: 20px;"> ⚫Saya ingin melakukan sesuatu sebaik mungkin</p>
			</ul>
			<br>
			<p>Mungkin saudara menyukai kedua pernyataan tersebut. Dalam hal ini saudara tetap diharapkan untuk memilih. Hendaknya saudara memilih yang lebih saudara sukai. Sekiranya saudara tidak suka kedua-duanya, hendaknya saudara memilih yang tidak terlalu saudara sukai. Pilihan saudara setiap kali hendaknya didasarkan atas kesukaan saudara sekarang dan tidak didasarkan atas apa yang saudara anggap wajar. Ini bukan suatu tes. Disini tidak ada jawaban benar atau salah. Apa yang saudara pilih hendaknya merupakan suatu gambaran dari apa yang saudara suka lakukan. </p>


			<p>Apabila saudara sudah memahami,<b> tekan</b> tombol <img src="images/contoh/ok.jpg"> <b>Waktu akan berjalan setelah saudara menekan <img src="images/contoh/ok.jpg"></b>. Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat</p>
		

			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 17)
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">EPPS</div>
			
			<div class="keterangan"> 
			<p>Saudara akan membaca sejumlah pernyataan mengenai berbagai hal yang mungkin menggambarkan diri saudara</p>
			<p>atau mungkin juga tidak menggambarkan diri saudara, dan pernyataan-pernyataan tersebut selalu disajikan berpasangan</p>
			<p style="text-align: center"><strong>Perhatikan contoh di bawah ini : </strong></p>
			
			<ul style="list-style-type: upper-alpha">
				<li>Saya suka berbicara tentang diri saya dengan orang lain</li>
				<li>Saya suka bekerja untuk suatu tujuan yang telah saya tentukan bagi diri saya</li>
			</ul>

			<p>Manakah dari dua pernyataan tersebut, yang lebih menggambarkan diri saudara ?</p>
			<p>Bila saudara lebih mirip <strong>suka berbicara tentang diri saudara dengan orang lain </strong>, daripada</p>
			<p><strong>suka bekerja untuk suatu tujuan yang telah saudara tentukan bagi diri saudara</strong>, maka </p>
			<p>Saudara hendaknya memilih <strong>A</strong></p>
			<p>Tetapi bila saudara lebih mirip <strong>suka bekerja untuk suatu tujuan yang telah saudara tentukan bagi diri saudara </strong>daripada</p>
			<p><strong>suka berbicara dengan orang lain </strong>, maka hendaknya saudara memilih <strong>B</strong></p>

			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}

	elseif($kondisi == 16 || $kondisi == 43)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul">Tes Sikap Kerja</div>

		<div class="keterangan"> 
		<br>
		<table style="width: 100%;" >
		<tbody><tr style="/*! height: 300px; */">
		<td style=" width: 200px;">
		<p><strong>Contoh Soal Tes Sikap Kerja</strong></p>
		<img src="images/contoh/gambar2.gif"></td>
		<td style="text-align: top;text-align: justify;vertical-align: text-top; border: 1px solid #000; text-align: top;text-align: justify;vertical-align: text-top; border: 1px solid #000;padding-left: 20px;padding-top: 20px;padding-right: 20px;padding-bottom: 20px;" align="top">
		<p>&emsp;Tugas Saudara adalah menjumlahkan antara satu bilangan dengan satu bilangan lain tepat diatasnya yang terdapat dalam deretan angka yang terbagi dalam lajur-lajur. Saudara wajib menjumlahkan mulai dari lajur paling kiri dan paling bawah ke atas secara berurutan. Waktu yang diberikan di setiap lajur sangat terbatas. Oleh sebab itu, Saudara diminta menjumlahkan secara cepat, teliti dan tepat </p>
		<ul style="list-style-type: upper-alpha"> <strong>Tata Cara Pengerjaan :</strong>
			<li>Sebelum memulai, pastikan Saudara pahami <strong>letak angka</strong> pada laptop atau PC dimana Saudara akan gunakan terlebih dulu (hafalkan letak angka 1 hingga 0);</li>
			<li>Ketika akan <strong>menekan MULAI </strong>warna pada lajur menjadi abu-abu muda dan akan muncul deretan angka secara berurutan;</li>
			<li>Jika dalam penjumlahan angka, saudara menemukan hitungan yang jumlahnya belasan tulis <strong>angka satuan</strong>nya saja (Misal 13, cukup tekan angka 3);</li>			
			<li>Jika secara otomatis muncul notifikasi seperti berikut : <br> <span ><img src="images/pindah.jpg"  style="padding-top: 20px; padding-bottom: 20px"></span> <br> Artinya waktu pengerjaan pada lajur tersebut sudah habis. Saudara secara otomatis berpindah ke lajur samping kanan dan mulai lagi menghitung dari paling bawah ke atas secara berurutan;</li>
			<li>Apabila dalam mengerjakan hitungan, ada salah hitung, cukup pindahkan kursor pada kolom jawaban yang akan Saudara ganti</li>
			<li>Apabila saudara sudah memahami,<b> tekan</b> tombol <img src="images/contoh/ok.jpg"> <b>Waktu akan berjalan setelah saudara menekan <img src="images/contoh/ok.jpg"></b>. Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat</li>
			
		</ul>
		</td>
		<br>
		</tbody>
		</table>
		


		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">

		</div>

		</div>
		';
	}
	elseif($kondisi == 19)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 01</p>
		<p>(Soal-soal No. 01-20)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Soal-soal 01 - 20 terdiri atas kalimat-kalimat. <br> Pada setiap kalimat satu kata hilang dan disediakan 5 (lima) kata pilihan sebagai penggantinya. <br> Pilihlah kata yang tepat yang dapat menyempurnakan kalimat itu ! </p>
		<br>
		<br>
		<b>Contoh 01</b>
		<br>
		<br>
		<p>Seekor kuda mempunyai kesamaan terbanyak dengan seekor .................. </p>
		<p> a) kucing &nbsp &nbsp b) bajing &nbsp &nbsp c) keledai &nbsp &nbsp d) lembu &nbsp &nbsp e) anjing</p>
		<br>
		<p> Jawaban yang benar ialah : c) keledai</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> dibelakang contoh 01,huruf c harus dicoret.</p>
		<br>
		<p style="text-align:center"> 01)&nbsp &nbsp a &nbsp &nbsp b &nbsp &nbsp <s> c </s> &nbsp &nbsp d  &nbsp &nbsp e</p>
		<br>
		<br>
		<b>Contoh berikutnya :</b>
		<br>
		<br>
		<p>Lawannya "harapan" ialah ................</p>
		<p> a) duka &nbsp &nbsp b) putus asa &nbsp &nbsp c) sengsara &nbsp &nbsp d) cinta &nbsp &nbsp e) benci</p>
		<br>
		<p> Jawabannya ialah : &nbsp b) putus asa</p>
		<p> Maka Huruf b yang seharusnya dicoret</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 20)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 02</p>
		<p>(Soal-soal No. 21-40)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Ditentukan 5 kata. <br> Pada 4 dari 5 kata itu terdapat suatu kesamaan. <br> Carilah kata yang kelima yang tidak memiliki kesamaan dengan keempat kata itu.</p>
		<br>
		<br>
		<b>Contoh 02</b>
		<br>
		<br>
		<p> a) meja &nbsp &nbsp b) kursi &nbsp &nbsp c) burung &nbsp &nbsp d) lemari &nbsp &nbsp e) tempat tidur</p>
		<br>
		<p> a), b), d), dan e) ialah perabot rumah (meubel)</p>
		<p> c) burung, bukan perabot rumah atau tidak memiliki kesamaan dengan keempat kata itu.</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> dibelakang contoh 02,huruf c harus dicoret.</p>
		<br>
		<p style="text-align:center"> 02)&nbsp &nbsp a &nbsp &nbsp b &nbsp &nbsp <s> c </s> &nbsp &nbsp d  &nbsp &nbsp e</p>
		<br>
		<br>
		<b>Contoh berikutnya :</b>
		<br>
		<br>
		<p> a) duduk &nbsp &nbsp b) berbaring &nbsp &nbsp c) berdiri &nbsp &nbsp d) berjalan &nbsp &nbsp e) berjongkok</p>
		<br>
		<p> Pada a), b), c), dan e) orang berada dalam keadaan tidak bergerak, sedangkan d) orang dalam keadaan bergerak.</p>
		<p> Maka Jawaban yang benar ialah : &nbsp d) berjalan</p>
		<p> Oleh karena itu huruf d yang seharusnya dicoret.</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 21)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 03</p>
		<p>(Soal-soal No. 41-60)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Ditentukan 3 (tiga) kata. 
			<br> Antara kata pertama dan kata kedua terdapat suatu hubungan yang tertentu. 
			<br> Antara kata ketiga dan salah satu diantara lima kata pilihan harus pula terdapat hubungan yang sama itu.
			<br> carilah kata itu.
		</p>
		<br>
		<br>
		<b>Contoh 03</b>
		<br>
		<br>
		<p>Hutan : pohon = tembok : ? </p>
		<p> a) batu bata &nbsp &nbsp b) rumah &nbsp &nbsp c) semen &nbsp &nbsp d) putih &nbsp &nbsp e) dinding</p>
		<br>
		<p> Hubungan antara hutan dan pohon ialah bahwa hutan terdiri atas pohon-pohon, maka hubungan antara tembok dan salah satu kata pilihan ialah bahwa tembok terdiri atas batu-batu bata.</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> dibelakang contoh 03,huruf a harus dicoret.</p>
		<br>
		<p style="text-align:center"> 03)&nbsp &nbsp <s> a </s> &nbsp &nbsp b &nbsp &nbsp  c  &nbsp &nbsp d  &nbsp &nbsp e</p>
		<br>
		<br>
		<b>Contoh berikutnya :</b>
		<br>
		<br>
		<p>Gelap : terang = basah : ?</p>
		<p> a) hujan &nbsp &nbsp b) hari  &nbsp &nbsp c) lembab &nbsp &nbsp d) angin &nbsp &nbsp e) kering</p>
		<br>
		<p> Gelap ialah lawannya dari terang, maka untuk basah lawannya ialah kering.</p>
		<p> Maka jawaban yang benar ialah : &nbsp e) kering</p>
		<p> Oleh karena itu huruf e yang seharusnya dicoret</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 22)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 04</p>
		<p>(Soal-soal No. 61-76)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Ditentukan dua kata. <br>Carilah satu perkataan yang meliputi pengertian kedua kata tadi. <br>Tuliskan perkataan itu pada lembar jawaban di belakang nomor soal yang sesuai.</p>
		<br>
		<br>
		<br>
		<b>Contoh 04</b>
		<br>
		<br>
		<p>Ayam - itik</p>
		<br>
		<p>Perkataan "burung" dapat meliputi pengertian kedua kata itu</p>
		<p>Maka jawabannya ialah "burung"</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> dibelakang contoh 04, harus ditulis "burung".</p>
		<br>
		<br>
		<p style="text-align:center"> 04) &nbsp burung</p>
		<br>
		<br>
		<b>Contoh berikutnya :</b>
		<br>
		<br>
		<p>Gaun - celana</p>
		<br>
		<p>Pada contoh ini jawabannya ialah "pakaian", maka "pakaian" yang seharusnya ditulis.</p>
		<p>Carilah selalu perkataan yang tepat yang dapat meliputi pengertian kedua kata itu.</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 23)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 05</p>
		<p>(Soal-soal No. 77-96)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Persoalan berikutnya ialah soal-soal hitungan.</p>
		<br>
		<br>
		<br>
		<b>Contoh 05</b>
		<br>
		<br>
		<p>Sebatang pensil harganya 25 rupiah. Berapakah harga 3 batang ?</p>
		<br>
		<br>
		<p>jawabannya ialah : 75</p>
		<br>
		<br>
		<p>Perhatikan cara menjawab di atas lembar jawaban !</p>
		<br>
		<br>
		<p>Pada lembar jawaban lihatlah pada kolom 05. <br>kolom ini Terdiri atas angka-angka 1 sampai 9 dan 0<br>Untuk menunjukkan jawaban suatu soal, maka coretlah angka-angka yang terdapat di dalam jawaban itu.<br>Keurutan angka jawaban tidak perlu dihiraukan.</p>
		<br>
		<p>Pada contoh 05, jawaban ialah 75.</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> dibelakang contoh 05, angka 7 dan 5 harus di coret.</p>
		<br>
		<br>
		<p style="text-align:center"> 05) 1 &nbsp &nbsp 2 &nbsp &nbsp 3 &nbsp &nbsp 4 &nbsp &nbsp <s> 5 </s> &nbsp &nbsp 6 &nbsp &nbsp <s> 7 </s> &nbsp &nbsp 8 &nbsp &nbsp 9 &nbsp &nbsp 10 &nbsp</p>
		<br>
		<br>
		<b>Contoh lain :</b>
		<br>
		<br>
		<p>Dengan sepeda Husin dapat mencapai 15 km dalam waktu 1 jam, Berapa km-kah yang dapat ia capai dalam waktu 4 jam ?</p>
		<br>
		<p>Jawabannya ialah : 60</p>
		<p>Maka untuk menunjukkan jawaban itu angka 6 dan 0 yang seharusnya dicoret.</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 24)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 06</p>
		<p>(Soal-soal No. 97-116)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Pada persoalan berikut akan diberikan deret angka. 
			<br> Setiap deret tersusun menurut suatu aturan yang tertentu dan dapat dilanjutkan menurut aturan itu. 
			<br> Carilah untuk setiap deret, angka berikutnya dan coretlah jawaban saudara pada lembar jawaban dibelakang nomor setiap soal yang sesuai.
		</p>
		<br>
		<br>
		<b>Contoh 06</b>
		<br>
		<br>
		<p> 2 &nbsp &nbsp 4 &nbsp &nbsp 6 &nbsp &nbsp 8 &nbsp &nbsp 10 &nbsp &nbsp 12 &nbsp &nbsp 14 &nbsp &nbsp ?</p>
		<br>
		<p> Pada deret ini angka berikutnya selalu didapat jika angka didepannya ditambah dengan 2.</p>
		<p> Maka jawabannya ialah 16,</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> dibelakang contoh 06, angka 1 dan 6 harus dicoret.</p>
		<br>
		<p style="text-align:center"> 06)&nbsp &nbsp <s> 1 </s> &nbsp &nbsp 2 &nbsp &nbsp  3  &nbsp &nbsp 4  &nbsp &nbsp 5 &nbsp &nbsp <s> 6 </s> 
		&nbsp &nbsp 7 &nbsp &nbsp 8 &nbsp &nbsp 9 &nbsp &nbsp 0</p>
		<br>
		<br>
		<b>Contoh berikutnya :</b>
		<br>
		<br>
		<p> 9 &nbsp &nbsp 7 &nbsp &nbsp 10 &nbsp &nbsp 8 &nbsp &nbsp 11 &nbsp &nbsp 9 &nbsp &nbsp 12 &nbsp &nbsp ?</p>
		<br>
		<p> Pada deret ini selalu berganti-ganti harus dikurangi dengan 2 dan setelah itu ditambah dengan 3</p>
		<p> jawaban contoh ini ialah : 10, maka dari itu angka 1 dan 0 seharusnya yang dicoret</p>
		<br>
		<p> Kadang-kadang pada beberapa soal harus pula dikalikan atau dibagi.</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 25)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 07</p>
		<p>(Soal-soal No. 117-136)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Pada persoalan berikutnya, setiap soal memperlihatkan sesuatu bentuk tertentu yang terpotong menjadi beberapa bagian. 
			<br> Carilah di antara bentuk-bentuk yang ditentukan (a,b,c,d,e), bentuk yang dapat dibangun dengan cara menyusun potongan-potongan itu sedemikian rupa, sehingga tidak ada kelebihan sudut atau ruang diantaranya. 
			<br> Carilah bentuk-bentuk itu dan coretlah huruf yang menunjukkan bentuk tadi <b>pada lembar jawaban</b> dibelakang nomor soal yang sesuai.
		</p>
		<br>
		<img src="images/contoh/soal7.png">
		<br>
		<br>
		<b>Contoh 07</b>
		<br>
		<br>
		<p> Jika potongan-potongan pada contoh 07 diatas disusun (digabungkan), maka akan menghasilkan bentuk a.</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> di belakang contoh 07, huruf a harus dicoret</p>
		<br>
		<p style="text-align:center"> 07)&nbsp &nbsp <s> a </s> &nbsp &nbsp b &nbsp &nbsp  c  &nbsp &nbsp d  &nbsp &nbsp e</p>
		<br>
		<br>
		<b>Contoh berikutnya :</b>
		<br>
		<br>
		<p> Potongan-potongan contoh kedua setelah disusun (digabungkan) menghasilkan bentuk e.</p>
		<p> Contoh ketiga menjadi bentuk b.</p>
		<p> Contoh keempat ialah bentuk d.</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 26)
	{
		$statement= '
		<div class="area-instruksi">

		<div class="judul" style="text-align:center">
		<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 08</p>
		<p>(Soal-soal No. 137-156)</p></b>
		</div>
		<br>
		<div class="keterangan"> 
		<p>Ditentukan 5(lima) buah kubus a,b,c,d,e. Pada tiap-tiap kubus terdapat enam saudara yang berlainan pada setiap sisinya. Tiga dari saudara itu dapat dilihat. 
			<br> Kubus-kubus yang ditentukan itu (a, b, c, d, e) ialah kubus-kubus yang berbeda, artinya kubus-kubus itu dapat mempunyai saudara-saudara yang sama, akan tetapi susunannya berlainan. setiap soal memperlihatkan salah satu kubus yang ditentukan di dalam kedudukan yang berbeda.
			<br> Carilah kubus yang dimaksudkan itu dan coretkanlah jawaban saudara <b>pada lembar jawaban</b> di belakang nomor soal yang sesuai.
			<br> Kubus itu dapat diputar, dapat digulingkan atau dapat diputar dan digulingkan dalam pikiran saudara.
			<br> Oleh karena itu mungkin akan terlihat suatu saudara yang baru.
		</p>
		<br>
		<img src="images/contoh/soal8.png">
		<br>
		<br>
		<b>Contoh 08</b>
		<br>
		<br>
		<p> Contoh ini memperlihatkan kubus a dengan kedudukan yang berbeda.</p>
		<p> Mendapatkannya adalah dengan cara menggulingkan lebih dahulu kubus itu ke kiri satu kali dan kemudian diputar ke kiri satu kali, sehingga sisi kubus yang bersaudara dua segi empat hitam terletak di depan, seperti kubus a.</p>
		<p> Oleh karena itu, <b>pada lembar jawaban</b> di belakang contoh 08, huruf a harus dicoret</p>
		<br>
		<p style="text-align:center"> 08)&nbsp &nbsp <s> a </s> &nbsp &nbsp b &nbsp &nbsp  c  &nbsp &nbsp d  &nbsp &nbsp e</p>
		<br>
		<br>
		<b>Contoh berikutnya :</b>
		<br>
		<br>
		<p> Contoh kedua adalah kubus e</p>
		<p> Cara mendapatkannya adalah dengan digulingkan ke kiri satu kali dan diputar ke kiri satu kali,</p>
		<p> Sehingga sisi kubus yang bersaudara garis silang terletak di depan, seperti kubus e.</p>
		<br>
		<p> Contoh ketiga adalah kubus b</p>
		<p> Cara mendapatakannya adalah dengan menggulingkannya ke kiri satu kali, sehingga dasar kubus yang tadinya tidak terlihat memunculkan saudara baru (dalam hal ini adalah saudara dua segi empat hitam) dan saudara silang pada sisi atas kubus itu menjadi tidak terlihat lagi</p>
		<br>
		<p> Contoh keempat menjadi bentuk c.</p>
		<br>
		<p> Contoh kelima ialah bentuk d.</p>
		<br>
		<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
		</div>
		</div>
		';
	}
	elseif($kondisi == 27)
	{
		$statement= '
		<div class="area-instruksi">
		 	
			<div class="judul" style="text-align:center">
			<b><p>PETUNJUK DAN CONTOH UNTUK KELOMPOK SOAL 09</p>
			<p>(Soal-soal No. 157-176)</p></b>
			</div>
			<div class="keterangan"> 
			<p> Pada persoalan berikutnya, terdapat sejumlah pertanyaan mengenai kata-kata yang telah saudara hafalkan tadi. Coretlah jawaban saudara pada lembaran jawaban di belakang nomor soal yang sesuai.</p>
			<br>
			<br>
			<b>Contoh 09</b>
			<br>
			<br>
			<p> Kata yang mempunyai huruf permulaan - Q - adalah suatu .................</p>
			<br>
			<p> a) bunga &nbsp &nbsp b) perkakas &nbsp &nbsp c) burung &nbsp &nbsp d) kesenian &nbsp &nbsp e) binatang</p>
			<br>
			<p> Quintet adalah termasuk dalam jenis kesenian. sehingga jawaban yang benar adalah d.</p>
			<p> Oleh karena itu pada lembar jawaban di belakang contoh 09 huruf d harus dicoret</p>
			<br>
			<p style="text-align:center"> 09)&nbsp &nbsp a &nbsp &nbsp b &nbsp &nbsp  c  &nbsp &nbsp <s> d </s> &nbsp &nbsp e</p>
			<br>
			<br>
			<b>Contoh berikutnya :</b>
			<br>
			<br>
			<p> Kata yang mempunyai huruf permulaan - Z - adalah suatu .................</p>
			<br>
			<p> a) bunga &nbsp &nbsp b) perkakas &nbsp &nbsp c) burung &nbsp &nbsp d) kesenian &nbsp &nbsp e) binatang</p>
			<br>
			<p> Jawabannya adalah e, karena Zebra termasuk dalam jenis binatang.</p>
			<br>
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}

	elseif($kondisi == 42 )
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">Personality Test C <span style="color: red">(Wajib terisi seluruhnya)</span></div>
			
			<div class="keterangan"> 
			
			<p>Dalam test ini akan disediakan 4 kalimat yang akan saudara pilih 
			<p> Tugas saudara adalah memilih 1 + (PLUS) dan 1 - (MINUS)</p>

			<p>1. Klik ⚪ pada kolom + (PLUS) disamping kalimat yang <strong>paling</strong> menggambarkan diri saudara</p>
			<p>2. Klik ⚪ pada kolom - (MINUS) disamping kalimat yang <strong>kurang</strong> menggambarkan diri saudara </p>
			<p>Setiap nomor hanya ada 1 jawaban + (plus) dan 1  jawaban – (minus).</p>
			<p>Tidak ada jawaban salah. Bacalah instruksi dengan teliti, apabila sudah jelas, tekan OK. Waktu akan berjalan ketika saudara menekan OK. Kerjakan dengan teliti dan jangan ada soal yang terlewati. </p>

			<p>Perhatikan contoh di bawah ini : </p>
			<table style="border: 1px solid #000; text-align: center;">
			  <tr>
			    <td style="font-size: 28px;">+</td>
			    <td></td>
			    <td style="font-size: 28px;">-</td>
			  </tr>
			  <tr>
			    <td style="width:200px">⚪</td>
			    <td style="width:700px">berbakat tapi malas</td>
			    <td style="width:200px">⚪</td>
			  </tr>
			  <tr>
			    <td>⚪</td>
			    <td>rajin tapi tak berbakat</td>
			    <td>⚪</td>
			  </tr>
			  <tr>
			    <td>⚪</td>
			    <td>rajin dan mencari hal lain</td>
			    <td>⚪</td>
			  </tr>
			  <tr>
			    <td>⚪</td>
			    <td>malas dan menerima semua</td>
			    <td>⚪</td>
			  </tr>
			</table>

			<br>

			<p>Yang manakah dari empat pernyataan menggambarkan diri saudara dan tidak menggambarkan diri saudara ?</p>
			<p>Bila saudara lebih cenderung memilih <strong>rajin tapi tak berbakat</strong> untuk menggambarkan diri saudara. maka saudara hendaknya melakukan Klik ⚪ pada kolom + (PLUS) </p>
			<p>Bila saudara lebih cenderung memilih <strong>malas dan menerima semua</strong> untuk menggambarkan yang bukan diri saudara. maka saudara hendaknya melakukan Klik ⚪ pada kolom - (MINUS) </p>
			<table style="border: 1px solid #000; text-align: center;">
			  <tr>
			    <td style="font-size: 28px;">+</td>
			    <td></td>
			    <td style="font-size: 28px;">-</td>
			  </tr>
			  <tr>
			    <td style="width:200px">⚪</td>
			    <td style="width:700px">berbakat tapi malas</td>
			    <td style="width:200px">⚪</td>
			  </tr>
			  <tr>
			   <td>⚫</td>
			    <td>rajin tapi tak berbakat</td>
			    <td>⚪</td>
			  </tr>
			  <tr>
			    <td>⚪</td>
			    <td>rajin dan mencari hal lain</td>
			    <td>⚪</td>
			  </tr>
			  <tr>
			    <td>⚪</td>
			    <td>malas dan menerima semua</td>
			   <td>⚫</td>
			  </tr>
			</table>
			
			<p>Apabila saudara sudah memahami,<b> tekan</b> tombol <img src="images/contoh/ok.jpg"> <b>Waktu akan berjalan setelah saudara menekan <img src="images/contoh/ok.jpg"></b>. Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat</p>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	elseif($kondisi == 40)
	{
		$statement= '
		<div class="area-instruksi">
		 	
		<div class="judul">Personality Test B <br><span style="color: red">(Waktu sangat terbatas dan soal harus terisi "seluruhnya. Kerjakan dengan cepat, teliti dan tepat)</span> </div>

			<div class="keterangan"> 
		
			<p>Dalam tes ini terdapat 105 pernyataan yang perlu dijawab dengan memilih satu diantara 3 pilihan jawaban. Semua jawaban adalah benar, pilih lah satu jawaban yang paling sesuai dan tepat menggambarkan diri pribadi anda.  Silahkan mengerjakan dengan cepat karena waktu yang diberikan terbatas.   </p>
			<p>Apabila semua jawaban sama-sama sesuai dengan diri saudara, pilihlah yang paling mendekati dengan diri saudara. Apabila sama-sama tidak sesuai, pilihlah yang paling mendekati paling sesuai</p>
			<p>Tidak ada jawaban yang salah. Respon pertama adalah yang paling diharapkan. Dalam menjawab pernyataan-pernyataan tersebut, usahakan untuk memilih kemungkinan jawaban Ya dan Tidak; dan bila saudara benar-benar merasa ragu, bisa memilih jawaban “Kadang-Kadang” atau “Diantaranya”</p>
			<p>Bacalah instruksi dengan teliti, apabila sudah jelas, tekan OK. Waktu akan berjalan ketika saudara menekan OK. Kerjakan dengan teliti dan jangan ada soal yang terlewati. </p>


			<p>Perhatikan contoh di bawah ini : </p>
			<ul style="border: 1px solid #000;">
				<p style="padding-left: 20px;"> Saya orang yang sangat tenang dan santai ?</p>
				<p style="padding-left: 20px;"> ⚪Ya</p>
				<p style="padding-left: 20px;"> ⚪Kadang-Kadang</p>
				<p style="padding-left: 20px;"> ⚪Tidak</p>
			</ul>
			<br>
			<p>Bila saudara memilih <strong>Ya </strong> sebagai jawaban saudara dari pada <strong>Kadang-kadang</strong> dan <strong>Tidak</strong>. maka saudara hendaknya melakukan klik pada tempat didalam kolom jawaban disamping jawaban </p>

			<p>Contoh :</p>
			<ul style="border: 1px solid #000;">
				<p style="padding-left: 20px;"> Saya orang yang sangat tenang dan santai ?</p>
				<p style="padding-left: 20px;"> ⚫Ya</p>
				<p style="padding-left: 20px;"> ⚪Kadang-Kadang</p>
				<p style="padding-left: 20px;"> ⚪Tidak</p>
			</ul>
			<br>
			<p>Tetapi Bila saudara memilih <strong>Kadang-kadang </strong> sebagai jawaban saudara dari pada <strong>Ya</strong> dan <strong>Tidak</strong>. maka saudara hendaknya melakukan klik pada tempat didalam kolom jawaban disamping jawaban </p>

			<p>Contoh : </p>

			<ul style="border: 1px solid #000;">
				<p style="padding-left: 20px;"> Saya orang yang sangat tenang dan santai ?</p>
				<p style="padding-left: 20px;"> ⚪Ya</p>
				<p style="padding-left: 20px;"> ⚫Kadang-Kadang</p>
				<p style="padding-left: 20px;"> ⚪Tidak</p>
			</ul>

			<br>
			<p>Begitu juga dengan keadaan dimana saudara memilih <strong>Tidak</strong>.</p>
			<p>Apabila saudara sudah memahami,<b> tekan</b> tombol <img src="images/contoh/ok.jpg"> <b>Waktu akan berjalan setelah saudara menekan <img src="images/contoh/ok.jpg"></b>. Waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat</p>
			
			<br>
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>
			

		</div>
		';
	}
	elseif( ($kondisi >= 19 && $kondisi < 40 )  )
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul"></div>
			
			<div class="keterangan"> 
			<b><p>Kerjakan Ujian dengan baik, waktu saudara terbatas, Kerjakan dengan cepat, teliti dan tepat.</p></b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	
	elseif($kondisi == 66 )
	{
		$statement= '
		<div class="area-instruksi">
		 
			<div class="judul">M.M.P.I  <span style="color: red">(Wajib terisi seluruhnya)</span></div>
			
			<div class="keterangan"> 
			<b>
			Pada halaman selanjutnya, saudara akan menghadapi sejumlah pernyataan. Tugas saudara memilih 1 dari 2 pernyataan yang sesuai/tidak sesuai atau mendekati/tidak mendekati dengan diri saudara. Tidak ada jawaban Salah dan Benar. Bacalah instruksi dengan benar. Apabila saudara sudah paham, tekan OK. Waktu akan berjalan ketika saudara menekan OK. Kerjakan seluruh soal dengan teliti, benar dan cepat. Seluruh soal pernyataan wajib diisi. </p>
			</b>
			
			<input type="button" value="OK" onclick="setBacaTipeUjian('.$kondisi.')" id="fvpp-close">
			
			</div>

		</div>
		';
	}
	else
	{
		$statement= "Belum ada";
	}
	return $statement;
}
?>