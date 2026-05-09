CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password TEXT NOT NULL,
    token TEXT
);

CREATE TABLE document_status (
    id SMALLINT PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE documents (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    certisign_document_id TEXT NOT NULL,
    file_name TEXT NOT NULL,
    sign_url TEXT,
    status_id SMALLINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (status_id) REFERENCES document_status(id)
);

CREATE TABLE signature_types (
    id SMALLINT PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE signatures (
    id SERIAL PRIMARY KEY,
    document_id INTEGER NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    type_signature_id SMALLINT NOT NULL,
    status_id SMALLINT,
    step SMALLINT DEFAULT 1,
    certisign_signer_uid TEXT,
    signed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (document_id) REFERENCES documents(id),
    FOREIGN KEY (type_signature_id) REFERENCES signature_types(id),
    FOREIGN KEY (status_id) REFERENCES document_status(id)
);