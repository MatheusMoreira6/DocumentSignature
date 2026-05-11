<?php

namespace App\Controllers;

use App\Core\Certising;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\DocumentModel;
use App\Models\SignatureModel;
use App\Models\SignatureTypeModel;
use App\Models\UserModel;
use App\Services\CertisingService;
use App\Services\UserService;
use Exception;

class DocumentsController extends Controller
{
    private UserModel $userModel;
    private DocumentModel $documentModel;
    private SignatureModel $signatureModel;
    private SignatureTypeModel $signatureTypeModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->documentModel = new DocumentModel();
        $this->signatureModel = new SignatureModel();
        $this->signatureTypeModel = new SignatureTypeModel();
    }

    public function index()
    {
        $signature_types = $this->signatureTypeModel->all();

        $this->layout("documents/index", compact("signature_types"), true);
    }

    public function datatable(Request $request): void
    {
        $user_id = Session::userId();
        $draw = $request->int('draw') ?? 1;
        $start = $request->int('start');
        $length = $request->int('length');
        $orders = $request->array('order');
        $columns = $request->array('columns');
        $search = $request->array('search');

        $documents = $this->documentModel->datatable($user_id, $orders, $columns, $start, $length, $search['value']);
        $documents_total = $this->documentModel->datatable($user_id, $orders, $columns, null, null, $search['value']);

        $response = [
            'draw' => $draw,
            'recordsTotal' => count($documents_total),
            'recordsFiltered' => count($documents),
            'data' => $documents,
        ];

        $this->json($response);
    }

    public function show(Request $request): void
    {
        $document_id = $request->int("id");

        $token = null;

        try {
            $userService = new UserService();
            $token = $userService->getTokenByUserId(Session::userId());
        } catch (Exception $e) {
            error_log('Erro ao obter token de autenticação: ' . $e->getMessage());
            $this->json(['errors' => $e->getMessage()], 400);
        }

        $document = $this->documentModel->findDocumentById($document_id);

        try {
            $certising = new Certising();
            $file = $certising->downloadDocument($token, $document['certisign_document_id']);

            header('Content-Type: ' . $file['mimeType']);
            header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            header('Content-Length: ' . strlen($file['content']));

            echo $file['content'];
        } catch (Exception $e) {
            error_log('Erro ao baixar documento: ' . $e->getMessage());
            die('Erro ao baixar documento.');
        }
    }

    public function store(Request $request): void
    {
        $signers_document = $request->array('signers');
        $document = $_FILES['file'];

        $signers = array_filter($signers_document, function ($signer) {
            return $signer['type'] == 1;
        });

        $eletronic_signers = array_filter($signers_document, function ($signer) {
            return $signer['type'] == 2;
        });

        /**
         * Validação básica do arquivo
         */
        [$file_errors, $msg_error] = validate_file($document, [], (5 * 1024 * 1024));

        if ($file_errors === false) {
            $this->json(['errors' => $msg_error], 400);
        }

        /**
         * Token de autenticação do usuário
         */
        $user = $this->userModel->findUserById(Session::userId());
        $token = null;

        try {
            $userService = new UserService();
            $token = $userService->getTokenByUserId(Session::userId());
        } catch (Exception $e) {
            error_log('Erro ao obter token de autenticação: ' . $e->getMessage());
            $this->json(['errors' => $e->getMessage()], 400);
        }

        /**
         * Envio do documento para a Certisign
         */
        $certisign_document = null;

        try {
            $certisingService = new CertisingService();

            $certisign_document = $certisingService->createDocument(
                $token,
                $document['tmp_name'],
                $document['name'],
                $user['name'],
                $user['email'],
                $user['cpf'],
                $signers,
                $eletronic_signers
            );
        } catch (Exception $e) {
            error_log('Erro ao criar documento na Certisign: ' . $e->getMessage());
            $this->json(['errors' => 'Erro ao criar documento.'], 500);
        }

        /**
         * Armazenar informações do documento e signatários no banco de dados
         */
        $this->documentModel->beginTransaction();

        $db_document = [
            'user_id' => Session::userId(),
            'certisign_document_id' => $certisign_document['id'],
            'certisign_chave' => $certisign_document['chave'],
            'file_name' => $document['name'],
            'sign_url' => $certisign_document['signUrl'],
            'status_id' => 1,
        ];

        $document_id = $this->documentModel->create($db_document);

        if (!$document_id) {
            $this->documentModel->rollBack();
            $this->json(['errors' => 'Erro ao criar documento.'], 500);
        }

        foreach ($signers_document as $signer) {
            $cpf = isset($signer['cpf']) ? regex($signer['cpf'], '/\D/', '') : null;

            if (empty($cpf) || strlen($cpf) !== 11) {
                $this->documentModel->rollBack();
                $this->json(['errors' => 'CPF inválido.'], 400);
            }

            $db_signer = [
                'document_id' => $document_id,
                'name' => $signer['name'],
                'email' => $signer['email'],
                'cpf' => $cpf,
                'type_signature_id' => $signer['type'],
            ];

            $signer_id = $this->signatureModel->create($db_signer);

            if (!$signer_id) {
                $this->documentModel->rollBack();
                $this->json(['errors' => 'Erro ao criar signatário.'], 500);
            }
        }

        $this->documentModel->commit();

        $this->json(['message' => 'Documento enviado com sucesso!']);
    }

    public function destroy(Request $request): void
    {
        $document_id = $request->int("id");

        $status = true;

        $this->documentModel->beginTransaction();

        $status &= $this->signatureModel->destroy($document_id);
        $status &= $this->documentModel->destroy($document_id, Session::userId());

        if (!$status) {
            $this->documentModel->rollBack();
            $this->json(['errors' => 'Erro ao deletar documento.'], 500);
        }

        $this->documentModel->commit();

        $this->json(['message' => 'Documento deletado com sucesso!']);
    }
}
