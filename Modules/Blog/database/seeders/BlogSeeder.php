<?php

namespace Modules\Blog\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Blog\App\Models\BlogPost;
use Modules\Blog\App\Models\BlogTag;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Notícias',
                'slug' => 'noticias',
                'description' => 'Notícias e comunicados da JUBAF.',
                'color' => '#3B82F6',
                'icon' => 'newspaper',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Eventos e congressos',
                'slug' => 'eventos-e-congressos',
                'description' => 'CONJUBAF, encontros de setores e agenda juvenil.',
                'color' => '#10B981',
                'icon' => 'calendar-days',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Vida nas igrejas',
                'slug' => 'vida-nas-igrejas',
                'description' => 'Unijovens, congregações e iniciativas locais.',
                'color' => '#F59E0B',
                'icon' => 'church',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Institucional',
                'slug' => 'institucional',
                'description' => 'Diretoria, estatuto e transparência.',
                'color' => '#8B5CF6',
                'icon' => 'building-columns',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        $categorySlugs = [];
        foreach ($categories as $categoryData) {
            $categorySlugs[] = $categoryData['slug'];
            BlogCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
        BlogCategory::whereNotIn('slug', $categorySlugs)->delete();

        $tags = [
            'JUBAF', 'ASBAF', 'estatuto', 'juventude', 'Feira de Santana', 'Batistas',
            'CONJUBAF', 'Unijovens', 'assembleia', 'diretoria', 'institucional',
            'comunidade', 'fé', 'serviço',
        ];

        $tagSlugs = [];
        foreach ($tags as $tagName) {
            $slug = Str::slug($tagName);
            $tagSlugs[] = $slug;
            BlogTag::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $tagName,
                    'slug' => $slug,
                    'color' => '#6B7280',
                ]
            );
        }
        BlogTag::whereNotIn('slug', $tagSlugs)->delete();

        // Remove posts de demonstração legados (Vertex / SEMAGRI / secretaria municipal) e o antigo post de boas-vindas genérico
        BlogPost::query()
            ->where(function ($q) {
                $q->where('slug', 'like', '%vertex%')
                    ->orWhere('slug', 'like', '%semagri%')
                    ->orWhere('title', 'like', '%VERTEX%')
                    ->orWhere('title', 'like', '%SEMAGRI%')
                    ->orWhere('title', 'like', '%Secretaria Municipal de Agricultura%')
                    ->orWhere('content', 'like', '%VERTEXSEMAGRI%')
                    ->orWhere('content', 'like', '%Secretaria Municipal de Agricultura%')
                    ->orWhere('slug', 'bem-vindos-ao-blog-jubaf');
            })
            ->delete();

        $user = User::first();
        if ($user) {
            $category = BlogCategory::where('slug', 'institucional')->first();
            if ($category) {
                $post = BlogPost::updateOrCreate([
                    'slug' => 'estatuto-da-jubaf-capitulos-i-a-vi',
                ], [
                    'title' => 'Estatuto da JUBAF — Capítulos I a VI (texto integral para consulta)',
                    'excerpt' => 'Texto de referência institucional: denominação, assembleias, diretoria, conselho de coordenação, receita, património e disposições gerais da Juventude Batista Feirense (JUBAF), em articulação com a ASBAF.',
                    'content' => $this->getStatutoPostHtml(),
                    'category_id' => $category->id,
                    'author_id' => $user->id,
                    'status' => 'published',
                    'published_at' => now(),
                    'is_featured' => true,
                    'allow_comments' => false,
                    'meta_title' => 'Estatuto da JUBAF — consulta',
                    'meta_description' => 'Estatuto da Juventude Batista Feirense (JUBAF): capítulos I a VI. ASBAF, assembleias, diretoria, conselho de coordenação, receita e património.',
                    'meta_keywords' => ['JUBAF', 'estatuto', 'ASBAF', 'Juventude Batista Feirense', 'CONJUBAF', 'assembleia', 'diretoria'],
                ]);

                $postTags = BlogTag::whereIn('slug', [
                    'jubaf', 'asbaf', 'estatuto', 'institucional', 'conjubaf', 'diretoria',
                ])->get();
                $post->tags()->sync($postTags->pluck('id')->all());
            }
        }
    }

    /**
     * Conteúdo alinhado a PLANOJUBAF/ESTATUTOJUBAF.md (linhas 1–165 — apenas o estatuto, capítulos I a VI).
     */
    private function getStatutoPostHtml(): string
    {
        return <<<'HTML'
<p class="text-slate-600 dark:text-slate-400 text-sm border-l-4 border-blue-500 pl-4 py-2 mb-8"><strong>Nota:</strong> publicação de referência para a comunidade JUBAF. O texto reproduz a estrutura do estatuto (Capítulos I a VI). Em caso de dúvida jurídica ou atualização formal, prevalece o documento aprovado e registado nos termos do Art. 31.</p>

<h2>CAPÍTULO I – DA DENOMINAÇÃO, CONSTITUIÇÃO, SEDE E FINS</h2>

<p><strong>Art. 1º</strong> — A Juventude Batista Feirense, e doravante neste Estatuto denominada JUBAF, é uma entidade civil de natureza religiosa, sem fins lucrativos e inspirada nos princípios cristãos, com sede e foro na cidade de Feira de Santana, sendo ilimitado o número de seus membros e indeterminado o tempo de sua duração.</p>

<p><strong>Art. 2º</strong> — A JUBAF é composta pelos jovens das Igrejas Batistas arroladas à Associação Batista Feirense, e doravante neste Estatuto denominada ASBAF.</p>

<p><strong>Art. 3º</strong> — A JUBAF tem por finalidade promover, no âmbito associacional, com os jovens batistas as seguintes atividades:</p>
<ul>
<li>a) Promover a integração do jovem na família, na Igreja, na denominação e na sociedade em que vivem, como elementos influentes e transmissores da palavra de Deus;</li>
<li>b) Realizar congressos, acampamentos, concursos, encontro de líderes, cursos, estudos, institutos inspirativos, trabalho com estudantes, atividades ligadas ao esporte, artes, cultura, educacionais e outras que atendam aos seus fins;</li>
<li>c) Articular-se com as Unijovens e assisti-las na programação de seus conclaves e demais realizações;</li>
<li>d) Articular-se com órgãos e conclaves dos jovens batistas, promovendo a integração associacional.</li>
</ul>

