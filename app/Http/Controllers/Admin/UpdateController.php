<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UpdateController extends Controller
{
    protected $updateService;

    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
    }

    /**
     * Exibir página de atualizações
     */
    public function index()
    {
        $updates = $this->updateService->getUpdateHistory();
        $lastUpdate = $this->updateService->getLastUpdate();
        $systemInfo = $this->updateService->getSystemInfo();

        return view('admin::updates.index', compact('updates', 'lastUpdate', 'systemInfo'));
    }

    /**
     * Exibir formulário de upload
     */
    public function create()
    {
        return view('admin::updates.create');
    }

    /**
     * Upload e processar atualização
     */
    public function upload(Request $request)
    {
        $request->validate([
            'update_file' => 'required|file|mimes:zip,application/zip,application/x-zip-compressed|max:102400', // Máximo 100MB
            'create_backup' => 'nullable|boolean',
            'auto_apply' => 'nullable|boolean',
        ]);

        try {
            if (!$request->hasFile('update_file')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Nenhum arquivo foi enviado.');
            }

            $file = $request->file('update_file');
            
            // Validar se o arquivo foi enviado corretamente
            if (!$file->isValid()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'O arquivo enviado é inválido: ' . $file->getErrorMessage());
            }

            // Processar opções do formulário corretamente
            // Checkbox não marcado = não enviado, marcado = "1"
            // Usar boolean() que trata "1", "true", "on", 1, true como true, e null/false como false
            $createBackup = $request->boolean('create_backup', true); // Default true se não enviado
            $autoApply = $request->boolean('auto_apply', false); // Default false se não enviado

            // Log das opções para debug
            Log::info('Opções de atualização recebidas', [
                'create_backup' => $createBackup,
                'auto_apply' => $autoApply,
                'create_backup_raw' => $request->input('create_backup'),
                'auto_apply_raw' => $request->input('auto_apply'),
            ]);

            // Upload do arquivo
            $uploadResult = $this->updateService->uploadUpdateFile($file, [
                'create_backup' => $createBackup,
                'auto_apply' => $autoApply,
            ]);

            if ($autoApply) {
                // Aplicar atualização automaticamente
                $applyResult = $this->updateService->applyUpdate($uploadResult['update_id']);

                if ($applyResult['success']) {
                    return redirect()->route('admin.updates.index')
                        ->with('success', 'Atualização aplicada com sucesso!');
                } else {
                    return redirect()->route('admin.updates.index')
                        ->with('error', 'Erro ao aplicar atualização: ' . $applyResult['message']);
                }
            } else {
                return redirect()->route('admin.updates.show', $uploadResult['update_id'])
                    ->with('success', 'Arquivo de atualização enviado com sucesso! Revise os detalhes antes de aplicar.');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao fazer upload de atualização: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao fazer upload: ' . $e->getMessage());
        }
    }

    /**
     * Exibir detalhes de uma atualização
     */
    public function show($id)
    {
        $update = $this->updateService->getUpdate($id);

        if (!$update) {
            return redirect()->route('admin.updates.index')
                ->with('error', 'Atualização não encontrada.');
        }

        $files = $this->updateService->getUpdateFiles($id);
        $backup = $this->updateService->getUpdateBackup($id);

        return view('admin::updates.show', compact('update', 'files', 'backup'));
    }

    /**
     * Aplicar atualização
     */
    public function apply($id)
    {
        try {
            $result = $this->updateService->applyUpdate($id);

            if ($result['success']) {
                return redirect()->route('admin.updates.index')
                    ->with('success', 'Atualização aplicada com sucesso!');
            } else {
                return redirect()->back()
                    ->with('error', 'Erro ao aplicar atualização: ' . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao aplicar atualização: ' . $e->getMessage(), [
                'update_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao aplicar atualização: ' . $e->getMessage());
        }
    }

    /**
     * Fazer rollback de uma atualização
     */
    public function rollback($id)
    {
        try {
            $result = $this->updateService->rollbackUpdate($id);

            if ($result['success']) {
                return redirect()->route('admin.updates.index')
                    ->with('success', 'Rollback realizado com sucesso!');
            } else {
                return redirect()->back()
                    ->with('error', 'Erro ao fazer rollback: ' . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao fazer rollback: ' . $e->getMessage(), [
                'update_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao fazer rollback: ' . $e->getMessage());
        }
    }

    /**
     * Deletar atualização
     */
    public function destroy($id)
    {
        try {
            $this->updateService->deleteUpdate($id);

            return redirect()->route('admin.updates.index')
                ->with('success', 'Atualização removida com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar atualização: ' . $e->getMessage(), [
                'update_id' => $id,
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao remover atualização: ' . $e->getMessage());
        }
    }

    /**
     * Download de backup
     */
    public function downloadBackup($id)
    {
        try {
            $backupPath = $this->updateService->getBackupPath($id);

            if (!$backupPath || !file_exists($backupPath)) {
                return redirect()->back()
                    ->with('error', 'Backup não encontrado.');
            }

            return response()->download($backupPath);
        } catch (\Exception $e) {
            Log::error('Erro ao baixar backup: ' . $e->getMessage(), [
                'update_id' => $id,
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao baixar backup: ' . $e->getMessage());
        }
    }
}

