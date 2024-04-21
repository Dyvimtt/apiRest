-- Datos ficticios para la tabla suppliers
INSERT INTO suppliers (name, CIF, phone, email) VALUES
('Proveedor A', 'A12345678', 123456789, 'proveedorA@example.com'),
('Proveedor B', 'B87654321', 987654321, 'proveedorB@example.com'),
('Proveedor C', 'C98765432', 987654322, 'proveedorC@example.com'),
('Proveedor D', 'D23456789', 234567890, 'proveedorD@example.com'),
('Proveedor E', 'E54321987', 543219876, 'proveedorE@example.com'),
('Proveedor F', 'F34567891', 345678912, 'proveedorF@example.com'),
('Proveedor G', 'G65432198', 654321987, 'proveedorG@example.com'),
('Proveedor H', 'H45678912', 456789123, 'proveedorH@example.com'),
('Proveedor I', 'I56789123', 567891234, 'proveedorI@example.com'),
('Proveedor J', 'J67891234', 678912345, 'proveedorJ@example.com');

-- Datos ficticios para la tabla projects
INSERT INTO projects (name, city, budget, start_date, end_date) VALUES
('Proyecto 1', 'Madrid', 10000, '2024-01-01', '2024-06-30'),
('Proyecto 2', 'Barcelona', 15000, '2024-02-15', '2024-08-31'),
('Proyecto 3', 'Valencia', 20000, '2024-03-10', '2024-09-15'),
('Proyecto 4', 'Sevilla', 12000, '2024-04-20', '2024-10-31'),
('Proyecto 5', 'Bilbao', 18000, '2024-05-05', '2024-11-30'),
('Proyecto 6', 'Málaga', 13000, '2024-06-15', '2024-12-31'),
('Proyecto 7', 'Alicante', 16000, '2024-07-20', '2025-01-31'),
('Proyecto 8', 'Zaragoza', 14000, '2024-08-10', '2025-02-28'),
('Proyecto 9', 'Murcia', 17000, '2024-09-05', '2025-03-31'),
('Proyecto 10', 'Palma de Mallorca', 11000, '2024-10-15', '2025-04-30');

-- Datos ficticios para la tabla employees
INSERT INTO employees (first_name, second_name, DNI, email_employee, phone, password_employee, role, hire_date, uploaded_by, token_user, token_exp_user) VALUES
('Juan', 'González', '12345678A', 'juang@example.com', 612345678, '123456', 'admin', '2020-01-01', 'admin', 'token123', 'exp123'),
('María', 'Martínez', '23456789B', 'mariam@example.com', 623456789, '234567', 'manager', '2020-02-01', 'manager', 'token234', 'exp234'),
('Antonio', 'Sánchez', '34567891C', 'antonios@example.com', 634567890, '345678', 'employee', '2020-03-01', 'employee', 'token345', 'exp345'),
('Carmen', 'López', '45678912D', 'carmenl@example.com', 645678901, '456789', 'employee', '2020-04-01', 'employee', 'token456', 'exp456'),
('David', 'García', '56789123E', 'davidg@example.com', 656789012, '567890', 'employee', '2020-05-01', 'employee', 'token567', 'exp567'),
('Laura', 'Fernández', '67891234F', 'lauraf@example.com', 667890123, '678901', 'employee', '2020-06-01', 'employee', 'token678', 'exp678'),
('Javier', 'Martín', '78912345G', 'javierm@example.com', 678901234, '789012', 'employee', '2020-07-01', 'employee', 'token789', 'exp789'),
('Ana', 'Jiménez', '89123456H', 'anaj@example.com', 689012345, '890123', 'employee', '2020-08-01', 'employee', 'token890', 'exp890'),
('Sara', 'Pérez', '91234567I', 'sarap@example.com', 690123456, '901234', 'employee', '2020-09-01', 'employee', 'token901', 'exp901'),
('Pedro', 'Ruiz', '12345678J', 'pedror@example.com', 601234567, '012345', 'employee', '2020-10-01', 'employee', 'token012', 'exp012');

-- Datos ficticios para la tabla employee_projects
INSERT INTO employee_projects (id_employee, id_project, employee_role) VALUES
(1, 1, 'Encargado'),
(2, 2, 'Oficial'),
(3, 3, 'Tecnico'),
(4, 4, 'Tecnico'),
(5, 5, 'Oficial'),
(6, 6, 'Oficial'),
(7, 7, 'Encargado'),
(8, 8, 'Tecnico'),
(9, 9, 'Oficial'),
(10, 10, 'Tecnico');