<?php
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
/////// SISFOKOL_SMP_v5.0_(PernahJaya)                          ///////
/////// (Sistem Informasi Sekolah untuk SMP)                    ///////
///////////////////////////////////////////////////////////////////////
/////// Dibuat oleh :                                           ///////
/////// Agus Muhajir, S.Kom                                     ///////
/////// URL 	:                                               ///////
///////     * http://omahbiasawae.com/                          ///////
///////     * http://sisfokol.wordpress.com/                    ///////
///////     * http://hajirodeon.wordpress.com/                  ///////
///////     * http://yahoogroup.com/groups/sisfokol/            ///////
///////     * http://yahoogroup.com/groups/linuxbiasawae/       ///////
/////// E-Mail	:                                               ///////
///////     * hajirodeon@yahoo.com                              ///////
///////     * hajirodeon@gmail.com                              ///////
/////// HP/SMS/WA : 081-829-88-54                               ///////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////



session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/class/paging.php");
require("../../inc/cek/admkesw.php");
$tpl = LoadTpl("../../template/index.html");


nocache;

//nilai
$filenya = "mutasi.php";
$judul = "Data Mutasi";
$judulku = "[$kesw_session : $nip12_session. $nm12_session] ==> $judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$a = nosql($_REQUEST['a']);
$crkd = nosql($_REQUEST['crkd']);
$crtipe = balikin($_REQUEST['crtipe']);
$kunci = cegah($_REQUEST['kunci']);
$swkd = nosql($_REQUEST['swkd']);
$tapelkd = nosql($_REQUEST['tapelkd']);
$kelkd = nosql($_REQUEST['kelkd']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}

$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&page=$page";







//focus...
if (empty($tapelkd))
	{
	$diload = "document.formx.tapel.focus();";
	}

else if (empty($kelkd))
	{
	$diload = "document.formx.kelas.focus();";
	}



//nek enter, ke simpan
$x_enter = 'onKeyDown="var keyCode = event.keyCode;
if (keyCode == 13)
	{
	document.formx.btnSMP.focus();
	}"';




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//reset
if ($_POST['btnRST'])
	{
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);


	//re-direct
	$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd";
	xloc($ke);
	exit();
	}





//cari
if ($_POST['btnCARI'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);
	$crkd = nosql($_POST['crkd']);
	$crtipe = balikin2($_POST['crtipe']);
	$kunci = cegah($_POST['kunci']);


	//cek
	if ((empty($crkd)) OR (empty($kunci)))
		{
		//re-direct
		$pesan = "Input Pencarian Tidak Lengkap. Harap diperhatikan...!!";
		$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//re-direct
		$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&crkd=$crkd&crtipe=$crtipe&kunci=$kunci";
		xloc($ke);
		exit();
		}
	}


