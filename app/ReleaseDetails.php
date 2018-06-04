<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReleaseDetails extends Model
{
    public $timestamps = false;

    public function release()
    {
        $this->belongsTo(Release::class);
    }
}