<h2>CAPÍTULO II – DAS ASSEMBLEIAS</h2>

<p><strong>Art. 4º</strong> — A Assembleia é o órgão soberano da JUBAF que se reunirá ordinariamente uma vez por ano e extraordinariamente, sempre que necessário.</p>

<p><strong>Art. 5º</strong> — As Assembleias ordinárias ocorrerão por ocasião e na mesma localidade onde estiver sendo realizado o congresso da JUBAF – CONJUBAF.</p>
<p><em>Parágrafo Único</em> — O CONJUBAF acontecerá anualmente conforme previsto em regimento próprio.</p>

<p><strong>Art. 6º</strong> — A Assembleia será constituída com a presença de qualquer número de membros, que a elas comparecerão como mensageiros de suas respectivas Igrejas, devendo, as mesmas serem arroladas à ASBAF.</p>
<p><em>Parágrafo Único</em> — As Assembleias extraordinárias serão convocadas com trinta dias de antecedência para tratar exclusivamente de assuntos inadiáveis, os quais constarão do termo de convocação.</p>

<h2>CAPÍTULO III – DA DIRETORIA</h2>

<p><strong>Art. 7º</strong> — Na Assembleia ordinária será eleita a diretoria da JUBAF, composta de um presidente, 1º e 2º vice-presidentes, 1º e 2º secretários e 1º e 2º tesoureiros, que se sucedem respectivamente.</p>

<p><strong>Art. 8º</strong> — A posse da diretoria eleita dar-se-á na última sessão da Assembleia ordinária em que foram eleitos.</p>

