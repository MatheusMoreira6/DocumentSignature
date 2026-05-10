<?php

namespace App\Controllers;

use App\Core\Certising;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\Crypt;
use App\Models\DocumentModel;
use App\Models\SignatureModel;
use App\Models\SignatureTypeModel;
use App\Models\UserModel;

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

        $user = $this->userModel->findUserById(Session::userId());
        $document = $this->documentModel->findDocumentById($document_id);

        $certising = new Certising();
        $file = $certising->downloadDocument(Crypt::decrypt($user['token']), $document['certisign_document_id']);

        header('Content-Type: ' . $file['mimeType']);
        header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
        header('Content-Length: ' . strlen($file['content']));

        echo $file['content'];
    }

    public function store(Request $request): void
    {
        $signers = $request->array('signers');
        $document = $_FILES['file'];

        if (empty($document) || $document['error'] !== UPLOAD_ERR_OK) {
            $this->json(['errors' => 'Arquivo inválido ou não enviado.'], 400);
        }

        $user = $this->userModel->findUserById(Session::userId());

        if (empty($user['token'])) {
            $this->json(['errors' => 'Token de autenticação não encontrado.'], 400);
        }

        $token = Crypt::decrypt($user['token']);

        if (!$token) {
            $this->json(['errors' => 'Token de autenticação inválido.'], 400);
        }

        // $certising = new Certising();
        // $upload_id = $certising->uploadDocument($token, $document['tmp_name'], $document['name']);

        $this->documentModel->beginTransaction();

        $db_document = [
            'user_id' => Session::userId(),
            'certisign_document_id' => bin2hex(random_bytes(16)),
            'file_name' => $document['name'],
            'status_id' => 1,
        ];

        $document_id = $this->documentModel->create($db_document);

        if (!$document_id) {
            $this->documentModel->rollBack();
            $this->json(['errors' => 'Erro ao criar documento.'], 500);
        }

        foreach ($signers as $signer) {
            $cpf = isset($signer['cpf']) ? preg_replace('/\D/', '', $signer['cpf']) : null;

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
