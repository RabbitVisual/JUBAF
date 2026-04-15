<?php

namespace App\Helpers;

/**
 * Helper para tradução de termos do sistema para Português Brasileiro
 * Usado principalmente para traduzir ações de auditoria e status
 */
class TranslationHelper
{
    /**
     * Traduz ações de auditoria para português
     */
    public static function translateAction(?string $action): string
    {
        if (empty($action)) {
            return 'Atualização';
        }

        $translations = [
            // Ações básicas
            'created' => 'Criado',
            'updated' => 'Atualizado',
            'deleted' => 'Excluído',
            'restored' => 'Restaurado',
            
            // Ações de workflow
            'started' => 'Iniciado',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            'pending' => 'Pendente',
            'paused' => 'Pausado',
            'resumed' => 'Retomado',
            
            // Ações compostas
            'status_changed' => 'Status Alterado',
            'status_updated' => 'Status Atualizado',
            'assigned' => 'Atribuído',
            'unassigned' => 'Desatribuído',
            'transferred' => 'Transferido',
            'archived' => 'Arquivado',
            'unarchived' => 'Desarquivado',
            
            // Ações de documento
            'uploaded' => 'Enviado',
            'downloaded' => 'Baixado',
            'printed' => 'Impresso',
            'exported' => 'Exportado',
            'imported' => 'Importado',
            
            // Ações de usuário
            'logged_in' => 'Login Realizado',
            'logged_out' => 'Logout Realizado',
            'password_changed' => 'Senha Alterada',
            'profile_updated' => 'Perfil Atualizado',
        ];

        $key = strtolower($action);
        
        if (isset($translations[$key])) {
            return $translations[$key];
        }

        // Tentar traduzir ações compostas com underscore
        return ucfirst(str_replace('_', ' ', $action));
    }

