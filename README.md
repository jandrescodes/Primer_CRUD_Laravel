<div align="center">

# Primer CRUD Laravel

![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.2-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/Licencia-MIT-green?style=for-the-badge)
![Tests](https://img.shields.io/badge/Tests-17%20passing-brightgreen?style=for-the-badge&logo=phpunit)

Aplicación de práctica desarrollada con **Laravel 10** para gestionar personas mediante operaciones CRUD completas, con papelera de reciclaje, validaciones, exportación de reportes y suite de tests.

</div>

---

## Objetivo del proyecto

Este repositorio está orientado a aprendizaje y práctica de:

- Arquitectura MVC en Laravel.
- Rutas y controladores para flujos CRUD.
- Form Requests para validación del lado servidor.
- Soft Deletes y papelera de reciclaje.
- Vistas Blade reutilizables con layout compartido.
- Integración de componentes frontend (Bootstrap, DataTables, SweetAlert2).
- Feature Tests con PHPUnit y base de datos en memoria (SQLite).

## Tecnologías utilizadas

| Componente        | Versión / Uso                                   |
| ----------------- | ----------------------------------------------- |
| PHP               | ^8.1                                            |
| Laravel           | ^10.10                                          |
| Base de datos     | MySQL/MariaDB (vía migraciones de Laravel)      |
| Frontend          | Blade + Bootstrap 5.2                           |
| Tabla interactiva | DataTables 1.13 + Buttons 2.3 (CSV, Excel, PDF) |
| Alertas           | SweetAlert2                                     |
| Bundler de assets | Vite                                            |
| Tests             | PHPUnit + SQLite en memoria                     |

## Estructura funcional

- **Home principal:** `/` — estadísticas en tiempo real y acciones rápidas
- **Módulo People (CRUD):** prefijo `/people`

| Método | Ruta                        | Descripción                     |
| ------ | --------------------------- | ------------------------------- |
| GET    | `/people`                   | Listado de personas activas     |
| GET    | `/people/create`            | Formulario de creación          |
| POST   | `/people/store`             | Guardar nueva persona           |
| GET    | `/people/edit/{id}`         | Formulario de edición           |
| PUT    | `/people/update/{id}`       | Actualizar persona              |
| GET    | `/people/destroy/{id}`      | Mover a papelera (soft delete)  |
| GET    | `/people/trash`             | Listado de registros eliminados |
| GET    | `/people/restore/{id}`      | Restaurar desde papelera        |
| GET    | `/people/force-delete/{id}` | Eliminar permanentemente        |

## Modelo y datos

La entidad principal es `personas`, con los campos:

| Campo              | Tipo      | Descripción            |
| ------------------ | --------- | ---------------------- |
| `nombre`           | string    | Nombre(s)              |
| `paterno`          | string    | Apellido paterno       |
| `materno`          | string    | Apellido materno       |
| `fecha_nacimiento` | date      | Fecha de nacimiento    |
| `deleted_at`       | timestamp | Soft delete (papelera) |

## Validaciones

Gestionadas con Form Requests (`StorePersonaRequest` / `UpdatePersonaRequest`):

- Todos los campos son requeridos.
- Campos de texto con máximo 100 caracteres.
- `fecha_nacimiento` debe ser una fecha válida y anterior a hoy.
- Los formularios muestran errores inline con Bootstrap y conservan los valores ingresados.

## Instalación y ejecución local

1. Clonar el repositorio:
    ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd Primer_CRUD_Laravel
    ```
2. Instalar dependencias:
    ```bash
    composer install
    npm install
    ```
3. Configurar entorno:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4. Configurar conexión a base de datos en `.env`.
5. Ejecutar migraciones:
    ```bash
    php artisan migrate
    ```
6. Poblar datos de ejemplo (opcional):
    ```bash
    php artisan db:seed
    ```
7. Levantar la aplicación:
    ```bash
    php artisan serve
    npm run dev
    ```

## Pruebas

Los tests usan SQLite en memoria y no afectan la base de datos real.

```bash
php artisan test
```

Cobertura actual — **17 tests, 50 assertions**:

| Área           | Casos cubiertos                                                  |
| -------------- | ---------------------------------------------------------------- |
| Index / Create | Respuesta 200, visualización de datos                            |
| Store          | Guardado exitoso, campos requeridos, max 100 chars, fecha futura |
| Edit / Update  | Formulario con datos, actualización, validaciones                |
| Soft Delete    | Mover a papelera, no aparece en index                            |
| Trash          | Listado de eliminados                                            |
| Restore        | Recuperación desde papelera                                      |
| Force Delete   | Eliminación permanente                                           |

---

<div align="center">

## Licencia

Este proyecto se distribuye bajo la licencia **MIT**.

</div>
