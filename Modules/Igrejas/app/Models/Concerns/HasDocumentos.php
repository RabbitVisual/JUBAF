<?php

namespace Modules\Igrejas\App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Modules\Secretaria\App\Models\SecretariaDocument;

trait HasDocumentos
{
    /**
     * Documentos da secretaria vinculados à igreja.
     *
     * @return HasMany<SecretariaDocument, $this>
     */
    public function secretariaDocuments(): HasMany
    {
        return $this->hasMany(SecretariaDocument::class, 'church_id');
    }

    public function secretariaDocumentsCount(): int
    {
        if (! Schema::hasTable('secretaria_documents')) {
            return 0;
        }

        return $this->secretariaDocuments()->count();
    }
}
