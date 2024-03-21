## Información diseño BD


### Nombres de las tablas

1.  Users/Employees (los úniremos y le ponemos un flag por si en el futuro queremos darles acceso al resto de empleados)
2. Documents ( utilizaremos un campo para crear un link de ruta al archivo),
documento podrá tener clave ajena, podría ser empleado, proyecto,proveedor
empleados tiene relación con proyectos y documentacion, documentación con proveedor y proveedor con proyectos
Creamos en documentos una tabla con muchos campos que no sean obligatorios y con un select que decida el tipo de documentación que vamos a introducir. Y así después filtrar
3. Projects
4.  Supplies

#### Diagrama básico

![alt text](image.png)