//jika batal
if ($_POST['btnBTL'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&page=$page";
	xloc($ke);
	exit();
	}



//jika daftar siswa
if ($_POST['btnDS'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&page=$page";
	xloc($ke);
	exit();
	}





//jika hapus
if ($_POST['btnHPS'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);


	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT * FROM m_siswa ".
			"ORDER BY round(nis) ASC";
	$sqlresult = $sqlcount;

	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);



	//ambil semua
	do
		{
		//ambil nilai
		$i = $i + 1;
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del data
		mysql_query("DELETE FROM m_siswa ".
				"WHERE kd = '$kd'");

		//nek $kd gak null
		if (!empty($kd))
			{
			//hapus file
			$cc_filex = $data['filex'];
			$path1 = "../../filebox/siswa/$kd/$cc_filex";
			unlink ($path1);
			}


		//del kelas
		mysql_query("DELETE FROM siswa_kelas ".
				"WHERE kd_siswa = '$kd'");
		}
	while ($data = mysql_fetch_assoc($result));

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//auto-kembali
	xloc($ke);
	exit();
	}





//jika simpan
if ($_POST['btnSMP'])
	{
	//nilai
	$s = nosql($_POST['s']);
	$a = nosql($_POST['a']);
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);

	$swkd = nosql($_POST['swkd']);


	//A. KETERANGAN TENTANG DIRI SISWA /////////////////////////////////////////////////////////////////////////////////////////////////
	if ($a == "a")
		{
		$nis = nosql($_POST['nis']);
		$nisn = nosql($_POST['nisn']);
		$nama = cegah($_POST['nama']);
		$panggilan = cegah($_POST['panggilan']);
		$kelamin = nosql($_POST['kelamin']);

		$tmp_lahir = cegah($_POST['tmp_lahir']);
		$lahir_tgl = nosql($_POST['lahir_tgl']);
		$lahir_bln = nosql($_POST['lahir_bln']);
		$lahir_thn = nosql($_POST['lahir_thn']);
		$tgl_lahir = "$lahir_thn:$lahir_bln:$lahir_tgl";

		$agama = nosql($_POST['agama']);
		$warga_negara = cegah($_POST['warga_negara']);
		$anak_ke = cegah($_POST['anak_ke']);
		$jml_sdr_kandung = nosql($_POST['jml_sdr_kandung']);
		$jml_sdr_tiri = nosql($_POST['jml_sdr_tiri']);
		$jml_sdr_angkat = nosql($_POST['jml_sdr_angkat']);
		$yatim_piatu = cegah($_POST['yatim_piatu']);
		$bahasa = cegah($_POST['bahasa']);


		//nek edit
		if ($s == "edit")
			{
			//nilai
			$s_userx = $nis;
			$s_passx = md5($nis);

			//update
			mysql_query("UPDATE m_siswa SET usernamex = '$s_userx', ".
					"passwordx = '$s_passx', ".
					"nis = '$nis', ".
					"nisn = '$nisn', ".
					"nama = '$nama', ".
					"panggilan = '$panggilan', ".
					"tmp_lahir = '$tmp_lahir', ".
					"tgl_lahir = '$tgl_lahir', ".
					"kd_kelamin = '$kelamin', ".
					"kd_agama = '$agama', ".
					"warga_negara = '$warga_negara', ".
					"anak_ke = '$anak_ke', ".
					"jml_sdr_kandung = '$jml_sdr_kandung', ".
					"jml_sdr_tiri = '$jml_sdr_tiri', ".
					"jml_sdr_angkat = '$jml_sdr_angkat', ".
					"yatim_piatu = '$yatim_piatu', ".
					"bhs_harian = '$bahasa' ".
					"WHERE kd = '$swkd'");



			//update ke janissari
			mysql_query("UPDATE m_user SET usernamex = '$s_userx', ".
					"passwordx = '$s_passx', ".
					"nomor = '$nis', ".
					"nama = '$nama' ".
					"WHERE kd = '$swkd'");



			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=a#a";
			xloc($ke);
			exit();
			}



		//nek baru
		if ($s == "baru")
			{
			//nek null
			if ((empty($nis)) OR (empty($nama)))
				{
				//diskonek
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				$pesan = "Input Tidak Lengkap. Harap Diulangi...!";
				$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&s=baru&swkd=$swkd&a=$a#a";
				pekem($pesan,$ke);
				exit();
				}
			else
				{
				//cek
				$qcc = mysql_query("SELECT * FROM m_siswa ".
							"WHERE nis = '$nis'");
				$rcc = mysql_fetch_assoc($qcc);
				$tcc = mysql_num_rows($qcc);

				//nek ada
				if ($tcc != 0)
					{
					//re-direct
					$pesan = "NIS Tersebut Sudah Ada. Silahkan Ganti Yang Lain...!!";
					$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&s=baru&swkd=$x&a=$a#a";
					pekem($pesan,$ke);
					exit();
					}
				else
					{
					//insert ke biodata
					$s_userx = $nis;
					$s_passx = md5($nis);
					$xx = md5($today3);


					//insert data siswa
					mysql_query("INSERT INTO m_siswa(kd, usernamex, passwordx, nis, nisn, nama, ".
							"panggilan, tmp_lahir, tgl_lahir, kd_kelamin, kd_agama, ".
							"warga_negara, anak_ke, jml_sdr_kandung, jml_sdr_tiri, ".
							"jml_sdr_angkat, yatim_piatu, bhs_harian) VALUES ".
							"('$swkd', '$s_userx', '$s_passx', '$nis', '$nisn', '$nama', ".
							"'$panggilan', '$tmp_lahir', '$tgl_lahir', '$kelamin', '$agama', ".
							"'$warga_negara', '$anak_ke', '$jml_sdr_kandung', '$jml_sdr_tiri', ".
							"'$jml_sdr_angkat', '$yatim_piatu', '$bahasa')");



					//masukkan ke janissari
					mysql_query("INSERT INTO m_user(kd, usernamex, passwordx, nomor, nama, tipe, postdate) VALUES ".
							"('$swkd', '$s_userx', '$s_passx', '$nis', '$nama', 'SISWA', '$today')");



					//insert ke kelas
					mysql_query("INSERT INTO siswa_kelas(kd, kd_tapel, kd_kelas, kd_siswa) VALUES ".
									"('$xx', '$tapelkd', '$kelkd', '$swkd')");

					//diskonek
					xfree($qbw);
					xclose($koneksi);

					//re-direct
					$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&s=edit&swkd=$swkd&a=$a#a";
					xloc($ke);
					exit();
					}
				}
			}
		}




	//B. KETERANGAN TEMPAT TINGGAL /////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "b")
		{
		$alamat = cegah($_POST['alamat']);
		$telp = cegah($_POST['telp']);
		$tinggal_dgn = cegah($_POST['tinggal_dgn']);
		$jarak = cegah($_POST['jarak']);


		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_tmp_tinggal ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_tmp_tinggal SET alamat = '$alamat', ".
								"telp = '$telp', ".
								"tinggal_dgn = '$tinggal_dgn', ".
								"jarak = '$jarak' ".
								"WHERE kd_siswa = '$swkd'");

				//diskonek
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=b#b";
				xloc($ke);
				exit();
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_tmp_tinggal(kd, kd_siswa, alamat, telp, tinggal_dgn, jarak) VALUES ".
								"('$x', '$swkd', '$alamat', '$telp', '$tinggal_dgn', '$jarak')");

				//diskonek
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&s=edit&swkd=$swkd&a=b#b";
				xloc($ke);
				exit();
				}
			}
		}




	//C. KETERANGAN KESEHATAN //////////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "c")
		{
		$gol_darah = cegah($_POST['gol_darah']);
		$penyakit = cegah($_POST['penyakit']);
		$kelainan = cegah($_POST['kelainan']);
		$berat = nosql($_POST['berat']);
		$tinggi = nosql($_POST['tinggi']);



		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_kesehatan ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_kesehatan SET gol_darah = '$gol_darah', ".
								"penyakit = '$penyakit', ".
								"kelainan = '$kelainan', ".
								"berat = '$berat', ".
								"tinggi = '$tinggi' ".
								"WHERE kd_siswa = '$swkd'");

				//diskonek
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=c#c";
				xloc($ke);
				exit();
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_kesehatan(kd, kd_siswa, gol_darah, penyakit, kelainan, berat, tinggi) VALUES ".
								"('$x', '$swkd', '$gol_darah', '$penyakit', '$kelainan', '$berat', '$tinggi')");

				//diskonek
				xfree($qbw);
				xclose($koneksi);

				//re-direct
				$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&s=edit&swkd=$swkd&a=c#c";
				xloc($ke);
				exit();
				}
			}
		}




	//D. KETERANGAN PENDIDIKAN /////////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "d")
		{
		//lulusan
		$lulusan = cegah($_POST['lulusan']);
		$no_sttb = nosql($_POST['no_sttb']);
		$lama = nosql($_POST['lama']);

		$sttb_tgl = nosql($_POST['sttb_tgl']);
		$sttb_bln = nosql($_POST['sttb_bln']);
		$sttb_thn = nosql($_POST['sttb_thn']);
		$tgl_sttb = ("$sttb_thn:$sttb_bln:$sttb_tgl");


		//pindahan
		$dari = cegah($_POST['dari']);
		$alasan = cegah($_POST['alasan']);


		//diterima
		$kelas = cegah($_POST['kelas']);
		$terima_tgl = nosql($_POST['terima_tgl']);
		$terima_bln = nosql($_POST['terima_bln']);
		$terima_thn = nosql($_POST['terima_thn']);
		$tgl_diterima = ("$terima_thn:$terima_bln:$terima_tgl");



		//nek edit
		if ($s == "edit")
			{
			//cek lulusan //////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$qcc = mysql_query("SELECT * FROM m_siswa_pendidikan ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_pendidikan SET lulusan = '$lulusan', ".
								"tgl_sttb = '$tgl_sttb', ".
								"no_sttb = '$no_sttb', ".
								"lama = '$lama' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_pendidikan(kd, kd_siswa, lulusan, tgl_sttb, no_sttb, lama) VALUES ".
								"('$x', '$swkd', '$lulusan', '$tgl_sttb', '$no_sttb', '$lama')");
				}



			//cek pindahan /////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$qcc1 = mysql_query("SELECT * FROM m_siswa_pindahan ".
									"WHERE kd_siswa = '$swkd'");
			$rcc1 = mysql_fetch_assoc($qcc1);
			$tcc1 = mysql_num_rows($qcc1);

			//nek ada
			if ($tcc1 != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_pindahan SET dari = '$dari', ".
								"alasan = '$alasan' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_pindahan(kd, kd_siswa, dari, alasan) VALUES ".
								"('$x', '$swkd', '$dari', '$alasan')");
				}



			//cek diterima /////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$qcc2 = mysql_query("SELECT * FROM m_siswa_diterima ".
									"WHERE kd_siswa = '$swkd'");
			$rcc2 = mysql_fetch_assoc($qcc2);
			$tcc2 = mysql_num_rows($qcc2);

			//nek ada
			if ($tcc2 != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_diterima SET kelas = '$kelas', ".
								"tgl = '$tgl_diterima' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_diterima(kd, kd_siswa, kelas, tgl) VALUES ".
								"('$x', '$swkd', '$kelas', '$tgl_diterima')");
				}


			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=d#d";
			xloc($ke);
			exit();
			}
		}




	//E. KETERANGAN TENTANG AYAH KANDUNG ///////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "e")
		{
		//nilai
		$nama = cegah($_POST['nama']);
		$tmp_lahir = cegah($_POST['tmp_lahir']);

		$lahir_tgl = nosql($_POST['lahir_tgl']);
		$lahir_bln = nosql($_POST['lahir_bln']);
		$lahir_thn = nosql($_POST['lahir_thn']);
		$tgl_lahir = ("$lahir_thn:$lahir_bln:$lahir_tgl");

		$agama = cegah($_POST['agama']);
		$warga_negara = cegah($_POST['warga_negara']);
		$pendidikan = cegah($_POST['pendidikan']);
		$pekerjaan = cegah($_POST['pekerjaan']);
		$pendapatan = cegah($_POST['pendapatan']);
		$alamat = cegah($_POST['alamat']);
		$telepon = cegah($_POST['telepon']);
		$hidup = cegah($_POST['hidup']);


		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_ayah ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_ayah SET nama = '$nama', ".
								"tmp_lahir = '$tmp_lahir', ".
								"tgl_lahir = '$tgl_lahir', ".
								"kd_agama = '$agama', ".
								"warga_negara = '$warga_negara', ".
								"pendidikan = '$pendidikan', ".
								"kd_pekerjaan = '$pekerjaan', ".
								"penghasilan = '$pendapatan', ".
								"alamat = '$alamat', ".
								"telp = '$telepon', ".
								"hidup = '$hidup' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_ayah(kd, kd_siswa, nama, tmp_lahir, tgl_lahir, kd_agama, ".
								"warga_negara, pendidikan, kd_pekerjaan, penghasilan, alamat, telp, hidup) VALUES ".
								"('$x', '$swkd', '$nama', '$tmp_lahir', '$tgl_lahir', '$agama', ".
								"'$warga_negara', '$pendidikan', '$pekerjaan', '$pendapatan', '$alamat', '$telepon', '$hidup')");
				}


			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=e#e";
			xloc($ke);
			exit();
			}
		}




	//F. KETERANGAN TENTANG IBU KANDUNG ////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "f")
		{
		//nilai
		$nama = cegah($_POST['nama']);
		$tmp_lahir = cegah($_POST['tmp_lahir']);

		$lahir_tgl = nosql($_POST['lahir_tgl']);
		$lahir_bln = nosql($_POST['lahir_bln']);
		$lahir_thn = nosql($_POST['lahir_thn']);
		$tgl_lahir = ("$lahir_thn:$lahir_bln:$lahir_tgl");

		$agama = cegah($_POST['agama']);
		$warga_negara = cegah($_POST['warga_negara']);
		$pendidikan = cegah($_POST['pendidikan']);
		$pekerjaan = cegah($_POST['pekerjaan']);
		$pendapatan = cegah($_POST['pendapatan']);
		$alamat = cegah($_POST['alamat']);
		$telepon = cegah($_POST['telepon']);
		$hidup = cegah($_POST['hidup']);


		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_ibu ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_ibu SET nama = '$nama', ".
								"tmp_lahir = '$tmp_lahir', ".
								"tgl_lahir = '$tgl_lahir', ".
								"kd_agama = '$agama', ".
								"warga_negara = '$warga_negara', ".
								"pendidikan = '$pendidikan', ".
								"kd_pekerjaan = '$pekerjaan', ".
								"penghasilan = '$pendapatan', ".
								"alamat = '$alamat', ".
								"telp = '$telepon', ".
								"hidup = '$hidup' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_ibu(kd, kd_siswa, nama, tmp_lahir, tgl_lahir, kd_agama, ".
								"warga_negara, pendidikan, kd_pekerjaan, penghasilan, alamat, telp, hidup) VALUES ".
								"('$x', '$swkd', '$nama', '$tmp_lahir', '$tgl_lahir', '$agama', ".
								"'$warga_negara', '$pendidikan', '$pekerjaan', '$pendapatan', '$alamat', '$telepon', '$hidup')");
				}


			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=f#f";
			xloc($ke);
			exit();
			}
		}




	//G. KETERANGAN TENTANG WALI ///////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "g")
		{
		//nilai
		$nama = cegah($_POST['nama']);
		$tmp_lahir = cegah($_POST['tmp_lahir']);

		$lahir_tgl = nosql($_POST['lahir_tgl']);
		$lahir_bln = nosql($_POST['lahir_bln']);
		$lahir_thn = nosql($_POST['lahir_thn']);
		$tgl_lahir = ("$lahir_thn:$lahir_bln:$lahir_tgl");

		$agama = cegah($_POST['agama']);
		$warga_negara = cegah($_POST['warga_negara']);
		$pendidikan = cegah($_POST['pendidikan']);
		$pekerjaan = cegah($_POST['pekerjaan']);
		$pendapatan = cegah($_POST['pendapatan']);
		$alamat = cegah($_POST['alamat']);
		$telepon = cegah($_POST['telepon']);


		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_wali ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_wali SET nama = '$nama', ".
								"tmp_lahir = '$tmp_lahir', ".
								"tgl_lahir = '$tgl_lahir', ".
								"kd_agama = '$agama', ".
								"warga_negara = '$warga_negara', ".
								"pendidikan = '$pendidikan', ".
								"kd_pekerjaan = '$pekerjaan', ".
								"penghasilan = '$pendapatan', ".
								"alamat = '$alamat', ".
								"telp = '$telepon' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_wali(kd, kd_siswa, nama, tmp_lahir, tgl_lahir, kd_agama, ".
								"warga_negara, pendidikan, kd_pekerjaan, penghasilan, alamat, telp) VALUES ".
								"('$x', '$swkd', '$nama', '$tmp_lahir', '$tgl_lahir', '$agama', ".
								"'$warga_negara', '$pendidikan', '$pekerjaan', '$pendapatan', '$alamat', '$telepon')");
				}


			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=g#g";
			xloc($ke);
			exit();
			}
		}




	//H. KEGEMARAN SISWA ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "h")
		{
		//nilai
		$kesenian = cegah($_POST['kesenian']);
		$olah_raga = cegah($_POST['olah_raga']);
		$organisasi = cegah($_POST['organisasi']);
		$lain_lain = cegah($_POST['lain_lain']);


		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_hobi ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_hobi SET kesenian = '$kesenian', ".
								"olah_raga = '$olah_raga', ".
								"organisasi = '$organisasi', ".
								"lain_lain = '$lain_lain' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_hobi(kd, kd_siswa, kesenian, olah_raga, organisasi, lain_lain) VALUES ".
								"('$x', '$swkd', '$kesenian', '$olah_raga', '$organisasi', '$lain_lain')");
				}


			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=h#h";
			xloc($ke);
			exit();
			}
		}




	//I. KETERANGAN PERKEMBANGAN SISWA /////////////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "i")
		{
		//nilai
		$bea_siswa = cegah($_POST['bea_siswa']);

		$tinggal_tgl = nosql($_POST['tinggal_tgl']);
		$tinggal_bln = nosql($_POST['tinggal_bln']);
		$tinggal_thn = nosql($_POST['tinggal_thn']);
		$tgl_tinggal = ("$tinggal_thn:$tinggal_bln:$tinggal_tgl");

		$ijazah_terima_tgl = nosql($_POST['ijazah_terima_tgl']);
		$ijazah_terima_bln = nosql($_POST['ijazah_terima_bln']);
		$ijazah_terima_thn = nosql($_POST['ijazah_terima_thn']);
		$tgl_terima_ijazah = ("$ijazah_terima_thn:$ijazah_terima_bln:$ijazah_terima_tgl");

		$ijazah_tgl = nosql($_POST['ijazah_tgl']);
		$ijazah_bln = nosql($_POST['ijazah_bln']);
		$ijazah_thn = nosql($_POST['ijazah_thn']);
		$tgl_ijazah = ("$ijazah_thn:$ijazah_bln:$ijazah_tgl");

		$tulis_tgl = nosql($_POST['tulis_tgl']);
		$tulis_bln = nosql($_POST['tulis_bln']);
		$tulis_thn = nosql($_POST['tulis_thn']);
		$tgl_tulis = ("$tulis_thn:$tulis_bln:$tulis_tgl");



		$alasan = cegah($_POST['alasan']);
		$tamat = cegah($_POST['tamat']);
		$no_sttb = cegah($_POST['no_sttb']);

		$pindah_kelas = cegah($_POST['pindah_kelas']);
		$no_surat = cegah($_POST['no_surat']);
		$ket = cegah($_POST['ket']);



		//dinyatakan jadi alumni, jika telah tertulis tanggal ijazah dan tanggal terima ijazah.
		if (($tgl_terima_ijazah != "0000:00:00") AND ($tgl_ijazah != "0000:00:00"))
			{
			$st_alumni = "true";
			}
		else
			{
			$st_alumni = "false";
			}



		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_perkembangan ".
						"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_perkembangan SET bea_siswa = '$bea_siswa', ".
						"tgl = '$tgl_tinggal', ".
						"tgl_terima_ijazah = '$tgl_terima_ijazah', ".
						"tgl_ijazah = '$tgl_ijazah', ".
						"tgl_tulis = '$today', ".
						"alasan = '$alasan', ".
						"tamat = '$tamat', ".
						"no_sttb = '$no_sttb', ".
						"pindah_kelas = '$pindah_kelas', ".
						"no_surat = '$no_surat', ".
						"ket = '$ket', ".
						"alumni = '$st_alumni' ".
						"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_perkembangan(kd, kd_siswa, bea_siswa, ".
						"tgl, tgl_terima_ijazah, tgl_ijazah, tgl_tulis, alasan, ".
						"tamat, no_sttb, pindah_kelas, no_surat, ket, alumni) VALUES ".
						"('$x', '$swkd', '$bea_siswa', ".
						"'$tgl_tinggal', '$tgl_terima_ijazah', '$tgl_ijazah', '$today', '$alasan', ".
						"'$tamat', '$no_sttb', '$pindah', '$no_surat', '$ket', '$st_alumni')");
				}


			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=i#i";
			xloc($ke);
			exit();
			}
		}




	//J. KETERANGAN SETELAH SELESAI PENDIDIKAN /////////////////////////////////////////////////////////////////////////////////////////
	else if ($a == "j")
		{
		//nilai
		$melanjutkan_di = cegah($_POST['melanjutkan_di']);
		$bekerja = cegah($_POST['bekerja']);

		$kerja_tgl = nosql($_POST['kerja_tgl']);
		$kerja_bln = nosql($_POST['kerja_bln']);
		$kerja_thn = nosql($_POST['kerja_thn']);
		$tgl_kerja = ("$kerja_thn:$kerja_bln:$kerja_tgl");

		$instansi = cegah($_POST['instansi']);
		$penghasilan = cegah($_POST['penghasilan']);




		//nek edit
		if ($s == "edit")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_siswa_selesai ".
									"WHERE kd_siswa = '$swkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE m_siswa_selesai SET melanjutkan_di = '$melanjutkan_di', ".
								"bekerja = '$bekerja', ".
								"tgl = '$tgl_kerja', ".
								"instansi = '$instansi', ".
								"penghasilan = '$penghasilan' ".
								"WHERE kd_siswa = '$swkd'");
				}

			//nek blm ada
			else
				{
				//insert
				mysql_query("INSERT INTO m_siswa_selesai(kd, kd_siswa, melanjutkan_di, bekerja, tgl, instansi, penghasilan) VALUES ".
								"('$x', '$swkd', '$melanjutkan_di', '$bekerja', '$tgl_kerja', '$instansi', '$penghasilan')");
				}


			//diskonek
			xfree($qbw);
			xclose($koneksi);

			//re-direct
			$ke = "$filenya?s=edit&tapelkd=$tapelkd&kelkd=$kelkd&swkd=$swkd&a=j#j";
			xloc($ke);
			exit();
			}
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi *START
ob_start();

