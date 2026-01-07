<?php  

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Project extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','description','owner','status'   
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo( User::class);
    }
}
