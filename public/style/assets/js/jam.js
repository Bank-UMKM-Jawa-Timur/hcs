function showTime(){
    var date = new Date();
	var h = date.getHours();
	var m = date.getMinutes();
	var s = date.getSeconds();

	h = (h<10) ? "0" + h : h;
	m = (m<10) ? "0" + m : m;
	// s = (s<10) ? "0" + s : s;

	var time = h + ":" + m + " " + "WIB";

    var tahun= date.getFullYear ();
    var hari= date.getDay ();
    var bulan= date.getMonth ();
    var tanggal= date.getDate ();
    var hariarray=new Array("Minggu,","Senin,","Selasa,","Rabu,","Kamis,","Jum'at,","Sabtu,");
    var bulanarray=new Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

    document.getElementById("DisplayClock").innerHTML = tanggal+" "+bulanarray[bulan]+" "+tahun+" | Jam " + time;
    setTimeout(showTime, 1000);
}

showTime();