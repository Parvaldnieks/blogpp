<?php

require "models/Model.php";

class Blog extends Model {
    protected static function getTableName(): string {
        return "posts";
    }
}