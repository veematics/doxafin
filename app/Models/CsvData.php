<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    use HasFactory;

    // Set the table name explicitly
    protected $table = 'csv_data';
    
    // Fillable attributes
    protected $fillable = [
        'data_name',
        'data_value',
    ];
}