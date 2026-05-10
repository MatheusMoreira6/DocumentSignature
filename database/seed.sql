INSERT INTO document_status (id, description)
VALUES
    (1, 'Assinatura Pendente'),
    (2, 'Parcialmente Assinado'),
    (3, 'Assinado'),
    (4, 'Recusado'),
    (5, 'Cancelado'),
    (6, 'Expirado');

INSERT INTO signature_types (id, description)
VALUES
    (1, 'Certificado Digital'),
    (2, 'Assinatura Eletronica');