<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once 'database.php';
include_once 'karyawan_object.php';

$database = new Database();
$db = $database->getConnection();

$karyawan = new Karyawan($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $karyawan->read();
    $num = $stmt->rowCount();

    if($num>0){
        $karyawanArr = [];
        $karyawanArr['data'] = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $karyawanItem = array(
                'no' => $row['no'],
                'nama' => $row['nama'],
                'hari' => $row['hari'],
                'bulan' => $row['bulan'],
                'tahun' => $row['tahun'],
                'zodiak' => $row['zodiak'],
                'usia' => $row['usia'],
            );

            array_push($karyawanArr['data'], $karyawanItem);
        }

        http_response_code(200);

        echo json_encode($karyawanArr);
    } else {
        http_response_code(404);

        echo json_encode(
            array('message' => 'Karyawan tidak ditemukan.')
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));

    $tglLahir = explode('-', $data->tglLahir);

    $nama = $data->nama;
    $hari = $tglLahir[0];
    $bulan = $tglLahir[1];
    $tahun = $tglLahir[2];

    $dataKaryawan = [
        'nama' => $nama,
        'hari' => $hari,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'zodiak' => $karyawan->calculateZodiac($hari, $bulan),
        'usia' => $karyawan->calculateBday($data->tglLahir),
    ];

    if ($karyawan->create($dataKaryawan)) {
        http_response_code(201);

        echo json_encode(array('message' => 'Karyawan telah dibuat.'));
    } else {
        http_response_code(503);

        echo json_encode(array('message' => 'Gagal membuat karyawan.'));
    }
}