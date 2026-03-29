<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRelationship extends Model
{
    protected $table = 'user_relationships';

    protected $fillable = [
        'user_id',
        'related_user_id',
        'related_name',
        'relationship_type',
        'status',
        'invited_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function relatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function getRelationshipTypeLabelAttribute(): string
    {
        $type = (string) ($this->relationship_type ?? '');

        $map = [
            'conjuge' => 'Cônjuge',
            'conjugue' => 'Cônjuge',
            'filho' => 'Filho(a)',
            'filha' => 'Filha',
            'pai' => 'Pai',
            'mae' => 'Mãe',
            'irmao' => 'Irmão(ã)',
            'irma' => 'Irmã',
            'avo' => 'Avô(ó)',
            'ava' => 'Avó',
            'neto' => 'Neto(a)',
            'outro' => 'Outro',
        ];

        if ($type !== '' && array_key_exists($type, $map)) {
            return $map[$type];
        }

        return $type === ''
            ? '—'
            : ucfirst(str_replace('_', ' ', $type));
    }
}
