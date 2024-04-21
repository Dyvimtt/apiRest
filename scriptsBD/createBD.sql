CREATE TABLE suppliers (
    id_supplier INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30),
    CIF VARCHAR(15) UNIQUE,
    phone INT,
    email VARCHAR(50)
);

CREATE TABLE projects (
    id_project INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
    city VARCHAR(30),
    budget INT,
    start_date DATE,
    end_date DATE
);

CREATE TABLE employees (
    id_employee INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(30),
    second_name VARCHAR(30),
    DNI VARCHAR(30) UNIQUE,
    email_employee VARCHAR(50) UNIQUE,
    phone INT,
    password_employee VARCHAR(50),
    role ENUM ("employee", "manager", "admin"),
    hire_date DATE,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    uploaded_by VARCHAR(30),
    token_user VARCHAR(100),
    token_exp_user VARCHAR(100)
);

CREATE TABLE employee_projects (
    id_employee INT,
    id_project INT,
    employee_role ENUM ("Encargado", "Oficial", "Tecnico"),
    PRIMARY KEY (id_employee, id_project),
    FOREIGN KEY (id_employee) REFERENCES employees(id_employee),
    FOREIGN KEY (id_project) REFERENCES projects(id_project)
);

CREATE TABLE supplier_projects (
    id_supplier INT,
    id_project INT,
    PRIMARY KEY (id_supplier, id_project),
    FOREIGN KEY (id_supplier) REFERENCES suppliers(id_supplier),
    FOREIGN KEY (id_project) REFERENCES projects(id_project)
);

CREATE TABLE documents (
    id_document INT AUTO_INCREMENT PRIMARY KEY,
    id_employee INT,
    id_supplier INT,
    id_project INT,
    type ENUM ("invoice", "document", "income"),
    document_name VARCHAR(50),
    due_date DATE,
    num_invoice INT UNIQUE,
    paid BOOLEAN,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    uploaded_by VARCHAR(30),
    attachment_url VARCHAR(100),
    FOREIGN KEY (id_employee) REFERENCES employees(id_employee),
    FOREIGN KEY (id_supplier) REFERENCES suppliers(id_supplier),
    FOREIGN KEY (id_project) REFERENCES projects(id_project)
);