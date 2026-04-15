<?php

namespace Modules\Secretaria\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Igrejas\App\Models\Church;

class SecretariaDocument extends Model
{
    protected $table = 'secretaria_documents';

    protected $fillable = [
        'title',
        'path',
        'original_name',
        'mime',
        'size',
        'visibility',
        'church_id',
        'uploaded_by_id',
    ];

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }
}
