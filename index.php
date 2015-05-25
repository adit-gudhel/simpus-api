<?php
/**
 * @author : Aditya Nursyahbani,S.SI
 * @web : http://aditya-nursyahbani.com
 * @keterangan : Webservice Simpus Kota Bogor (format JSON)
 **/

ini_set('memory_limit', '-1');
require 'Slim/Slim.php';

$app = new Slim();

/********************************************************/
/* GET METHOD											*/
/********************************************************/
// pasien
$app->get('/rekap/pasien/:date', 'getPasienToday');
$app->get('/rekap/pasien/month/:month/:year', 'getPasienMonthly');
$app->get('/rekap/pasien/year/:year', 'getPasienYearly');

// pelayanan
$app->get('/rekap/pelayanan/:date', 'getKunjunganToday');
$app->get('/rekap/pelayanan/month/:month/:year', 'getKunjunganMonthly');
$app->get('/rekap/pelayanan/year/:year', 'getKunjunganYearly');

// penyakit
$app->get('/rekap/penyakit/icd/:icd/date/:date', 'getPenyakitByIcdToday');
$app->get('/rekap/penyakit/icd/:icd/month/:month/:year', 'getPenyakitByIcdMonthly');
$app->get('/rekap/penyakit/icd/:icd/year/:year', 'getPenyakitByIcdYearly');

// obat
$app->get('/rekap/obat/kode_obat/:kd_obat/date/:date', 'getObatByKodeToday');
$app->get('/rekap/obat/kode_obat/:kd_obat/month/:month/:year', 'getObatByKodeMonthly');
$app->get('/rekap/obat/kode_obat/:kd_obat/year/:year', 'getObatByKodeYearly');

/********************************************************/
/* POST METHOD											*/
/********************************************************/


$app->run();

