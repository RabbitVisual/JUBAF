<?php

namespace Modules\Talentos\App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Talentos\App\Models\TalentSkill;

class TalentSkillValidated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public User $youth,
        public TalentSkill $skill,
        public User $validatedBy
    ) {}
}
