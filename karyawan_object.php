<?php


class Karyawan
{
    // database connection and table name
    private $conn;
    private $table = 'karyawan';

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT * FROM karyawan';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function create($data)
    {
        $query = 'INSERT INTO
                ' . $this->table . '
            VALUES
                (NULL, :nama, :hari, :bulan, :tahun, :zodiak, :usia)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':hari', $data['hari']);
        $stmt->bindParam(':bulan', $data['bulan']);
        $stmt->bindParam(':tahun', $data['tahun']);
        $stmt->bindParam(':zodiak', $data['zodiak']);
        $stmt->bindParam(':usia', $data['usia']);

        if($stmt->execute()){
            return true;
        }

        die(print_r($stmt->errorInfo()));
    }

    public function calculateZodiac($day, $month)
    {
        $month = intval($month);
        $zodiac = array('', 'Capricorn', 'Aquarius', 'Pisces', 'Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn');
        $last_day = array('', 19, 18, 20, 20, 21, 21, 22, 22, 21, 22, 21, 20, 19);
        return ($day > $last_day[$month]) ? $zodiac[$month + 1] : $zodiac[$month];
    }

    public function calculateBday($tgl)
    {
        $bday = new DateTime($tgl);
        $today = new Datetime(date('d-m-Y'));
        $diff = $today->diff($bday);

        return $diff->y;
    }
}