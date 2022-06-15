<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $appends=['bond'];
    protected $hidden=[
      'created_at',
      'updated_at',
      'id'
    ];

    public function getBondAttribute(){
        return Bonds::find($this->bond_id);
    }


}