//menu
require("../../inc/menu/admkesw.php");

//isi_menu
$isi_menu = ob_get_contents();
ob_end_clean();









//isi *START
ob_start();


//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/js/checkall.js");
require("../../inc/js/number.js");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" enctype="multipart/form-data" action="'.$filenya.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Tahun Pelajaran : ';

echo "<select name=\"tapel\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qtpx = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd = '$tapelkd'");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_thn1 = nosql($rowtpx['tahun1']);
$tpx_thn2 = nosql($rowtpx['tahun2']);

echo '<option value="'.$tpx_kd.'">'.$tpx_thn1.'/'.$tpx_thn2.'</option>';

$qtp = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd <> '$tapelkd' ".
						"ORDER BY tahun1 ASC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpth1 = nosql($rowtp['tahun1']);
	$tpth2 = nosql($rowtp['tahun2']);

	echo '<option value="'.$filenya.'?tapelkd='.$tpkd.'">'.$tpth1.'/'.$tpth2.'</option>';
	}
while ($rowtp = mysql_fetch_assoc($qtp));

echo '</select></td>
</tr>
</table>

<table bgcolor="'.$warna01.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Kelas : ';

echo "<select name=\"kelas\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qbtx = mysql_query("SELECT * FROM m_kelas ".
			"WHERE kd = '$kelkd'");
$rowbtx = mysql_fetch_assoc($qbtx);
$btxkd = nosql($rowbtx['kd']);
$btxkelas = balikin($rowbtx['kelas']);

echo '<option value="'.$btxkd.'">'.$btxkelas.'</option>';

$qbt = mysql_query("SELECT * FROM m_kelas ".
					"ORDER BY no ASC, kelas ASC");
$rowbt = mysql_fetch_assoc($qbt);

do
	{
	$btkd = nosql($rowbt['kd']);
	$btkelas = balikin($rowbt['kelas']);

	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$btkd.'">'.$btkelas.'</option>';
	}
while ($rowbt = mysql_fetch_assoc($qbt));

echo '</select>

<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
<input name="kelkd" type="hidden" value="'.$kelkd.'">
<input name="swkd" type="hidden" value="'.$swkd.'">
</td>
</tr>
</table>';


//nek blm dipilih
if (empty($tapelkd))
	{
	echo '<p>
	<font color="#FF0000"><strong>TAHUN PELAJARAN Belum Dipilih...!</strong></font>
	</p>';
	}
else if (empty($kelkd))
	{
	echo '<p>
	<font color="#FF0000"><strong>KELAS Belum Dipilih...!</strong></font>
	</p>';
	}
