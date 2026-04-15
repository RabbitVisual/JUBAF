<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O campo :attribute deve ser aceito.',
    'active_url' => 'O campo :attribute não é uma URL válida.',
    'after' => 'O campo :attribute deve ser uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute deve ser uma data posterior ou igual a :date.',
    'alpha' => 'O campo :attribute só pode conter letras.',
    'alpha_dash' => 'O campo :attribute só pode conter letras, números, traços e sublinhados.',
    'alpha_num' => 'O campo :attribute só pode conter letras e números.',
    'array' => 'O campo :attribute deve ser uma matriz.',
    'before' => 'O campo :attribute deve ser uma data anterior a :date.',
    'before_or_equal' => 'O campo :attribute deve ser uma data anterior ou igual a :date.',
    'between' => [
        'numeric' => 'O campo :attribute deve estar entre :min e :max.',
        'file' => 'O campo :attribute deve ter entre :min e :max kilobytes.',
        'string' => 'O campo :attribute deve ter entre :min e :max caracteres.',
        'array' => 'O campo :attribute deve ter entre :min e :max itens.',
    ],
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação do campo :attribute não corresponde.',
    'date' => 'O campo :attribute não é uma data válida.',
    'date_equals' => 'O campo :attribute deve ser uma data igual a :date.',
    'date_format' => 'O campo :attribute não corresponde ao formato :format.',
    'different' => 'Os campos :attribute e :other devem ser diferentes.',
    'digits' => 'O campo :attribute deve ter :digits dígitos.',
    'digits_between' => 'O campo :attribute deve ter entre :min e :max dígitos.',
    'dimensions' => 'O campo :attribute tem dimensões de imagem inválidas.',
    'distinct' => 'O campo :attribute tem um valor duplicado.',
    'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'ends_with' => 'O campo :attribute deve terminar com um dos seguintes valores: :values.',
    'exists' => 'O campo :attribute selecionado é inválido.',
    'file' => 'O campo :attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute deve ter um valor.',
    'gt' => [
        'numeric' => 'O campo :attribute deve ser maior que :value.',
        'file' => 'O campo :attribute deve ter mais de :value kilobytes.',
        'string' => 'O campo :attribute deve ter mais de :value caracteres.',
        'array' => 'O campo :attribute deve ter mais de :value itens.',
    ],
    'gte' => [
        'numeric' => 'O campo :attribute deve ser maior ou igual a :value.',
        'file' => 'O campo :attribute deve ter :value ou mais kilobytes.',
        'string' => 'O campo :attribute deve ter :value ou mais caracteres.',
        'array' => 'O campo :attribute deve ter :value ou mais itens.',
    ],
    'image' => 'O campo :attribute deve ser uma imagem.',
    'in' => 'O campo :attribute selecionado é inválido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'ip' => 'O campo :attribute deve ser um endereço IP válido.',
    'ipv4' => 'O campo :attribute deve ser um endereço IPv4 válido.',
    'ipv6' => 'O campo :attribute deve ser um endereço IPv6 válido.',
    'json' => 'O campo :attribute deve ser uma string JSON válida.',
    'lt' => [
        'numeric' => 'O campo :attribute deve ser menor que :value.',
        'file' => 'O campo :attribute deve ter menos de :value kilobytes.',
        'string' => 'O campo :attribute deve ter menos de :value caracteres.',
        'array' => 'O campo :attribute deve ter menos de :value itens.',
    ],
    'lte' => [
        'numeric' => 'O campo :attribute deve ser menor ou igual a :value.',
        'file' => 'O campo :attribute deve ter :value ou menos kilobytes.',
        'string' => 'O campo :attribute deve ter :value ou menos caracteres.',
        'array' => 'O campo :attribute deve ter :value ou menos itens.',
    ],
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file' => 'O campo :attribute não pode ter mais de :max kilobytes.',
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
        'array' => 'O campo :attribute não pode ter mais de :max itens.',
    ],
    'mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => 'O campo :attribute deve ser pelo menos :min.',
        'file' => 'O campo :attribute deve ter pelo menos :min kilobytes.',
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
        'array' => 'O campo :attribute deve ter pelo menos :min itens.',
    ],
    'not_in' => 'O campo :attribute selecionado é inválido.',
    'not_regex' => 'O formato do campo :attribute é inválido.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'password' => 'A senha está incorreta.',
    'present' => 'O campo :attribute deve estar presente.',
    'regex' => 'O formato do campo :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O campo :attribute é obrigatório, a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum dos :values estão presentes.',
    'same' => 'Os campos :attribute e :other devem corresponder.',
    'size' => [
        'numeric' => 'O campo :attribute deve ser :size.',
        'file' => 'O campo :attribute deve ter :size kilobytes.',
        'string' => 'O campo :attribute deve ter :size caracteres.',
        'array' => 'O campo :attribute deve conter :size itens.',
    ],
    'starts_with' => 'O campo :attribute deve começar com um dos seguintes valores: :values.',
    'string' => 'O campo :attribute deve ser uma string.',
    'timezone' => 'O campo :attribute deve ser um fuso horário válido.',
    'unique' => 'O campo :attribute já foi utilizado.',
    'uploaded' => 'Falhou ao fazer upload do campo :attribute.',
    'url' => 'O formato do campo :attribute é inválido.',
    'uuid' => 'O campo :attribute deve ser um UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute". This makes it quick to specify a specific
    | custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // Campos do módulo Pessoas
        'nom_pessoa' => 'nome',
        'nom_apelido_pessoa' => 'apelido',
        'num_nis_pessoa_atual' => 'NIS',
        'num_cpf_pessoa' => 'CPF',
        'cod_sexo_pessoa' => 'sexo',
        'dta_nasc_pessoa' => 'data de nascimento',
        'nom_completo_mae_pessoa' => 'nome da mãe',
        'nom_completo_pai_pessoa' => 'nome do pai',
        'localidade_id' => 'localidade',
        'ativo' => 'status',

        // Campos gerais
        'name' => 'nome',
        'email' => 'e-mail',
        'password' => 'senha',
        'password_confirmation' => 'confirmação de senha',
        'phone' => 'telefone',
        'address' => 'endereço',
        'status' => 'status',
        'type' => 'tipo',
        'description' => 'descrição',
        'date' => 'data',
        'time' => 'hora',
        'title' => 'título',
        'content' => 'conteúdo',
        'message' => 'mensagem',
        'subject' => 'assunto',
        'priority' => 'prioridade',
        'category' => 'categoria',
        'tags' => 'tags',
        'notes' => 'observações',
        'comments' => 'comentários',
    ],

];
