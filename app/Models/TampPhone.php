<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TampPhone extends Model
{
  protected $table = 'tamp_phone';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = ['phone', 'code'];
}
