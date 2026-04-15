<?php

namespace Modules\Financeiro\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinTransaction;

class FinanceiroDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => FinCategory::CODE_REC_INSCRICOES_EVENTOS,
                'name' => 'Inscrições e eventos (online / presencial)',
                'direction' => 'in',
                'group_key' => FinCategory::GROUP_RECEITAS_OPERACIONAIS,
                'sort_order' => 15,
                'description' => 'Receitas de CONJUBAF, retiros e eventos; alinhado ao Gateway e ao calendário.',
                'is_system' => true,
            ],
            [
                'code' => 'REC_COTAS_CONTRIB',
                'name' => 'Cotas e contribuições de jovens / igrejas',
                'direction' => 'in',
                'group_key' => FinCategory::GROUP_RECEITAS_OPERACIONAIS,
                'sort_order' => 20,
            ],
            [
                'code' => 'REC_OFERTAS',
                'name' => 'Ofertas e dízimos destinados à JUBAF',
                'direction' => 'in',
                'group_key' => FinCategory::GROUP_RECEITAS_OPERACIONAIS,
                'sort_order' => 30,
            ],
            [
                'code' => 'REC_VERBA_ASBAF',
                'name' => 'Verbas ASBAF / repasses e convénios',
                'direction' => 'in',
                'group_key' => FinCategory::GROUP_RECEITAS_FINANCEIRAS,
                'sort_order' => 40,
            ],
            [
                'code' => 'REC_DOACOES',
                'name' => 'Doações, campanhas e patrocínios',
                'direction' => 'in',
                'group_key' => FinCategory::GROUP_RECEITAS_FINANCEIRAS,
                'sort_order' => 50,
            ],
            [
                'code' => 'REC_JUROS',
                'name' => 'Rendimentos financeiros (juros / aplicações)',
                'direction' => 'in',
                'group_key' => FinCategory::GROUP_RECEITAS_FINANCEIRAS,
                'sort_order' => 60,
            ],
            [
                'code' => 'APL_PROJETOS',
                'name' => 'Projectos e missões (receita aplicada)',
                'direction' => 'in',
                'group_key' => FinCategory::GROUP_APLICACAO_DIRETA,
                'sort_order' => 70,
            ],
            [
                'code' => FinCategory::CODE_DES_REEMBOLSO,
                'name' => 'Reembolso a aprovados (despesa)',
                'direction' => 'out',
                'group_key' => FinCategory::GROUP_DESPESAS_OPERACIONAIS,
                'sort_order' => 110,
                'description' => 'Usado automaticamente quando a tesouraria marca um pedido como pago.',
                'is_system' => true,
            ],
            [
                'code' => 'DES_OPERACIONAL',
                'name' => 'Despesas operacionais (eventos, logística, materiais)',
                'direction' => 'out',
                'group_key' => FinCategory::GROUP_DESPESAS_OPERACIONAIS,
                'sort_order' => 120,
            ],
            [
                'code' => 'DES_COMUNICACAO',
                'name' => 'Comunicação, marketing e presença digital',
                'direction' => 'out',
                'group_key' => FinCategory::GROUP_DESPESAS_OPERACIONAIS,
                'sort_order' => 130,
            ],
            [
                'code' => 'DES_ADMIN',
                'name' => 'Despesas administrativas (contabilidade, jurídico, seguros)',
                'direction' => 'out',
                'group_key' => FinCategory::GROUP_DESPESAS_ADMINISTRATIVAS,
                'sort_order' => 140,
            ],
            [
                'code' => 'DES_PESSOAS',
                'name' => 'Honorários, transporte e apoio a equipas',
                'direction' => 'out',
                'group_key' => FinCategory::GROUP_DESPESAS_ADMINISTRATIVAS,
                'sort_order' => 150,
            ],
            [
                'code' => 'DES_OUTROS',
                'name' => 'Outras despesas (classificar depois)',
                'direction' => 'out',
                'group_key' => FinCategory::GROUP_OUTROS,
                'sort_order' => 190,
            ],
        ];

        foreach ($rows as $row) {
            $code = $row['code'];
            FinCategory::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $row['name'],
                    'direction' => $row['direction'],
                    'group_key' => $row['group_key'] ?? null,
                    'sort_order' => $row['sort_order'] ?? 100,
                    'description' => $row['description'] ?? null,
                    'is_active' => true,
                    'is_system' => $row['is_system'] ?? false,
                ]
            );
        }

        // Junta categorias antigas (sem código) às canónicas por código, para não violar UNIQUE nem perder lançamentos.
        $legacy = [
            'Inscrições / eventos' => FinCategory::CODE_REC_INSCRICOES_EVENTOS,
            'Oferta de igreja' => 'REC_OFERTAS',
            'Verba ASBAF / repasses' => 'REC_VERBA_ASBAF',
            'Doações e campanhas' => 'REC_DOACOES',
            'Despesa operacional' => 'DES_OPERACIONAL',
            'Reembolso aprovado' => FinCategory::CODE_DES_REEMBOLSO,
        ];
        foreach ($legacy as $name => $code) {
            $canonical = FinCategory::query()->where('code', $code)->first();
            $dup = FinCategory::query()->whereNull('code')->where('name', $name)->first();
            if ($canonical && $dup && $canonical->id !== $dup->id) {
                FinTransaction::query()->where('category_id', $dup->id)->update(['category_id' => $canonical->id]);
                $dup->delete();
            }
        }
    }
}