    /**
     * Traduz status para português
     */
    public static function translateStatus(?string $status): string
    {
        if (empty($status)) {
            return 'Desconhecido';
        }

        $translations = [
            // Status gerais
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'enabled' => 'Habilitado',
            'disabled' => 'Desabilitado',
            
            // Status de demanda/ordem
            'pendente' => 'Pendente',
            'pending' => 'Pendente',
            'em_execucao' => 'Em Execução',
            'in_progress' => 'Em Andamento',
            'em_andamento' => 'Em Andamento',
            'concluida' => 'Concluída',
            'concluido' => 'Concluído',
            'completed' => 'Concluído',
            'cancelada' => 'Cancelada',
            'cancelado' => 'Cancelado',
            'cancelled' => 'Cancelado',
            'aberta' => 'Aberta',
            'open' => 'Aberta',
            'fechada' => 'Fechada',
            'closed' => 'Fechada',
            
            // Status de aprovação
            'approved' => 'Aprovado',
            'aprovado' => 'Aprovado',
            'rejected' => 'Rejeitado',
            'rejeitado' => 'Rejeitado',
            'waiting' => 'Aguardando',
            'aguardando' => 'Aguardando',
            
            // Status de prioridade
            'urgente' => 'Urgente',
            'urgent' => 'Urgente',
            'alta' => 'Alta',
            'high' => 'Alta',
            'media' => 'Média',
            'medium' => 'Média',
            'baixa' => 'Baixa',
            'low' => 'Baixa',
            
            // Status de documento
            'draft' => 'Rascunho',
            'published' => 'Publicado',
            'archived' => 'Arquivado',
        ];

        $key = strtolower($status);
        
        if (isset($translations[$key])) {
            return $translations[$key];
        }

        // Tentar traduzir status compostos com underscore
        return ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Traduz tipos para português
     */
    public static function translateType(?string $type): string
    {
        if (empty($type)) {
            return 'Desconhecido';
        }

        $translations = [
            // Tipos de demanda
            'luz' => 'Iluminação',
            'agua' => 'Água',
            'estrada' => 'Estrada',
            'poco' => 'Poço',
            'outros' => 'Outros',
            
            // Tipos de notificação
            'info' => 'Informação',
            'warning' => 'Aviso',
            'error' => 'Erro',
            'success' => 'Sucesso',
            'alert' => 'Alerta',
            
            // Tipos de documento
            'document' => 'Documento',
            'image' => 'Imagem',
            'video' => 'Vídeo',
            'audio' => 'Áudio',
            'pdf' => 'PDF',
            
            // Tipos de usuário
            'admin' => 'Administrador',
            'user' => 'Usuário',
            'jovens' => 'Jovem JUBAF',
            'lider' => 'Líder',
        ];

        $key = strtolower($type);
        
        if (isset($translations[$key])) {
            return $translations[$key];
        }

        return ucfirst(str_replace('_', ' ', $type));
    }

    /**
     * Traduz mensagens de erro comuns
     */
    public static function translateError(?string $error): string
    {
        if (empty($error)) {
            return 'Erro desconhecido';
        }

        $translations = [
            'not found' => 'Não encontrado',
            'not authorized' => 'Não autorizado',
            'unauthorized' => 'Não autorizado',
            'forbidden' => 'Acesso negado',
            'internal server error' => 'Erro interno do servidor',
            'bad request' => 'Requisição inválida',
            'validation error' => 'Erro de validação',
            'connection error' => 'Erro de conexão',
            'timeout' => 'Tempo esgotado',
            'network error' => 'Erro de rede',
            'file not found' => 'Arquivo não encontrado',
            'permission denied' => 'Permissão negada',
            'invalid data' => 'Dados inválidos',
            'duplicate entry' => 'Registro duplicado',
            'required field' => 'Campo obrigatório',
        ];

        $key = strtolower($error);
        
        foreach ($translations as $en => $pt) {
            if (strpos($key, $en) !== false) {
                return $pt;
            }
        }

        return $error;
    }

    /**
     * Traduz labels/rótulos comuns
     */
    public static function translateLabel(?string $label): string
    {
        if (empty($label)) {
            return '';
        }

        $translations = [
            // Labels comuns
            'name' => 'Nome',
            'email' => 'E-mail',
            'phone' => 'Telefone',
            'address' => 'Endereço',
            'status' => 'Status',
            'type' => 'Tipo',
            'description' => 'Descrição',
            'date' => 'Data',
            'time' => 'Hora',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
            'deleted_at' => 'Excluído em',
            'created' => 'Criado',
            'updated' => 'Atualizado',
            'deleted' => 'Excluído',
            
            // Labels de formulário
            'title' => 'Título',
            'content' => 'Conteúdo',
            'message' => 'Mensagem',
            'subject' => 'Assunto',
            'priority' => 'Prioridade',
            'category' => 'Categoria',
            'tags' => 'Tags',
            'notes' => 'Observações',
            'comments' => 'Comentários',
            
            // Labels de sistema
            'user' => 'Usuário',
            'role' => 'Função',
            'permission' => 'Permissão',
            'setting' => 'Configuração',
            'module' => 'Módulo',
            'action' => 'Ação',
            'result' => 'Resultado',
            
            // Labels de arquivo
            'file' => 'Arquivo',
            'folder' => 'Pasta',
            'image' => 'Imagem',
            'document' => 'Documento',
            'attachment' => 'Anexo',
        ];

        $key = strtolower($label);
        
        if (isset($translations[$key])) {
            return $translations[$key];
        }

        // Converte snake_case para título
        return ucfirst(str_replace('_', ' ', $label));
    }

    /**
     * Traduz botões/ações da interface
     */
    public static function translateButton(?string $button): string
    {
        if (empty($button)) {
            return '';
        }

        $translations = [
            // Botões básicos
            'save' => 'Salvar',
            'cancel' => 'Cancelar',
            'close' => 'Fechar',
            'delete' => 'Excluir',
            'edit' => 'Editar',
            'create' => 'Criar',
            'add' => 'Adicionar',
            'remove' => 'Remover',
            'update' => 'Atualizar',
            'submit' => 'Enviar',
            'confirm' => 'Confirmar',
            'back' => 'Voltar',
            'next' => 'Próximo',
            'previous' => 'Anterior',
            'finish' => 'Finalizar',
            'start' => 'Iniciar',
            'stop' => 'Parar',
            'pause' => 'Pausar',
            'resume' => 'Retomar',
            
            // Botões de busca/filtro
            'search' => 'Buscar',
            'filter' => 'Filtrar',
            'clear' => 'Limpar',
            'reset' => 'Redefinir',
            'apply' => 'Aplicar',
            
            // Botões de arquivo
            'upload' => 'Enviar',
            'download' => 'Baixar',
            'export' => 'Exportar',
            'import' => 'Importar',
            'print' => 'Imprimir',
            
            // Botões de visualização
            'view' => 'Visualizar',
            'preview' => 'Pré-visualizar',
            'show' => 'Exibir',
            'hide' => 'Ocultar',
            'expand' => 'Expandir',
            'collapse' => 'Recolher',
            
            // Botões de estado
            'approve' => 'Aprovar',
            'reject' => 'Rejeitar',
            'archive' => 'Arquivar',
            'restore' => 'Restaurar',
            'activate' => 'Ativar',
            'deactivate' => 'Desativar',
            
            // Botões de seleção
            'select' => 'Selecionar',
            'select_all' => 'Selecionar Tudo',
            'deselect' => 'Desmarcar',
            'deselect_all' => 'Desmarcar Tudo',
            
            // Botões de login
            'login' => 'Entrar',
            'logout' => 'Sair',
            'register' => 'Cadastrar',
            'sign_in' => 'Entrar',
            'sign_out' => 'Sair',
            'sign_up' => 'Cadastrar',
            
            // Ações compostas
            'loading' => 'Carregando',
            'processing' => 'Processando',
            'saving' => 'Salvando',
            'deleting' => 'Excluindo',
            'updating' => 'Atualizando',
        ];

        $key = strtolower($button);
        
        if (isset($translations[$key])) {
            return $translations[$key];
        }

        return ucfirst(str_replace('_', ' ', $button));
    }
}

