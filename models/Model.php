<?php
require "Database.php";

/**
 * Abstract class Model provides a basic implementation of a data model.
 * It contains a static method init() which is used to initialize the database connection.
 * The method checks if the database connection has already been established (i.e. if the static property $db is not null).
 * If the connection has not been established, it creates a new instance of the Database class and assigns it to the static property $db.
 * This way, the database connection is only established once, even if multiple models are used in the same request.
 * 
 * The class also contains an abstract method getTableName() which must be implemented by any class that extends Model.
 * This method should return the name of the database table that the model represents.
 * 
 * The class also contains a static method all() which returns all records from the table.
 */
abstract class Model {
    protected static $db;

    /**
     * Initializes the database connection.
     */
    public static function init() {
        // If the database connection has not been established, create a new instance of the Database class
        if (!self::$db) {
            self::$db = new Database();
        }
    }

    /**
     * Returns the name of the database table that the model represents.
     * This method must be implemented by any class that extends Model.
     */
    abstract protected static function getTableName(): string;

    /**
     * Returns all records from the table.
     */
    public static function all() {
        self::init();
        $sql = "SELECT * FROM " . static::getTableName();
        // Execute the query and return the results
        $records = self::$db->query($sql)->fetchAll();
        return  $records;
    }

    /**
     * Find a record by ID.
     */
    public static function find($id) {
        self::init();
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE id = ?";
        $stmt = self::$db->query($sql, [$id]);
        return $stmt->fetch();
    }

    /**
     * Create a new record.
     */
    public static function create($data) {
        self::init();

        $columns = implode(", ", array_keys($data)); // Convert keys to column names
        $placeholders = implode(", ", array_fill(0, count($data), "?")); // Create ?, ?, ?
        $values = array_values($data); // Extract values

        $sql = "INSERT INTO " . static::getTableName() . " ($columns) VALUES ($placeholders)";
        return self::$db->query($sql, $values);
    }

    /**
     * Update an existing record.
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
     * Delete a record.
     */
    public static function delete($id) {
        self::init();
        $sql = "DELETE FROM " . static::getTableName() . " WHERE id = ?";
        return self::$db->query($sql, [$id]);
    }
}