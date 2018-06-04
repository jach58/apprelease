<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    public function releaseDetails()
    {
        $this->hasMany(ReleaseDetails::class);
    }
}
