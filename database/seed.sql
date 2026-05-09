INSERT INTO document_status (id, description)
VALUES
    (1, 'Criado'),
    (2, 'Pendente de Assinatura'),
    (3, 'Parcialmente Assinado'),
    (4, 'Assinado'),
    (5, 'Recusado'),
    (6, 'Cancelado'),
    (7, 'Expirado'),
    (8, 'Erro');

INSERT INTO signature_types (id, description)
VALUES
    (1, 'Certificado Digital'),
    (2, 'Assinatura Eletronica');