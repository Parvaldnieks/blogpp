<?php
require "Database.php";

/**
 * Abstraktā klase Model nodrošina pamatfunkcionalitāti datu modelim.
 * Tā satur statisku metodi init(), kas tiek izmantota datu bāzes savienojuma inicializēšanai.
 * Metode pārbauda, vai savienojums jau ir izveidots (t.i., vai statiskā īpašība $db nav null).
 * Ja savienojums vēl nav izveidots, tiek izveidots jauns Database klases instances objekts un piešķirts statiskajai īpašībai $db.
 * Tādējādi datu bāzes savienojums tiek izveidots tikai vienu reizi, pat ja tajā pašā pieprasījumā tiek izmantoti vairāki modeļi.
 * 
 * Klase satur arī abstraktu metodi getTableName(), kuru jāīsteno jebkurai klasei, kas paplašina Model klasi.
 * Šai metodei ir jāatgriež datu bāzes tabulas nosaukums, kuru šis modelis pārstāv.
 * 
 * Klase satur arī statisku metodi all(), kas atgriež visus ierakstus no tabulas.
 */
abstract class Model {
    protected static $db;

    /**
     * Inicializē datu bāzes savienojumu.
     */
    public static function init() {
        // Ja datu bāzes savienojums vēl nav izveidots, izveido jaunu Database klases instanci
        if (!self::$db) {
            self::$db = new Database();
        }
    }

    /**
     * Atgriež datu bāzes tabulas nosaukumu, kuru pārstāv modelis.
     * Šī metode ir obligāti jāīsteno jebkurai klasei, kas paplašina Model klasi.
     */
    abstract protected static function getTableName(): string;

    /**
     * Atgriež visus ierakstus no tabulas.
     */
    public static function all() {
        self::init();
        $sql = "SELECT * FROM " . static::getTableName();
        // Izpilda vaicājumu un atgriež rezultātus
        $records = self::$db->query($sql)->fetchAll();
        return  $records;
    }

    /**
     * Atrod ierakstu pēc ID.
     */
    public static function find($id) {
        self::init();
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE id = ?";
        $stmt = self::$db->query($sql, [$id]);
        return $stmt->fetch();
    }

    /**
     * Izveido jaunu ierakstu.
     */
    public static function create($data) {
        self::init();

        $columns = implode(", ", array_keys($data)); // Pārvērš atslēgas par kolonnu nosaukumiem
        $placeholders = implode(", ", array_fill(0, count($data), "?")); // Izveido ?, ?, ?
        $values = array_values($data); // Izvelk vērtības

        $sql = "INSERT INTO " . static::getTableName() . " ($columns) VALUES ($placeholders)";
        return self::$db->query($sql, $values);
    }

    /**
     * Atjaunina esošu ierakstu.
     */
    public static function save($id, $data) {
        self::init();
        
        $fields = [];
        $params = [":id" => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE " . static::getTableName() . " SET " . implode(", ", $fields) . " WHERE id = :id";
        return self::$db->query($sql, $params);
    }

    /**
     * Dzēš ierakstu.
     */
    public static function delete($id) {
        self::init();
        $sql = "DELETE FROM " . static::getTableName() . " WHERE id = ?";
        return self::$db->query($sql, [$id]);
    }
}