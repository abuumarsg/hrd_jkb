<html>
<head>
<title>CSS - Page Break</title>
<body bgcolor="#ffffff">
<center>
<?php for ($i=1; $i < 10; $i++) {
    # code...
    echo '<div style="page-break-before:always;"> Halaman '.$i.' </div>';
} ?>
<!-- <div style="page-break-after:always;"> Halaman 3 </div>
Gunakan print preview untuk melihat hasilnya -->
</center>
</body>
</html>