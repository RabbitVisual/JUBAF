<?php

namespace Modules\Governance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaItem extends Model
{
    protected $table = 'governance_agenda_items';

    protected $fillable = ['assembly_id', 'sort_order', 'title', 'description'];

    public function assembly(): BelongsTo
    {
        return $this->belongsTo(Assembly::class, 'assembly_id');
    }
}
