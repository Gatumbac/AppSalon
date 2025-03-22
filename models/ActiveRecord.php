<?php
namespace Model;
abstract class ActiveRecord {
    protected $id;

    // Database
    protected static $db;
    protected static $table = '';
    protected static $columns = [];

    // Alerts && Messages
    protected static $alerts = [];
    
    // Some getters and setters

    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlert($type, $message) {
        static::$alerts[$type][] = $message;
    }

    public static function getAlerts() {
        return static::$alerts;
    }

    // Validation - Subclasses must implement their own validations
    public abstract function validate();

    // Sql query and creation of an object in memory
    public static function executeSQL($query) {
        $result = self::$db->query($query);

        $array = [];
        while($record = $result->fetch_assoc()) {
            $array[] = static::createObject($record);
        }

        $result->free();

        return $array;
    }

    // Create and object equals to the row of the database
    protected static function createObject($record) {
        $object = new static;

        foreach($record as $key => $value ) {
            if(property_exists( $object, $key  )) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    // Identify and link DB attributes
    public function getAttributes() {
        $attributes = [];
        foreach(static::$columns as $column) {
            if($column === 'id') continue;
            $atributtes[$column] = $this->$column;
        }
        return $attributes;
    }

    // Sanitize data for security
    public function sanitizeAttributes() {
        $attributes = $this->getAttributes();
        $data = [];
        foreach($attributes as $key => $value ) {
            $data[$key] = self::$db->escape_string($value);
        }
        return $data;
    }

    // Sincronize the object with the data of the array
    public function sync($args = []) { 
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    //CRUD
    public function save() {
        $result = '';
        if(!is_null($this->id)) {
            $result = $this->update();
        } else {
            $result = $this->create();
        }
        return $result;
    }

    // Returns all records in the database
    public static function all() {
        $query = "SELECT * FROM " . static::$table;
        $result = self::executeSQL($query);
        return $result;
    }

    // Find a record by id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$table  ." WHERE id = {$id}";
        $result = self::executeSQL($query);
        return array_shift($result) ;
    }

    // Returns an specific number of records
    public static function get($limit) {
        $query = "SELECT * FROM " . static::$table . " LIMIT {$limit}";
        $result = self::executeSQL($query);
        return $result;
    }

    // Create a new row in the database
    public function create() {
        $attributes = $this->sanitizeAttributes();

        $query = " INSERT INTO " . static::$table . " ( ";
        $query .= join(', ', array_keys($attributes));
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($attributes));
        $query .= " ') ";

        $result = self::$db->query($query);
        return [
           'result' =>  $result,
           'id' => self::$db->insert_id
        ];
    }

    // Update the record 
    public function update() {
        $attributes = $this->sanitizeAttributes();

        $values = [];
        foreach($attributes as $key => $value) {
            $values[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$table ." SET ";
        $query .=  join(', ', $values);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= "LIMIT 1"; 

        $result = self::$db->query($query);
        return $result;
    }

    // Delete the record 
    public function delete() {
        $query = "DELETE FROM "  . static::$table . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $result = self::$db->query($query);
        return $result;
    }
}