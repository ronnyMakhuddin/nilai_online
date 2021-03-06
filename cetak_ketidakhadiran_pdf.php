<?php
session_start();
require('pdf/fpdf.php');
class PDF extends FPDF
{
//Page header
function Header()
{
	//Logo
	//$this->Image('',12,10,33);
	//Arial bold 15
	$this->SetFont('Arial','B',15);
	//Move to the right
	$this->Cell(10);
	//Title
	$this->Cell(185,20,'Sistem Informasi Nilai Online SMA Negeri 1 Bumiayu',0,0,'C');
	//Line break
	$this->Ln(20);
}

//Page footer
function Footer(){
	//Position at 1.5 cm from bottom
	$this->SetY(-15);
	//Arial italic 8
	$this->SetFont('Arial','I',8);
	//Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			}
}

$tgl = date('d - m - Y');

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$tahun = $_GET['tahun'];
$pdf->text(75,35,'LAPORAN NILAI KETIDAKHADIRAN');
$pdf->text(102,45,$tahun);
$yi = 60;
$ya = 54;
$pdf->setFont('Arial','',6);
$pdf->setFillColor(222,222,222);
$pdf->setXY(47,$ya);

$pdf->CELL(15,5,'NIS',1,0,'C',1);	
$pdf->CELL(25,5,'Nama',1,0,'C',1);
$pdf->CELL(15,5,'Kelas',1,0,'C',1);									
$pdf->CELL(15,5,'Semester',1,0,'C',1);
$pdf->CELL(10,5,'Sakit',1,0,'C',1);
$pdf->CELL(10,5,'Ijin',1,0,'C',1);
$pdf->CELL(10,5,'Alpha',1,0,'C',1);
$pdf->CELL(30,5,'Keterangan',1,0,'C',1);
$ya = $yi + $row;
include "config/koneksi.php";

$kelas = $_GET[kelas];
$tahun = $_GET[tahun];
$nis = $_GET[nis];
$semester = $_GET[semester];
$query='';
if(!empty($kelas)){
		$queryx.=" and tkelas.Nama_Kelas like '%".$kelas."%'";
}	
if(!empty($tahun)){
	$queryx.=" and ttahun_ajaran.tahun like '%".$tahun."%'";

}	
if(!empty($nis)){
	$queryx.=" and ketidakhadiran.nis like '%".$nis."%'";
	
}	
if(!empty($semester)){
	$queryx.=" and ketidakhadiran.semester like '%".$semester."%'";
	
}

if($_SESSION[status]=="Siswa"){
	$queryx .= " and ketidakhadiran.nis='$_SESSION[nama_user]' ";
	}

$sql = mysql_query("SELECT tsiswa.Nama, tkelas.Nama_Kelas, ketidakhadiran.*  
							 FROM tsiswa 
							 	INNER JOIN ketidakhadiran ON ketidakhadiran.nis = tsiswa.nis
							 	INNER JOIN tkelas ON tkelas.ID_Kelas = ketidakhadiran.id_kelas
								INNER JOIN ttahun_ajaran ON ttahun_ajaran.id = tsiswa.tahun_ajaran
								where ketidakhadiran.id_ketidakhadiran <> '' $queryx
								
								")or die ("Error");
$i = 1;
$no = 1;
$max = 31;
$row = 6;
while($data = mysql_fetch_array($sql)){
$pdf->setXY(47,$ya);
$pdf->setFont('arial','',6);
$pdf->setFillColor(255,255,255);

$pdf->cell(15,5,$data[nis],1,0,'C',1);
$pdf->cell(25,5,$data[Nama],1,0,'C',1);
$pdf->cell(15,5,$data[Nama_Kelas],1,0,'C',1);
$pdf->CELL(15,5,$data[semester],1,0,'C',1);
$pdf->CELL(10,5,$data[sakit],1,0,'C',1);
$pdf->CELL(10,5,$data[ijin],1,0,'C',1);
$pdf->CELL(10,5,$data[alpha],1,0,'C',1);
$pdf->CELL(30,5,$data[keterangan],1,0,'C',1);

$ya = $ya+$row;
$no++;
$i++;
$dm[kode] = $data[kdprog];
}


$pdf->Output();
?>