function getPasienToday($date){
    $sql = "SELECT COUNT(*) as total FROM pasien WHERE tanggal_daftar = :date";
    $response = array();

    try {
        $db = getConnection();
        $tgl = convert_date_sql($date);
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":date", $tgl, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['pasien'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data pasien!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getPasienMonthly($month, $year){
    $sql = "SELECT COUNT(*) as total FROM pasien WHERE MONTH(tanggal_daftar) = :month AND YEAR(tanggal_daftar) = :year";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":month", $month, PDO::PARAM_INT);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['pasien'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data pasien!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getPasienYearly($year){
    $sql = "SELECT COUNT(*) as total FROM pasien WHERE YEAR(tanggal_daftar) = :year";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['pasien'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data pasien!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getKunjunganToday($date){
    $sql = "SELECT COUNT(*) as total FROM pelayanan WHERE tgl_pelayanan= :date";
    $response = array();

    try {
        $db = getConnection();
        $tgl = convert_date_sql($date);
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":date", $tgl, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['pelayanan'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data pelayanan!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getKunjunganMonthly($month, $year){
    $sql = "SELECT COUNT(*) as total FROM pelayanan WHERE MONTH(tgl_pelayanan) = :month AND YEAR(tgl_pelayanan) = :year";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":month", $month, PDO::PARAM_INT);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['pelayanan'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data pelayanan!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getKunjunganYearly($year){
    $sql = "SELECT COUNT(*) as total FROM pelayanan WHERE YEAR(tgl_pelayanan) = :year";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['pelayanan'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data pasien!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getPenyakitByIcdToday($icd_code, $date){
    $sql = "SELECT COUNT(*) as total FROM pelayanan_penyakit LEFT JOIN pelayanan ON pelayanan.kd_trans_pelayanan = pelayanan_penyakit.kd_trans_pelayanan WHERE pelayanan_penyakit.kd_penyakit = :icd_code and pelayanan.tgl_pelayanan= :date";
    $response = array();

    try {
        $db = getConnection();
        $tgl = convert_date_sql($date);
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":icd_code", $icd_code, PDO::PARAM_STR);
        $stmt->bindParam(":date", $tgl, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['penyakit'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data penyakit!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getPenyakitByIcdMonthly($icd_code, $month, $year){
    $sql = "SELECT COUNT(*) as total FROM pelayanan_penyakit LEFT JOIN pelayanan ON pelayanan.kd_trans_pelayanan = pelayanan_penyakit.kd_trans_pelayanan WHERE pelayanan_penyakit.kd_penyakit = :icd_code AND (MONTH(pelayanan.tgl_pelayanan) = :month AND YEAR(pelayanan.tgl_pelayanan) = :year)";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":icd_code", $icd_code, PDO::PARAM_STR);
        $stmt->bindParam(":month", $month, PDO::PARAM_INT);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['penyakit'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data penyakit!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getPenyakitByIcdYearly($icd_code, $year){
    $sql = "SELECT COUNT(*) as total FROM pelayanan_penyakit LEFT JOIN pelayanan ON pelayanan.kd_trans_pelayanan = pelayanan_penyakit.kd_trans_pelayanan WHERE pelayanan_penyakit.kd_penyakit = :icd_code AND YEAR(pelayanan.tgl_pelayanan) = :year";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":icd_code", $icd_code, PDO::PARAM_STR);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['penyakit'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data penyakit!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getObatByKodeToday($kd_obat, $date){
    $sql = "SELECT SUM(pelayanan_obat.qty) as total FROM pelayanan_obat LEFT JOIN pelayanan ON pelayanan.kd_trans_pelayanan = pelayanan_obat.kd_trans_pelayanan WHERE pelayanan_obat.kd_obat = :kd_obat AND pelayanan.tgl_pelayanan = :date";
    $response = array();

    try {
        $db = getConnection();
        $tgl = convert_date_sql($date);
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":kd_obat", $kd_obat, PDO::PARAM_STR);
        $stmt->bindParam(":date", $tgl, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['obat'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data obat!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getObatByKodeMonthly($kd_obat, $month, $year){
    $sql = "SELECT SUM(pelayanan_obat.qty) as total FROM pelayanan_obat LEFT JOIN pelayanan ON pelayanan.kd_trans_pelayanan = pelayanan_obat.kd_trans_pelayanan WHERE pelayanan_obat.kd_obat = :kd_obat AND (MONTH(pelayanan.tgl_pelayanan) = :month AND YEAR(pelayanan.tgl_pelayanan) = :year )";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":kd_obat", $kd_obat, PDO::PARAM_STR);
        $stmt->bindParam(":month", $month, PDO::PARAM_INT);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['obat'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data obat!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

function getObatByKodeYearly($kd_obat, $year){
    $sql = "SELECT SUM(pelayanan_obat.qty) as total FROM pelayanan_obat LEFT JOIN pelayanan ON pelayanan.kd_trans_pelayanan = pelayanan_obat.kd_trans_pelayanan WHERE pelayanan_obat.kd_obat = :kd_obat AND YEAR(pelayanan.tgl_pelayanan) = :year";
    $response = array();

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":kd_obat", $kd_obat, PDO::PARAM_STR);
        $stmt->bindParam(":year", $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;

        $response['status'] = true;
        $response['obat'] = $result;
    } catch (PDOException $e) {
        $response['status'] = false;
        $response['message'] = "Tidak ada data penyakit!";
    }
    //echo '<pre>';
    //echo print_r($response);
    echo json_encode($response);
}

/* ------------------------------
	// Konversi tanggal sql ke tgl indo
	// Y-m-d => d/m/Y
	// Usage :  convert_date_indo(array("datetime" => "2014-12-31")) return 21/12/2014
/*------------------------------*/
function convert_date_indo($array)
{
	$datetime=$array['datetime'];
	$y=substr($datetime,0,4);
	$m=substr($datetime,5,2);
	$d=substr($datetime,8,2);
	$conv_datetime=date("d-m-Y",mktime(1,0,0,$m,$d,$y));#"$d / $m / $y";
	
	return($conv_datetime);
}
	
/* ------------------------------
// Konversi tanggal tgl indo ke sql
// Usage :  convert_date_sql("31/12/2014") return 2014-12-31
-------------------------------*/
function convert_date_sql($date){
	// list($day, $month, $year) = split('[/.-]', $date); => DEPRECATED
	list($day, $month, $year) = preg_split('/[\/\.\-]/', $date);
	return "$year-".sprintf("%02d", $month)."-".sprintf("%02d", $day);
}

function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="";
	$dbname="pus_bogortimur";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
?>