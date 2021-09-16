<?php

namespace Armincms\Contract\Models;
 
use Armincms\Contract\Concerns\Authorizable; 
use Armincms\Contract\Contracts\Authenticatable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;  

class ExternalLink extends Model implements Authenticatable
{  
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    use Authorizable;
    use HasFactory; 
}