<p><strong>Art. 9º</strong> — Ao presidente que é o orientador da ordem compete:</p>
<ul>
<li>a) Representar a JUBAF, ativa e passivamente, judicial e extrajudicialmente;</li>
<li>b) Convocar e dirigir as Assembleias e reuniões;</li>
<li>c) Organizar, com a aprovação da diretoria, programa provisório das Assembleias;</li>
<li>d) Nomear, logo após a instalação das Assembleias, uma comissão de indicações, que indicará os nomes dos componentes das comissões de pareceres.</li>
</ul>

<p><strong>Art. 10º</strong> — Ao 1º vice-presidente compete substituir o presidente nas suas faltas ou impedimentos.</p>
<p><strong>Art. 11</strong> — Ao 2º vice-presidente compete substituir o 1º vice-presidente nas suas faltas ou impedimentos.</p>
<p><strong>Art. 12</strong> — Ao 1º secretário compete redigir as atas das sessões, bem como, assiná-las juntamente com o presidente.</p>
<p><strong>Art. 13</strong> — Ao 2º secretário compete substituir o 1º secretário nas suas faltas ou impedimentos.</p>
<p><strong>Art. 14</strong> — Ao 1º tesoureiro compete cuidar da área financeira da JUBAF, prestando as suas contas na Assembleia Ordinária.</p>
<p><strong>Art. 15</strong> — Ao 2º tesoureiro compete substituir o 1º tesoureiro em suas faltas ou impedimentos.</p>

<h2>CAPÍTULO IV – DA ADMINISTRAÇÃO</h2>

<p><strong>Art. 16</strong> — Para a realização de seus fins a JUBAF terá um conselho de coordenação, que será composto por:</p>
<ul>
<li>a) Os membros integrantes da diretoria da JUBAF;</li>
<li>b) Doze membros efetivos, eleitos pela Assembleia Ordinária, realizada concomitantemente com a Assembleia da ASBAF, com mandato de três anos, renovados anualmente pelo terço;</li>
<li>c) Pelo representante de cada UNIJOVEM das Igrejas arroladas à ASBAF.</li>
</ul>

<p><strong>Art. 17</strong> — O conselho de coordenação terá quatro suplentes eleitos pela Assembleia Ordinária, realizada concomitantemente com a Assembleia da ASBAF, com mandato de um ano, que serão convocados para servir na ordem de sua indicação, quando houver necessidade de substituir membros efetivos.</p>

<p><strong>Art. 18</strong> — A diretoria do Conselho de Coordenação será a mesma da JUBAF.</p>

<p><strong>Art. 19</strong> — São condições para pertencer ao Conselho de Coordenação da JUBAF:</p>
<ul>
<li>a) Não ser funcionário da JUBAF, nem dela receber remuneração;</li>
<li>b) Não ser parente em 1º grau de quaisquer funcionário da JUBAF;</li>
<li>c) Ser preferencialmente líder do trabalho com jovens de sua Igreja.</li>
</ul>
<p><em>Parágrafo Único</em> — Todo aquele que deixar de ser membro de uma Igreja Batista que coopere com a ASBAF, perderá o seu mandato para o qual foi eleito, bem assim aqueles que faltarem a três reuniões consecutivas sem prévia justificação.</p>

<p><strong>Art. 20</strong> — O terço eleito será empossado na primeira reunião do Conselho de Coordenação, após a Assembleia que o elegeu.</p>

<p><strong>Art. 21</strong> — Compete ao presidente do Conselho de Coordenação, além do disposto em Regimento próprio:</p>
<ul>
<li>a) Convocar reuniões do Conselho de Coordenação;</li>
<li>b) Observar e cumprir o presente Estatuto e os Regimentos;</li>
<li>c) Assinar as atas das reuniões após as suas aprovações.</li>
</ul>

<p><strong>Art. 22</strong> — O Conselho de Coordenação fará quatro reuniões ordinárias e tantas extraordinárias quantas forem necessárias.</p>
<p><em>Parágrafo Único</em> — O quorum mínimo para as reuniões ordinárias será de metade mais um e para as reuniões extraordinárias de um terço dos membros a que se refere o artigo 16 alíneas a e b.</p>