else
	{
	//entry baru / edit
	if (($s == "baru") OR ($s == "edit"))
		{
		//nilai
		$swkd = nosql($_REQUEST['swkd']);
		$tapelkd = nosql($_REQUEST['tapelkd']);
		$kelkd = nosql($_REQUEST['kelkd']);


		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="a"></a>
		<strong>A. KETERANGAN TENTANG DIRI SISWA</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=a#a" title="A. KETERANGAN TENTANG DIRI SISWA">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "a")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa.*, m_siswa.kd AS mskd, ".
						"DATE_FORMAT(m_siswa.tgl_lahir, '%d') AS lahir_tgl, ".
						"DATE_FORMAT(m_siswa.tgl_lahir, '%m') AS lahir_bln, ".
						"DATE_FORMAT(m_siswa.tgl_lahir, '%Y') AS lahir_thn, ".
						"siswa_kelas.* ".
						"FROM m_siswa, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_nis = nosql($rnil['nis']);
			$y_nisn = nosql($rnil['nisn']);
			$y_nama = balikin($rnil['nama']);
			$y_panggilan = balikin($rnil['panggilan']);
			$y_jkelkd = nosql($rnil['kd_kelamin']);

			$y_tmp_lahir = balikin($rnil['tmp_lahir']);
			$y_lahir_tgl = nosql($rnil['lahir_tgl']);
			$y_lahir_bln = nosql($rnil['lahir_bln']);
			$y_lahir_thn = nosql($rnil['lahir_thn']);

			$y_agmkd = nosql($rnil['kd_agama']);
			$y_warga_negara = balikin2($rnil['warga_negara']);
			$y_anak_ke = nosql($rnil['anak_ke']);
			$y_jml_sdr_kandung = nosql($rnil['jml_sdr_kandung']);
			$y_jml_sdr_tiri = nosql($rnil['jml_sdr_tiri']);
			$y_jml_sdr_angkat = nosql($rnil['jml_sdr_angkat']);
			$y_yatim_piatu = balikin2($rnil['yatim_piatu']);
			$y_bahasa = balikin2($rnil['bhs_harian']);
			$y_filex = $rnil['filex'];

			//view
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr valign="top">
			<td width="80%">

			<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			NIS
			</td>
			<td width="10">: </td>
			<td>
			<input name="nis" type="text" value="'.$y_nis.'" size="30" onKeyPress="return numbersonly(this, event)">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			NISN
			</td>
			<td width="10">: </td>
			<td>
			<input name="nisn" type="text" value="'.$y_nisn.'" size="30" onKeyPress="return numbersonly(this, event)">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			1. Nama Siswa
			</td>
			<td width="10"></td>
			<td></td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			a. Nama Lengkap
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="nama" type="text" value="'.$y_nama.'" size="20">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			b. Panggilan
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="panggilan" type="text" value="'.$y_panggilan.'" size="20">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			2. Jenis Kelamin
			</td>
			<td width="10">: </td>
			<td>
			<select name="kelamin">';

			//terpilih
			$qjkelx = mysql_query("SELECT * FROM m_kelamin ".
									"WHERE kd = '$y_jkelkd'");
			$rjkelx = mysql_fetch_assoc($qjkelx);
			$jkelx_kd = nosql($rjkelx['kd']);
			$jkelx_kelamin = balikin($rjkelx['kelamin']);

			echo '<option value="'.$jkelx_kd.'">'.$jkelx_kelamin.'</option>';

			$qjkel = mysql_query("SELECT * FROM m_kelamin ".
									"WHERE kd <> '$y_jkelkd' ".
									"ORDER BY kelamin ASC");
			$rjkel = mysql_fetch_assoc($qjkel);

			do
				{
				$jkel_kd = nosql($rjkel['kd']);
				$jkel_kelamin = balikin($rjkel['kelamin']);

				echo '<option value="'.$jkel_kd.'">'.$jkel_kelamin.'</option>';
				}
			while ($rjkel = mysql_fetch_assoc($qjkel));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			3. TTL
			</td>
			<td width="10">: </td>
			<td>
			<input name="tmp_lahir" type="text" value="'.$y_tmp_lahir.'" size="30">,
			<select name="lahir_tgl">
			<option value="'.$y_lahir_tgl.'" selected>'.$y_lahir_tgl.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="lahir_bln">
			<option value="'.$y_lahir_bln.'" selected>'.$arrbln1[$y_lahir_bln].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="lahir_thn">
			<option value="'.$y_lahir_thn.'" selected>'.$y_lahir_thn.'</option>';
			for ($k=$lahir01;$k<=$lahir02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			4. Agama
			</td>
			<td width="10">: </td>
			<td>

			<select name="agama">';

			//terpilih
			$qagmx = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd = '$y_agmkd'");
			$ragmx = mysql_fetch_assoc($qagmx);
			$agmx_kd = nosql($ragmx['kd']);
			$agmx_agama = balikin($ragmx['agama']);

			echo '<option value="'.$agmx_kd.'">'.$agmx_agama.'</option>';

			$qagm = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd <> '$y_agmkd' ".
									"ORDER BY agama ASC");
			$ragm = mysql_fetch_assoc($qagm);

			do
				{
				$agm_kd = nosql($ragm['kd']);
				$agm_agama = balikin($ragm['agama']);

				echo '<option value="'.$agm_kd.'">'.$agm_agama.'</option>';
				}
			while ($ragm = mysql_fetch_assoc($qagm));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			5. Kewarganegaraan
			</td>
			<td width="10">: </td>
			<td>
			<input name="warga_negara" type="text" value="'.$y_warga_negara.'" size="20">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			6. Anak Keberapa
			</td>
			<td width="10">: </td>
			<td>
			<input name="anak_ke" type="text" value="'.$y_anak_ke.'" size="2" maxlength="2" onKeyPress="return numbersonly(this, event)">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			7. Jumlah Saudara Kandung
			</td>
			<td width="10">: </td>
			<td>
			<input name="jml_sdr_kandung" type="text" value="'.$y_jml_sdr_kandung.'" size="2" maxlength="2" onKeyPress="return numbersonly(this, event)">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			8. Jumlah Saudara Tiri
			</td>
			<td width="10">: </td>
			<td>
			<input name="jml_sdr_tiri" type="text" value="'.$y_jml_sdr_tiri.'" size="2" maxlength="2" onKeyPress="return numbersonly(this, event)">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			9. Jumlah Saudara Angkat
			</td>
			<td width="10">: </td>
			<td>
			<input name="jml_sdr_angkat" type="text" value="'.$y_jml_sdr_angkat.'" size="2" maxlength="2" onKeyPress="return numbersonly(this, event)">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			10. Anak Yatim / Anak Piatu / Yatim Piatu
			</td>
			<td width="10">: </td>
			<td>
			<select name="yatim_piatu">
			<option value="'.$y_yatim_piatu.'" selected>'.$y_yatim_piatu.'</option>
			<option value="Anak Yatim">Anak Yatim</option>
			<option value="Anak Piatu">Anak Piatu</option>
			<option value="Yatim Piatu">Yatim Piatu</option>
			<option value="-">-</option>
			</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			11. Bahasa Sehari - hari di Rumah
			</td>
			<td width="10">: </td>
			<td>
			<input name="bahasa" type="text" value="'.$y_bahasa.'" size="20">
			</td>
			</tr>
			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="a">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">


			</td>
			<td>';

			//nek null foto
			if (empty($y_filex))
				{
				$nil_foto = "$sumber/img/foto_blank.jpg";
				}
			else
				{
				$nil_foto = "$sumber/filebox/siswa/$swkd/$y_filex";
				}

			echo '<img src="'.$nil_foto.'" alt="'.$y_nama.'" width="150" height="200" border="5">
			<br>
			<br>
			<input name="filex_foto" type="file" size="15">
			<br>
			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="a">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnGNT" type="submit" value="GANTI">
			</td>
			</tr>
			</table>
			<br>
			<br>';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="b"></a>
		<strong>B. KETERANGAN TEMPAT TINGGAL</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=b#b" title="B. KETERANGAN TEMPAT TINGGAL">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "b")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_tmp_tinggal.*, siswa_kelas.* ".
						"FROM m_siswa_tmp_tinggal, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_tmp_tinggal.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_alamat = balikin2($rnil['alamat']);
			$y_telp = balikin2($rnil['telp']);
			$y_tinggal_dgn = balikin2($rnil['tinggal_dgn']);
			$y_jarak = balikin2($rnil['jarak']);



			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			12. Alamat
			</td>
			<td width="10">: </td>
			<td>
			<input name="alamat" type="text" value="'.$y_alamat.'" size="50">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			13. Telp.
			</td>
			<td width="10">: </td>
			<td>
			<input name="telp" type="text" value="'.$y_telp.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			14. Tinggal dengan Orang Tua / Saudara / di Asrama / Kos.
			</td>
			<td width="10">: </td>
			<td>
			<select name="tinggal_dgn">
			<option value="'.$y_tinggal_dgn.'" selected>'.$y_tinggal_dgn.'</option>
			<option value="Orang Tua">Orang Tua</option>
			<option value="Saudara">Saudara</option>
			<option value="di Asrama">di Asrama</option>
			<option value="Kos">Kos</option>
			</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			15. Jarak tempat tinggal ke sekolah
			</td>
			<td width="10">: </td>
			<td>
			<input name="jarak" type="text" value="'.$y_jarak.'" size="10">
			</td>
			</tr>
			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="b">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="c"></a>
		<strong>C. KETERANGAN KESEHATAN</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=c#c" title="C. KETERANGAN KESEHATAN">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "c")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_kesehatan.*, siswa_kelas.* ".
						"FROM m_siswa_kesehatan, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_kesehatan.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_gol_darah = balikin2($rnil['gol_darah']);
			$y_penyakit = balikin2($rnil['penyakit']);
			$y_kelainan = balikin2($rnil['kelainan']);
			$y_berat = balikin2($rnil['berat']);
			$y_tinggi = balikin2($rnil['tinggi']);


			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			16. Golongan Darah
			</td>
			<td width="10">: </td>
			<td>
			<input name="gol_darah" type="text" value="'.$y_gol_darah.'" size="50">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			17. Penyakit yang pernah diderita TBC/Cacar/Malaria/dan lain - lain
			</td>
			<td width="10">: </td>
			<td>
			<input name="penyakit" type="text" value="'.$y_penyakit.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			18. Kelainan Jasmani
			</td>
			<td width="10">: </td>
			<td>
			<input name="kelainan" type="text" value="'.$y_kelainan.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			19. Tinggi / Berat Badan
			</td>
			<td width="10">: </td>
			<td>
			<input name="tinggi" type="text" value="'.$y_tinggi.'" size="3" maxlength="3" onKeyPress="return numbersonly(this, event)"> Cm /
			<input name="berat" type="text" value="'.$y_berat.'" size="3" maxlength="3" onKeyPress="return numbersonly(this, event)"> Kg.
			</td>
			</tr>
			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="c">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="d"></a>
		<strong>D. KETERANGAN PENDIDIKAN</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=d#d" title="D. KETERANGAN PENDIDIKAN">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "d")
			{
			//query pendidikan /////////////////////////////////////////////////////////////////////////////////////////////////////////
			$qnil = mysql_query("SELECT m_siswa_pendidikan.*, ".
						"DATE_FORMAT(m_siswa_pendidikan.tgl_sttb, '%d') AS tgl, ".
						"DATE_FORMAT(m_siswa_pendidikan.tgl_sttb, '%m') AS bln, ".
						"DATE_FORMAT(m_siswa_pendidikan.tgl_sttb, '%Y') AS thn, ".
						"siswa_kelas.* ".
						"FROM m_siswa_pendidikan, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_pendidikan.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_lulusan = balikin2($rnil['lulusan']);
			$y_no_sttb = balikin2($rnil['no_sttb']);
			$y_tgl_sttb = nosql($rnil['tgl']);
			$y_bln_sttb = nosql($rnil['bln']);
			$y_thn_sttb = nosql($rnil['thn']);
			$y_lama = balikin2($rnil['lama']);


			//query pindahan ///////////////////////////////////////////////////////////////////////////////////////////////////////////
			$qnil2 = mysql_query("SELECT m_siswa_pindahan.*, siswa_kelas.* ".
						"FROM m_siswa_pindahan, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_pindahan.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil2 = mysql_fetch_assoc($qnil2);
			$y_dari = balikin2($rnil2['dari']);
			$y_alasan = balikin2($rnil2['alasan']);


			//query diterima ///////////////////////////////////////////////////////////////////////////////////////////////////////////
			$qnil3 = mysql_query("SELECT m_siswa_diterima.*, ".
						"DATE_FORMAT(m_siswa_diterima.tgl, '%d') AS tgl, ".
						"DATE_FORMAT(m_siswa_diterima.tgl, '%m') AS bln, ".
						"DATE_FORMAT(m_siswa_diterima.tgl, '%Y') AS thn, ".
						"siswa_kelas.* ".
						"FROM m_siswa_diterima, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_diterima.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil3 = mysql_fetch_assoc($qnil3);
			$y_kelas = balikin2($rnil3['kelas']);
			$y_tgl_terima = nosql($rnil3['tgl']);
			$y_bln_terima = nosql($rnil3['bln']);
			$y_thn_terima = nosql($rnil3['thn']);




			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			20. Pendidikan Sebelumnya
			</td>
			<td width="10"></td>
			<td></td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			a. Lulusan dari
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="lulusan" type="text" value="'.$y_lulusan.'" size="50">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			b. Tanggal dan No. STTB
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="sttb_tgl">
			<option value="'.$y_tgl_sttb.'" selected>'.$y_tgl_sttb.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="sttb_bln">
			<option value="'.$y_bln_sttb.'" selected>'.$arrbln1[$y_bln_sttb].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="sttb_thn">
			<option value="'.$y_thn_sttb.'" selected>'.$y_thn_sttb.'</option>';
			for ($k=$sttb01;$k<=$sttb02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			 /
			<input name="no_sttb" type="text" value="'.$y_no_sttb.'" size="10">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			c. Lama Belajar
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="lama" type="text" value="'.$y_lama.'" size="1" maxlength="1" onKeyPress="return numbersonly(this, event)"> Tahun.
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			21. Pindahan
			</td>
			<td width="10"></td>
			<td></td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			a. Dari Sekolah
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="dari" type="text" value="'.$y_dari.'" size="50">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			b. Alasan
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="alasan" type="text" value="'.$y_alasan.'" size="50">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			22. Diterima di Sekolah ini
			</td>
			<td width="10"></td>
			<td></td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			a. Di Kelas
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="kelas" type="text" value="'.$y_kelas.'" size="10">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			b. Tanggal
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="terima_tgl">
			<option value="'.$y_tgl_terima.'" selected>'.$y_tgl_terima.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="terima_bln">
			<option value="'.$y_bln_terima.'" selected>'.$arrbln1[$y_bln_terima].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="terima_thn">
			<option value="'.$y_thn_terima.'" selected>'.$y_thn_terima.'</option>';
			for ($k=$sttb01;$k<=$sttb02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>
			</table>


			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="d">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="e"></a>
		<strong>E. KETERANGAN TENTANG AYAH KANDUNG</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=e#e" title="E. KETERANGAN TENTANG AYAH KANDUNG">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "e")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_ayah.*, ".
						"DATE_FORMAT(m_siswa_ayah.tgl_lahir, '%d') AS tgl, ".
						"DATE_FORMAT(m_siswa_ayah.tgl_lahir, '%m') AS bln, ".
						"DATE_FORMAT(m_siswa_ayah.tgl_lahir, '%Y') AS thn, ".
						"siswa_kelas.* ".
						"FROM m_siswa_ayah, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_ayah.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_nama = balikin2($rnil['nama']);
			$y_tmp_lahir = balikin2($rnil['tmp_lahir']);
			$y_tgl_lahir = nosql($rnil['tgl']);
			$y_bln_lahir = nosql($rnil['bln']);
			$y_thn_lahir = nosql($rnil['thn']);
			$y_agmkd = nosql($rnil['kd_agama']);
			$y_warga_negara = balikin2($rnil['warga_negara']);
			$y_pendidikan = balikin2($rnil['pendidikan']);
			$y_pekkd = nosql($rnil['kd_pekerjaan']);
			$y_penghasilan = balikin2($rnil['penghasilan']);
			$y_alamat = balikin2($rnil['alamat']);
			$y_telp = balikin2($rnil['telp']);
			$y_hidup = balikin2($rnil['hidup']);


			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			23. Nama
			</td>
			<td width="10">: </td>
			<td>
			<input name="nama" type="text" value="'.$y_nama.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			24. Tempat dan Tanggal Lahir
			</td>
			<td width="10">: </td>
			<td>
			<input name="tmp_lahir" type="text" value="'.$y_tmp_lahir.'" size="30">,
			<select name="lahir_tgl">
			<option value="'.$y_tgl_lahir.'" selected>'.$y_tgl_lahir.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="lahir_bln">
			<option value="'.$y_bln_lahir.'" selected>'.$arrbln1[$y_bln_lahir].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="lahir_thn">
			<option value="'.$y_thn_lahir.'" selected>'.$y_thn_lahir.'</option>';
			for ($k=$lahir01;$k<=$lahir02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			25. Agama
			</td>
			<td width="10">: </td>
			<td>
			<select name="agama">';

			//terpilih
			$qagmx = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd = '$y_agmkd'");
			$ragmx = mysql_fetch_assoc($qagmx);
			$agmx_kd = nosql($ragmx['kd']);
			$agmx_agama = balikin($ragmx['agama']);

			echo '<option value="'.$agmx_kd.'">'.$agmx_agama.'</option>';

			$qagm = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd <> '$y_agmkd' ".
									"ORDER BY agama ASC");
			$ragm = mysql_fetch_assoc($qagm);

			do
				{
				$agm_kd = nosql($ragm['kd']);
				$agm_agama = balikin($ragm['agama']);

				echo '<option value="'.$agm_kd.'">'.$agm_agama.'</option>';
				}
			while ($ragm = mysql_fetch_assoc($qagm));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			26. Kewarganegaraan
			</td>
			<td width="10">: </td>
			<td>
			<input name="warga_negara" type="text" value="'.$y_warga_negara.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			27. Pendidikan
			</td>
			<td width="10">: </td>
			<td>
			<input name="pendidikan" type="text" value="'.$y_pendidikan.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			28. Pekerjaan
			</td>
			<td width="10">: </td>
			<td>
			<select name="pekerjaan">';

			//terpilih
			$qpekx = mysql_query("SELECT * FROM m_pekerjaan ".
									"WHERE kd = '$y_pekkd'");
			$rpekx = mysql_fetch_assoc($qpekx);
			$pekx_kd = nosql($rpekx['kd']);
			$pekx_pekerjaan = balikin($rpekx['pekerjaan']);

			echo '<option value="'.$pekx_kd.'">'.$pekx_pekerjaan.'</option>';

			$qpek = mysql_query("SELECT * FROM m_pekerjaan ".
									"WHERE kd <> '$y_pekkd' ".
									"ORDER BY pekerjaan ASC");
			$rpek = mysql_fetch_assoc($qpek);

			do
				{
				$pek_kd = nosql($rpek['kd']);
				$pek_pekerjaan = balikin($rpek['pekerjaan']);

				echo '<option value="'.$pek_kd.'">'.$pek_pekerjaan.'</option>';
				}
			while ($rpek = mysql_fetch_assoc($qpek));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			29. Penghasilan per Bulan
			</td>
			<td width="10">: </td>
			<td>
			Rp. <input name="pendapatan" type="text" value="'.$y_penghasilan.'" size="10" onKeyPress="return numbersonly(this, event)">,00
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			30. Alamat Rumah / Telepon
			</td>
			<td width="10">: </td>
			<td>
			<input name="alamat" type="text" value="'.$y_alamat.'" size="30"> /
			<input name="telepon" type="text" value="'.$y_telp.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			31. Masih Hidup/Meninggal Dunia, Tahun
			</td>
			<td width="10">: </td>
			<td>
			<input name="hidup" type="text" value="'.$y_hidup.'" size="30">
			</td>
			</tr>
			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="e">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="f"></a>
		<strong>F. KETERANGAN TENTANG IBU KANDUNG</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=f#f" title="F. KETERANGAN TENTANG IBU KANDUNG">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "f")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_ibu.*, ".
						"DATE_FORMAT(m_siswa_ibu.tgl_lahir, '%d') AS tgl, ".
						"DATE_FORMAT(m_siswa_ibu.tgl_lahir, '%m') AS bln, ".
						"DATE_FORMAT(m_siswa_ibu.tgl_lahir, '%Y') AS thn, ".
						"siswa_kelas.* ".
						"FROM m_siswa_ibu, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_ibu.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_nama = balikin2($rnil['nama']);
			$y_tmp_lahir = balikin2($rnil['tmp_lahir']);
			$y_tgl_lahir = nosql($rnil['tgl']);
			$y_bln_lahir = nosql($rnil['bln']);
			$y_thn_lahir = nosql($rnil['thn']);
			$y_agmkd = nosql($rnil['kd_agama']);
			$y_warga_negara = balikin2($rnil['warga_negara']);
			$y_pendidikan = balikin2($rnil['pendidikan']);
			$y_pekkd = nosql($rnil['kd_pekerjaan']);
			$y_penghasilan = balikin2($rnil['penghasilan']);
			$y_alamat = balikin2($rnil['alamat']);
			$y_telp = balikin2($rnil['telp']);
			$y_hidup = balikin2($rnil['hidup']);


			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			32. Nama
			</td>
			<td width="10">: </td>
			<td>
			<input name="nama" type="text" value="'.$y_nama.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			33. Tempat dan Tanggal Lahir
			</td>
			<td width="10">: </td>
			<td>
			<input name="tmp_lahir" type="text" value="'.$y_tmp_lahir.'" size="30">,
			<select name="lahir_tgl">
			<option value="'.$y_tgl_lahir.'" selected>'.$y_tgl_lahir.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="lahir_bln">
			<option value="'.$y_bln_lahir.'" selected>'.$arrbln1[$y_bln_lahir].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="lahir_thn">
			<option value="'.$y_thn_lahir.'" selected>'.$y_thn_lahir.'</option>';
			for ($k=$lahir01;$k<=$lahir02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			34. Agama
			</td>
			<td width="10">: </td>
			<td>
			<select name="agama">';

			//terpilih
			$qagmx = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd = '$y_agmkd'");
			$ragmx = mysql_fetch_assoc($qagmx);
			$agmx_kd = nosql($ragmx['kd']);
			$agmx_agama = balikin($ragmx['agama']);

			echo '<option value="'.$agmx_kd.'">'.$agmx_agama.'</option>';

			$qagm = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd <> '$y_agmkd' ".
									"ORDER BY agama ASC");
			$ragm = mysql_fetch_assoc($qagm);

			do
				{
				$agm_kd = nosql($ragm['kd']);
				$agm_agama = balikin($ragm['agama']);

				echo '<option value="'.$agm_kd.'">'.$agm_agama.'</option>';
				}
			while ($ragm = mysql_fetch_assoc($qagm));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			35. Kewarganegaraan
			</td>
			<td width="10">: </td>
			<td>
			<input name="warga_negara" type="text" value="'.$y_warga_negara.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			36. Pendidikan
			</td>
			<td width="10">: </td>
			<td>
			<input name="pendidikan" type="text" value="'.$y_pendidikan.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			37. Pekerjaan
			</td>
			<td width="10">: </td>
			<td>
			<select name="pekerjaan">';

			//terpilih
			$qpekx = mysql_query("SELECT * FROM m_pekerjaan ".
									"WHERE kd = '$y_pekkd'");
			$rpekx = mysql_fetch_assoc($qpekx);
			$pekx_kd = nosql($rpekx['kd']);
			$pekx_pekerjaan = balikin($rpekx['pekerjaan']);

			echo '<option value="'.$pekx_kd.'">'.$pekx_pekerjaan.'</option>';

			$qpek = mysql_query("SELECT * FROM m_pekerjaan ".
									"WHERE kd <> '$y_pekkd' ".
									"ORDER BY pekerjaan ASC");
			$rpek = mysql_fetch_assoc($qpek);

			do
				{
				$pek_kd = nosql($rpek['kd']);
				$pek_pekerjaan = balikin($rpek['pekerjaan']);

				echo '<option value="'.$pek_kd.'">'.$pek_pekerjaan.'</option>';
				}
			while ($rpek = mysql_fetch_assoc($qpek));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			38. Penghasilan per Bulan
			</td>
			<td width="10">: </td>
			<td>
			Rp. <input name="pendapatan" type="text" value="'.$y_penghasilan.'" size="10" onKeyPress="return numbersonly(this, event)">,00
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			39. Alamat Rumah / Telepon
			</td>
			<td width="10">: </td>
			<td>
			<input name="alamat" type="text" value="'.$y_alamat.'" size="30"> /
			<input name="telepon" type="text" value="'.$y_telp.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			40. Masih Hidup/Meninggal Dunia, Tahun
			</td>
			<td width="10">: </td>
			<td>
			<input name="hidup" type="text" value="'.$y_hidup.'" size="30">
			</td>
			</tr>
			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="f">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}
		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="g"></a>
		<strong>G. KETERANGAN TENTANG WALI</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=g#g" title="G. KETERANGAN TENTANG WALI">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "g")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_wali.*, ".
						"DATE_FORMAT(m_siswa_wali.tgl_lahir, '%d') AS tgl, ".
						"DATE_FORMAT(m_siswa_wali.tgl_lahir, '%m') AS bln, ".
						"DATE_FORMAT(m_siswa_wali.tgl_lahir, '%Y') AS thn, ".
						"siswa_kelas.* ".
						"FROM m_siswa_wali, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_wali.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_nama = balikin2($rnil['nama']);
			$y_tmp_lahir = balikin2($rnil['tmp_lahir']);
			$y_tgl_lahir = nosql($rnil['tgl']);
			$y_bln_lahir = nosql($rnil['bln']);
			$y_thn_lahir = nosql($rnil['thn']);
			$y_agmkd = nosql($rnil['kd_agama']);
			$y_warga_negara = balikin2($rnil['warga_negara']);
			$y_pendidikan = balikin2($rnil['pendidikan']);
			$y_pekkd = nosql($rnil['kd_pekerjaan']);
			$y_penghasilan = balikin2($rnil['penghasilan']);
			$y_alamat = balikin2($rnil['alamat']);
			$y_telp = balikin2($rnil['telp']);


			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			41. Nama
			</td>
			<td width="10">: </td>
			<td>
			<input name="nama" type="text" value="'.$y_nama.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			42. Tempat dan Tanggal Lahir
			</td>
			<td width="10">: </td>
			<td>
			<input name="tmp_lahir" type="text" value="'.$y_tmp_lahir.'" size="30">,
			<select name="lahir_tgl">
			<option value="'.$y_tgl_lahir.'" selected>'.$y_tgl_lahir.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="lahir_bln">
			<option value="'.$y_bln_lahir.'" selected>'.$arrbln1[$y_bln_lahir].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="lahir_thn">
			<option value="'.$y_thn_lahir.'" selected>'.$y_thn_lahir.'</option>';
			for ($k=$lahir01;$k<=$lahir02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			43. Agama
			</td>
			<td width="10">: </td>
			<td>
			<select name="agama">';

			//terpilih
			$qagmx = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd = '$y_agmkd'");
			$ragmx = mysql_fetch_assoc($qagmx);
			$agmx_kd = nosql($ragmx['kd']);
			$agmx_agama = balikin($ragmx['agama']);

			echo '<option value="'.$agmx_kd.'">'.$agmx_agama.'</option>';

			$qagm = mysql_query("SELECT * FROM m_agama ".
									"WHERE kd <> '$y_agmkd' ".
									"ORDER BY agama ASC");
			$ragm = mysql_fetch_assoc($qagm);

			do
				{
				$agm_kd = nosql($ragm['kd']);
				$agm_agama = balikin($ragm['agama']);

				echo '<option value="'.$agm_kd.'">'.$agm_agama.'</option>';
				}
			while ($ragm = mysql_fetch_assoc($qagm));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			44. Kewarganegaraan
			</td>
			<td width="10">: </td>
			<td>
			<input name="warga_negara" type="text" value="'.$y_warga_negara.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			45. Pendidikan
			</td>
			<td width="10">: </td>
			<td>
			<input name="pendidikan" type="text" value="'.$y_pendidikan.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			46. Pekerjaan
			</td>
			<td width="10">: </td>
			<td>
			<select name="pekerjaan">';

			//terpilih
			$qpekx = mysql_query("SELECT * FROM m_pekerjaan ".
									"WHERE kd = '$y_pekkd'");
			$rpekx = mysql_fetch_assoc($qpekx);
			$pekx_kd = nosql($rpekx['kd']);
			$pekx_pekerjaan = balikin($rpekx['pekerjaan']);

			echo '<option value="'.$pekx_kd.'">'.$pekx_pekerjaan.'</option>';

			$qpek = mysql_query("SELECT * FROM m_pekerjaan ".
									"WHERE kd <> '$y_pekkd' ".
									"ORDER BY pekerjaan ASC");
			$rpek = mysql_fetch_assoc($qpek);

			do
				{
				$pek_kd = nosql($rpek['kd']);
				$pek_pekerjaan = balikin($rpek['pekerjaan']);

				echo '<option value="'.$pek_kd.'">'.$pek_pekerjaan.'</option>';
				}
			while ($rpek = mysql_fetch_assoc($qpek));

			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			47. Penghasilan per Bulan
			</td>
			<td width="10">: </td>
			<td>
			Rp. <input name="pendapatan" type="text" value="'.$y_penghasilan.'" size="10" onKeyPress="return numbersonly(this, event)">,00
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			48. Alamat Rumah / Telepon
			</td>
			<td width="10">: </td>
			<td>
			<input name="alamat" type="text" value="'.$y_alamat.'" size="30"> /
			<input name="telepon" type="text" value="'.$y_telp.'" size="30">
			</td>
			</tr>
			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="g">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="h"></a>
		<strong>H. KEGEMARAN SISWA</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=h#h" title="H. KEGEMARAN SISWA">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "h")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_hobi.*, siswa_kelas.* ".
						"FROM m_siswa_hobi, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_hobi.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_kesenian = balikin2($rnil['kesenian']);
			$y_olah_raga = balikin2($rnil['olah_raga']);
			$y_organisasi = balikin2($rnil['organisasi']);
			$y_lain = balikin2($rnil['lain_lain']);



			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			49. Kesenian
			</td>
			<td width="10">: </td>
			<td>
			<input name="kesenian" type="text" value="'.$y_kesenian.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			50. Olah Raga
			</td>
			<td width="10">: </td>
			<td>
			<input name="olah_raga" type="text" value="'.$y_olah_raga.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			51. Kemasyarakatan / Organisasi
			</td>
			<td width="10">: </td>
			<td>
			<input name="organisasi" type="text" value="'.$y_organisasi.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			52. Lain - Lain
			</td>
			<td width="10">: </td>
			<td>
			<input name="lain_lain" type="text" value="'.$y_lain.'" size="30">
			</td>
			</tr>
			</table>


			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="h">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="i"></a>
		<strong>I. KETERANGAN PERKEMBANGAN SISWA</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=i#i" title="I. KETERANGAN PERKEMBANGAN SISWA">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "i")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_perkembangan.*, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl, '%d') AS tgl, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl, '%m') AS bln, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl, '%Y') AS thn, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl_terima_ijazah, '%d') AS tgl_terima, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl_terima_ijazah, '%m') AS bln_terima, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl_terima_ijazah, '%Y') AS thn_terima, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl_ijazah, '%d') AS tgl_ijazah, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl_ijazah, '%m') AS bln_ijazah, ".
						"DATE_FORMAT(m_siswa_perkembangan.tgl_ijazah, '%Y') AS thn_ijazah, ".
						"siswa_kelas.* ".
						"FROM m_siswa_perkembangan, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_perkembangan.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_bea_siswa = balikin2($rnil['bea_siswa']);
			$y_tgl_tinggal = nosql($rnil['tgl']);
			$y_bln_tinggal = nosql($rnil['bln']);
			$y_thn_tinggal = nosql($rnil['thn']);
			$y_tgl_terima_ijazah = nosql($rnil['tgl_terima']);
			$y_bln_terima_ijazah = nosql($rnil['bln_terima']);
			$y_thn_terima_ijazah = nosql($rnil['thn_terima']);
			$y_tgl_ijazah = nosql($rnil['tgl_ijazah']);
			$y_bln_ijazah = nosql($rnil['bln_ijazah']);
			$y_thn_ijazah = nosql($rnil['thn_ijazah']);
			$y_alasan = balikin2($rnil['alasan']);
			$y_tamat = balikin2($rnil['tamat']);
			$y_no_sttb = balikin2($rnil['no_sttb']);
			$y_pindah_kelas = nosql($rnil['pindah_kelas']);
			$y_no_surat = balikin($rnil['no_surat']);
			$y_ket = balikin($rnil['ket']);





			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			53. Menerima Bea Siswa
			</td>
			<td width="10">: </td>
			<td>
			<input name="bea_siswa" type="text" value="'.$y_bea_siswa.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			54. Meninggalkan Sekolah
			</td>
			<td width="10"></td>
			<td></td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			a. Tanggal Meninggalkan Sekolah
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="tinggal_tgl">
			<option value="'.$y_tgl_tinggal.'" selected>'.$y_tgl_tinggal.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="tinggal_bln">
			<option value="'.$y_bln_tinggal.'" selected>'.$arrbln1[$y_bln_tinggal].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="tinggal_thn">
			<option value="'.$y_thn_tinggal.'" selected>'.$y_thn_tinggal.'</option>';
			for ($k=$tinggal01;$k<=$tinggal02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			b. Alasan
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="alasan" type="text" value="'.$y_alasan.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			c. Kelas Berapa
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="pindah_kelas">
			<option value="'.$y_pindah_kelas.'" selected>'.$y_pindah_kelas.'</option>
			<option value="X">X</option>
			<option value="XI">XI</option>
			<option value="XII">XII</option>
			</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			d. No.Surat
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="no_surat" type="text" value="'.$y_no_surat.'" size="20">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			e. Ket.
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="ket">
			<option value="'.$y_ket.'" selected>'.$y_ket.'</option>
			<option value="DO">DO</option>
			<option value="Pindah">Pindah</option>
			</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			55. Akhir Pendidikan
			</td>
			<td width="10"></td>
			<td></td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			a. Tamat Belajar
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="tamat" type="text" value="'.$y_tamat.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			b. STTB Nomor
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="no_sttb" type="text" value="'.$y_no_sttb.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			c. Tanggal Ijazah
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="ijazah_tgl">
			<option value="'.$y_tgl_ijazah.'" selected>'.$y_tgl_ijazah.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="ijazah_bln">
			<option value="'.$y_bln_ijazah.'" selected>'.$arrbln1[$y_bln_ijazah].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="ijazah_thn">
			<option value="'.$y_thn_ijazah.'" selected>'.$y_thn_ijazah.'</option>';
			for ($k=$tinggal01;$k<=$tinggal02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			d. Tanggal Terima Ijazah
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="ijazah_terima_tgl">
			<option value="'.$y_tgl_terima_ijazah.'" selected>'.$y_tgl_terima_ijazah.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="ijazah_terima_bln">
			<option value="'.$y_bln_terima_ijazah.'" selected>'.$arrbln1[$y_bln_terima_ijazah].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="ijazah_terima_thn">
			<option value="'.$y_thn_terima_ijazah.'" selected>'.$y_thn_terima_ijazah.'</option>';
			for ($k=$tinggal01;$k<=$tinggal02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="i">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btxno" type="hidden" value="'.$btxno.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}

		echo '<br>
		<br>';




		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td>
		<a name="j"></a>
		<strong>J. KETERANGAN SETELAH SELESAI PENDIDIKAN</strong>
		(<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$swkd.'&a=j#j" title="J. KETERANGAN SETELAH SELESAI PENDIDIKAN">EDIT</a>)
		</td>
		</tr>
		</table>
		<br>';

		if ($a == "j")
			{
			//query
			$qnil = mysql_query("SELECT m_siswa_selesai.*, ".
						"DATE_FORMAT(m_siswa_selesai.tgl, '%d') AS tgl, ".
						"DATE_FORMAT(m_siswa_selesai.tgl, '%m') AS bln, ".
						"DATE_FORMAT(m_siswa_selesai.tgl, '%Y') AS thn, ".
						"siswa_kelas.* ".
						"FROM m_siswa_selesai, siswa_kelas ".
						"WHERE siswa_kelas.kd_siswa = m_siswa_selesai.kd_siswa ".
						"AND siswa_kelas.kd_tapel = '$tapelkd' ".
						"AND siswa_kelas.kd_kelas = '$kelkd' ".
						"AND siswa_kelas.kd_siswa = '$swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$y_melanjutkan_di = balikin2($rnil['melanjutkan_di']);
			$y_bekerja = balikin2($rnil['bekerja']);
			$y_tgl_kerja = nosql($rnil['tgl']);
			$y_bln_kerja = nosql($rnil['bln']);
			$y_thn_kerja = nosql($rnil['thn']);
			$y_instansi = balikin2($rnil['instansi']);
			$y_penghasilan = balikin2($rnil['penghasilan']);


			echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr valign="top">
			<td width="200">
			56. Melanjutkan di
			</td>
			<td width="10">: </td>
			<td>
			<input name="melanjutkan_di" type="text" value="'.$y_melanjutkan_di.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			57. Bekerja
			</td>
			<td width="10">: </td>
			<td>
			<input name="bekerja" type="text" value="'.$y_bekerja.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			a. Tanggal Mulai Bekerja
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<select name="kerja_tgl">
			<option value="'.$y_tgl_kerja.'" selected>'.$y_tgl_kerja.'</option>';
			for ($i=1;$i<=31;$i++)
				{
				echo '<option value="'.$i.'">'.$i.'</option>';
				}

			echo '</select>
			<select name="kerja_bln">
			<option value="'.$y_bln_kerja.'" selected>'.$arrbln1[$y_bln_kerja].'</option>';
			for ($j=1;$j<=12;$j++)
				{
				echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
				}

			echo '</select>
			<select name="kerja_thn">
			<option value="'.$y_thn_kerja.'" selected>'.$y_thn_kerja.'</option>';
			for ($k=$kerja01;$k<=$kerja02;$k++)
				{
				echo '<option value="'.$k.'">'.$k.'</option>';
				}
			echo '</select>
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			b. Nama Perusahaan / Lembaga / Lain-lain
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			<input name="instansi" type="text" value="'.$y_instansi.'" size="30">
			</td>
			</tr>

			<tr valign="top">
			<td width="200">
			<dd>
			c. Penghasilan
			</dd>
			</td>
			<td width="10">: </td>
			<td>
			Rp. <input name="penghasilan" type="text" value="'.$y_penghasilan.'" size="10" onKeyPress="return numbersonly(this, event)">,00
			</td>
			</tr>
			</table>

			<input name="s" type="hidden" value="'.$s.'">
			<input name="a" type="hidden" value="j">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnDS" type="submit" value="DAFTAR SISWA >>">';
			}
		echo '<br>
		<br>';
		}
	else
		{
		//query DATA
		$tapelkd = nosql($_REQUEST['tapelkd']);
		$kelkd = nosql($_REQUEST['kelkd']);




		//nis
		if ($crkd == "cr01")
			{
			//query
			$p = new Pager();
			$start = $p->findStart($limit);

			$sqlcount = "SELECT m_siswa.*, ".
							"DATE_FORMAT(m_siswa.tgl_lahir, '%d') AS tgl, ".
							"DATE_FORMAT(m_siswa.tgl_lahir, '%m') AS bln, ".
							"DATE_FORMAT(m_siswa.tgl_lahir, '%Y') AS thn, ".
							"m_siswa.kd AS mskd, ".
							"siswa_kelas.* ".
							"FROM m_siswa, siswa_kelas ".
							"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
							"AND siswa_kelas.kd_tapel = '$tapelkd' ".
							"AND siswa_kelas.kd_kelas = '$kelkd' ".
							"AND m_siswa.nis LIKE '%$kunci%' ".
							"ORDER BY round(m_siswa.nis) ASC";
			$sqlresult = $sqlcount;

			$count = mysql_num_rows(mysql_query($sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
			$target = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&crkd=$crkd&crtipe=$crtipe&kunci=$kunci";
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysql_fetch_array($result);
			}

		//nama
		else if ($crkd == "cr02")
			{
			//query
			$p = new Pager();
			$start = $p->findStart($limit);

			$sqlcount = "SELECT m_siswa.*, ".
							"DATE_FORMAT(m_siswa.tgl_lahir, '%d') AS tgl, ".
							"DATE_FORMAT(m_siswa.tgl_lahir, '%m') AS bln, ".
							"DATE_FORMAT(m_siswa.tgl_lahir, '%Y') AS thn, ".
							"m_siswa.kd AS mskd, ".
							"siswa_kelas.* ".
							"FROM m_siswa, siswa_kelas ".
							"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
							"AND siswa_kelas.kd_tapel = '$tapelkd' ".
							"AND siswa_kelas.kd_kelas = '$kelkd' ".
							"AND m_siswa.nama LIKE '%$kunci%' ".
							"ORDER BY m_siswa.nama ASC";
			$sqlresult = $sqlcount;

			$count = mysql_num_rows(mysql_query($sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
			$target = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&crkd=$crkd&crtipe=$crtipe&kunci=$kunci";
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysql_fetch_array($result);
			}

		else
			{
			//query
			$p = new Pager();
			$start = $p->findStart($limit);

			$sqlcount = "SELECT m_siswa.*, ".
					"DATE_FORMAT(m_siswa.tgl_lahir, '%d') AS tgl, ".
					"DATE_FORMAT(m_siswa.tgl_lahir, '%m') AS bln, ".
					"DATE_FORMAT(m_siswa.tgl_lahir, '%Y') AS thn, ".
					"m_siswa.kd AS mskd, ".
					"siswa_kelas.* ".
					"FROM m_siswa, siswa_kelas ".
					"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
					"AND siswa_kelas.kd_tapel = '$tapelkd' ".
					"AND siswa_kelas.kd_kelas = '$kelkd' ".
					"ORDER BY round(m_siswa.nis) ASC";
			$sqlresult = $sqlcount;

			$count = mysql_num_rows(mysql_query($sqlcount));
			$pages = $p->findPages($count, $limit);
			$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
			$target = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&crkd=$crkd&crtipe=$crtipe&kunci=$kunci";
			$pagelist = $p->pageList($_GET['page'], $pages, $target);
			$data = mysql_fetch_array($result);
			}



		echo '<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr bgcolor="'.$warna02.'">
		<td>
		[<a href="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&s=baru&swkd='.$x.'&a=a#a" title="Entry Data">Entry Data</a>]
		</td>
		<td align="right">';
		echo "<select name=\"katcari\" onChange=\"MM_jumpMenu('self',this,0)\">";
		echo '<option value="'.$filenya.'?crkd='.$crkd.'&crtipe='.$crtipe.'&kunci='.$kunci.'" selected>'.$crtipe.'</option>
		<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&crkd=cr01&crtipe=NIS&kunci='.$kunci.'">NIS</option>
		<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&crkd=cr02&crtipe=Nama&kunci='.$kunci.'">Nama</option>
		</select>
		<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
		<input name="kelkd" type="hidden" value="'.$kelkd.'">
		<input name="kunci" type="text" value="'.$kunci.'" size="30">
		<input name="crkd" type="hidden" value="'.$crkd.'">
		<input name="crtipe" type="hidden" value="'.$crtipe.'">
		<input name="btnCARI" type="submit" value="CARI >>">
		<input name="btnRST" type="submit" value="RESET">
		</td>
		</tr>
		</table>';

		//nek ada
		if ($count != 0)
			{
			echo '<table width="100%" border="1" cellpadding="3" cellspacing="0">
			<tr bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
			<td width="50"><strong>NIS</strong></td>
			<td width="150"><strong>Nama</strong></td>
			<td width="5"><strong>L/P</strong></td>
			<td width="150"><strong>TTL.</strong></td>
			<td width="150"><strong>Nama Orang Tua</strong></td>
			<td><strong>Alamat Rumah</strong></td>
			<td width="100"><strong>Asal Sekolah</strong></td>
			<td width="100"><strong>Telp.</strong></td>
			</tr>';

			do
				{
				if ($warna_set ==0)
					{
					$warna = $warna01;
					$warna_set = 1;
					}
				else
					{
					$warna = $warna02;
					$warna_set = 0;
					}

				$nomer = $nomer + 1;

				$kd = nosql($data['mskd']);
				$nis = nosql($data['nis']);
				$i_userx = nosql($data['usernamex']);
				$i_passx = nosql($data['passwordx']);
				$nama = balikin($data['nama']);
				$kd_kelamin = nosql($data['kd_kelamin']);
				$tmp_lahir = balikin2($data['tmp_lahir']);
				$tgl_lahir = nosql($data['tgl']);
				$bln_lahir = nosql($data['bln']);
				$thn_lahir = nosql($data['thn']);


				//kelamin
				$qmin = mysql_query("SELECT * FROM m_kelamin ".
										"WHERE kd = '$kd_kelamin'");
				$rmin = mysql_fetch_assoc($qmin);
				$min_kelamin = balikin2($rmin['kelamin']);


				//orang tua - ayah
				$qtun = mysql_query("SELECT * FROM m_siswa_ayah ".
										"WHERE kd_siswa = '$kd'");
				$rtun = mysql_fetch_assoc($qtun);
				$tun_nama = balikin2($rtun['nama']);
				$tun_alamat = balikin2($rtun['alamat']);
				$tun_telp = balikin2($rtun['telp']);


				//lulusan dari
				$qpend = mysql_query("SELECT * FROM m_siswa_pendidikan ".
										"WHERE kd_siswa = '$kd'");
				$rpend = mysql_fetch_assoc($qpend);
				$pend_lulusan = balikin2($rpend['lulusan']);




				//cek janissari
				$qcc1 = mysql_query("SELECT * FROM m_user ".
							"WHERE kd = '$kd'");
				$rcc1 = mysql_fetch_assoc($qcc1);
				$tcc1 = mysql_num_rows($qcc1);

				//jika ada, update
				if ($tcc1 != 0)
					{
					//update ke janissari
					mysql_query("UPDATE m_user SET usernamex = '$i_userx', ".
							"passwordx = '$i_passx', ".
							"nomor = '$nis', ".
							"nama = '$nama' ".
							"WHERE kd = '$kd'");
					}
				else
					{
					//masukkan ke janissari
					mysql_query("INSERT INTO m_user(kd, usernamex, passwordx, nomor, nama, tipe, postdate) VALUES ".
							"('$kd', '$i_userx', '$i_passx', '$nis', '$nama', 'SISWA', '$today')");
					}



				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td><input name="kd'.$nomer.'" type="hidden" value="'.$kd.'">
				<input type="checkbox" name="item'.$nomer.'" value="'.$kd.'">
				</td>
				<td>
				<a href="'.$filenya.'?s=edit&tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$kd.'&a=a#a"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
				</td>
				<td>
				<a href="siswa_pdf.php?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&swkd='.$kd.'" target="_blank" title="PRINT..."><img src="'.$sumber.'/img/pdf.gif" width="16" height="16" border="0"></a>
				</td>
				<td valign="top">
				'.$nis.'
				</td>
				<td valign="top">
				'.$nama.'
				</td>
				<td valign="top">
				'.$min_kelamin.'
				</td>
				<td valign="top">
				'.$tmp_lahir.', '.$tgl_lahir.' '.$arrbln1[$bln_lahir].' '.$thn_lahir.'
				</td>
				<td valign="top">
				'.$tun_nama.'
				</td>
				<td valign="top">
				'.$tun_alamat.'
				</td>
				<td valign="top">
				'.$pend_lulusan.'
				</td>
				<td valign="top">
				'.$tun_telp.'
				</td>
				</tr>';
				}
			while ($data = mysql_fetch_assoc($result));

			echo '</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			<td width="250">
			<input type="button" name="Button" value="SEMUA" onClick="checkAll('.$limit.')">
			<input name="btnBTL" type="reset" value="BATAL">
			<input name="btnHPS" type="submit" value="HAPUS">
			<input name="jml" type="hidden" value="'.$limit.'">
			<input name="s" type="hidden" value="'.$s.'">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="swkd" type="hidden" value="'.$swkd.'">
			<input name="total" type="hidden" value="'.$count.'">
			</td>
			<td align="right"><font color="#FF0000"><strong>'.$count.'</strong></font> Data '.$pagelist.'</td>
			</tr>
			</table>';
			}
		}
	}

echo '</form>
<br>
<br>
<br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../inc/niltpl.php");


//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>