<p><strong>Art. 23</strong> — Pelo exercício do cargo, nenhum membro da diretoria da JUBAF, ou de seu Conselho de Coordenação, receberá remuneração ou participação na receita a qualquer título, a não ser, reembolso por despesas a serviço da JUBAF.</p>

<p><strong>Art. 24</strong> — Os membros do Conselho de Coordenação não respondem nem mesmo subsidiariamente, pelas obrigações da JUBAF, bem como, do próprio Conselho de Coordenação.</p>

<p><strong>Art. 25</strong> — Para executar as decisões e promover seus objetivos, o Conselho de Coordenação elegerá, por tempo indeterminado, e enquanto bem servir, a seu juízo, um Secretário Geral que será seu oficial executivo.</p>

<p><strong>Art. 26</strong> — Compete ao Secretário Geral, além de outras atribuições inerentes ao cargo:</p>
<ul>
<li>a) Representar a JUBAF e seu Conselho de Coordenação perante as igrejas do campo e órgãos denominacionais;</li>
<li>b) Executar as resoluções do Conselho de Coordenação;</li>
<li>c) Manter em ordem o arquivo de documentos e toda a escrituração;</li>
<li>d) Preparar os relatórios anuais a serem apresentados à Assembleia Ordinária.</li>
</ul>

<h2>CAPÍTULO V – DA RECEITA E DO PATRIMÔNIO</h2>

<p><strong>Art. 27</strong> — A receita da JUBAF será constituída de:</p>
<ul>
<li>a) Verbas destinadas pela ASBAF;</li>
<li>b) Ofertas regulares e especiais de instituições, igrejas ou indivíduos, compatíveis com os princípios adotados pela ASBAF.</li>
</ul>
<ol>
<li>A execução do orçamento da JUBAF caberá ao Conselho de Coordenação, respeitadas as recomendações de sua Assembleia.</li>
<li>A fiscalização da execução orçamentária compete a uma comissão de Exame de Contas nomeada pela Assembleia, dentre pessoas devidamente qualificadas, constituída de três membros que emitirá parecer perante a Assembleia.</li>
</ol>

<p><strong>Art. 28</strong> — O patrimônio da JUBAF será constituído de bens móveis, imóveis, e semoventes, doações e legados, registrados em seu nome, devendo ser utilizados na consecução de seus fins estatutários.</p>
<p><em>Parágrafo Único</em> — Qualquer ato que importe em alienação ou oneração de bens imóveis da JUBAF, dependerá de autorização prévia de sua Assembleia.</p>

<h2>CAPÍTULO VI – DAS DISPOSIÇÕES GERAIS</h2>

<p><strong>Art. 29</strong> — Para dissolução da JUBAF é necessário que na Assembleia em que for votada, conste do programa: Dissolução da JUBAF, e votem para este fim pelo menos 4/5 (quatro quintos) dos congressistas inscritos para o CONJUBAF, destinando-se neste caso, o patrimônio da JUBAF à ASBAF, ressalvados os direitos de terceiros.</p>
<p><em>Parágrafo Único</em> — Este assunto só poderá ser tratado na Assembleia Ordinária por ocasião do CONJUBAF e dependerá da homologação da ASBAF para se efetivar.</p>

<p><strong>Art. 30</strong> — A JUBAF será representada ativa, passiva, judicial e extrajudicialmente pelo presidente e no impedimento deste pelo seu substituto legal.</p>

<p><strong>Art. 31</strong> — Este Estatuto entrará em vigor, para fins operacionais, após sua aprovação pela Assembleia da ASBAF, e para efeitos legais, após a sua publicação e registro no órgão competente.</p>

<p><strong>Art. 32</strong> — A reforma deste Estatuto só poderá ser feita em Assembleia que conste do seu programa o item “Reforma de Estatuto”, devendo haver parecer favorável do Conselho de Coordenação, e votação favorável de dois terços dos presentes à Assembleia.</p>

<hr class="my-8 border-slate-200 dark:border-slate-600" />

<p class="text-sm text-slate-500 dark:text-slate-400"><em>Fonte de redação: Estatuto da JUBAF (Capítulos I a VI). Texto reproduzido para consulta da comunidade no blog institucional.</em></p>
HTML;
    }
}
