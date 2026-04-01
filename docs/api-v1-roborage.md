# API RoboRage — `v1/roborage`

Prefijo base (Laravel `routes/api.php`):

```text
{APP_URL}/api/v1/roborage
```

Ejemplo local: `http://127.0.0.1:8000/api/v1/roborage`.

---

## Formato general de respuesta JSON

Los controladores usan `Controller::success()`:

```json
{
  "success": true,
  "message": "Texto descriptivo",
  "data": null
}
```

- **`data`**: recurso, colección o `null` (p. ej. DELETE).
- **Colecciones Eloquent Resource**: Laravel suele serializar un objeto con clave interna `"data"` con el arreglo de ítems. Es decir, el listado puede quedar como `response.data.data` (array) según el cliente.

Errores de validación: **422** con el formato estándar de Laravel (`message`, `errors`).

---

## Categorías

**Request aplicable:** `CategoryRequest` (POST, PUT, PATCH con el mismo cuerpo: todos los campos requeridos en cada petición con body).

| Campo | Reglas |
|--------|--------|
| `name` | `required`, string, máx. 255 |
| `project` | `required`, integer |

### `GET /categories`

- **Método:** `GET`
- **Body:** no
- **Respuesta:** `200` — `message`: *Categorias obtenidas exitosamente*; `data`: colección de categorías (sin `subcategories` cargadas en listado).

### `POST /categories`

- **Método:** `POST`
- **Headers:** `Content-Type: application/json`, `Accept: application/json`
- **Body (JSON):** ver tabla `CategoryRequest`
- **Respuesta:** `201` — `message`: *Categoria creada exitosamente*; `data`: un objeto categoría.

### `GET /categories/{id}`

- **Método:** `GET`
- **Body:** no
- **Respuesta:** `200` — `message`: *Categoria obtenida exitosamente*; `data`: categoría con `subcategories` embebidas (cuando aplica).

### `PUT|PATCH /categories/{id}`

- **Método:** `PUT` o `PATCH`
- **Body (JSON):** mismo esquema que `CategoryRequest` (campos requeridos).
- **Respuesta:** `200` — `message`: *Categoria actualizada exitosamente*; `data`: categoría.

### `DELETE /categories/{id}`

- **Método:** `DELETE`
- **Respuesta:** `200` — `message`: *Categoria eliminada exitosamente*; `data`: `null`

---

## Subcategorías

**Request aplicable:** `SubcategoryRequest` (POST, PUT, PATCH: mismos campos requeridos).

| Campo | Reglas |
|--------|--------|
| `name` | `required`, string, máx. 255 |
| `project` | `required`, integer |
| `price` | `required`, numeric, mín. 0 |
| `category_id` | `required`, debe existir en `categories.id` |

### `GET /subcategories`

- **Método:** `GET`
- **Respuesta:** `200` — `message`: *Subcategorias obtenidas exitosamente*; `data`: colección de subcategorías (sin `category` cargada en listado).

### `POST /subcategories`

- **Método:** `POST`
- **Body (JSON):** ver tabla `SubcategoryRequest`
- **Respuesta:** `201` — `message`: *Subcategoria creada exitosamente*; `data`: subcategoría.

### `GET /subcategories/{id}`

- **Método:** `GET`
- **Respuesta:** `200` — `message`: *Subcategoria obtenida exitosamente*; `data`: subcategoría con `category` embebida.

### `PUT|PATCH /subcategories/{id}`

- **Método:** `PUT` o `PATCH`
- **Body (JSON):** `SubcategoryRequest`
- **Respuesta:** `200` — `message`: *Subcategoria actualizada exitosamente*; `data`: subcategoría.

### `DELETE /subcategories/{id}`

- **Método:** `DELETE`
- **Respuesta:** `200` — `message`: *Subcategoria eliminada exitosamente*; `data`: `null`

---

## Registros

### `GET /registers` (listado con filtros)

- **Método:** `GET`
- **Request:** `RegisterIndexRequest` (query string, todo opcional; se pueden combinar).

| Query param | Reglas | Comportamiento |
|-------------|--------|----------------|
| `payment_status` | `sometimes`, `pending` \| `confirmed` \| `rejected` | Filtro exacto |
| `registration_code` | `sometimes`, string, máx. 255 | `LIKE %valor%` (comodines `%` y `_` escapados) |
| `team_name` | `sometimes`, string, máx. 255 | `LIKE %valor%` |
| `robot_name` | `sometimes`, string, máx. 255 | `LIKE %valor%` |

- **Respuesta:** `200` — `message`: *Registros obtenidos exitosamente*; `data`: colección de registros ordenados por `id` descendente, con `category`, `subcategory` y `teamMembers` cargados.

---

### `POST /registers`

- **Método:** `POST`
- **Request:** `RegisterRequest` (POST)
- **Headers:** `Content-Type: application/json`; conviene `Accept: application/pdf` para indicar que esperas PDF.

| Campo | Reglas |
|--------|--------|
| `team_name` | `required`, string, máx. 255 |
| `robot_name` | `required`, string, máx. 255 |
| `education_level` | `required`, `high_school` \| `university` |
| `institution` | `required`, string, máx. 255 |
| `personal_email` | `required`, email, máx. 255 |
| `institutional_email` | `required`, email, máx. 255 |
| `comments` | opcional, string, máx. 255 |
| `category_id` | `required`, existe en `categories` |
| `subcategory_id` | `required`, existe en `subcategories` |
| `has_discount` | opcional, boolean |
| `payment_date` | opcional, fecha (fecha límite de pago) |
| `team_members` | `required`, array de 1 a 4 elementos |
| `team_members.*.name` | `required`, string, máx. 255 |
| `team_members.*.personal_email` | `required`, email, máx. 255 |
| `team_members.*.institutional_email` | `required`, email, máx. 255 |

**Notas de negocio:**

- `payment_status` no se envía en POST; en base de datos queda **`pending`** por defecto.
- `price` se asigna en servidor según el precio de la subcategoría elegida (no va en el body).
- Si `has_discount` es `true`, el monto a pagar (`payable_price` en JSON) es el **50%** del precio de lista.

- **Respuesta exitosa:** `200` — cuerpo **binario PDF**, headers típicos:
  - `Content-Type: application/pdf`
  - `Content-Disposition: attachment; filename="registro-{codigo}.pdf"`
- No devuelve el envelope JSON `{ success, message, data }` en éxito.

---

### `GET /registers/{id}`

- **Método:** `GET`
- **Respuesta:** `200` — `message`: *Registro obtenido exitosamente*; `data`: objeto registro con `category`, `subcategory`, `team_members`.

---

### `PUT /registers/{id}`

- **Método:** `PUT`
- **Request:** `RegisterRequest` (no PATCH): mismos campos obligatorios que un alta, **excepto** `team_members` (no se validan / no se actualizan por este flujo; el servicio los ignora).

Incluye obligatorio:

- `team_name`, `robot_name`, `education_level`, `institution`, `personal_email`, `institutional_email`, `category_id`, `subcategory_id`, `payment_status` (`pending` \| `confirmed` \| `rejected`)

Opcionales: `comments`, `has_discount`, `payment_date`

- **Respuesta:** `200` — `message`: *Registro actualizado exitosamente*; `data`: registro fresco con relaciones.

---

### `PATCH /registers/{id}`

- **Método:** `PATCH`
- **Request:** `RegisterRequest` (PATCH): solo campos parciales; debe enviarse **al menos uno** de los siguientes en el JSON:

| Campo | Reglas |
|--------|--------|
| `payment_status` | `sometimes`, `pending` \| `confirmed` \| `rejected` |
| `payment_date` | `sometimes`, nullable, date |
| `has_discount` | `sometimes`, nullable, boolean |
| `comments` | `sometimes`, nullable, string máx. 255 |

**Comportamiento en modelo al cambiar `payment_status`:**

- `confirmed` → rellena `confirmed_at` (fecha actual).
- `pending` o `rejected` → `confirmed_at` pasa a `null`.
- `payment_date` (fecha límite) no se modifica solo por el estado; solo cambia si lo envías en el PATCH.

- **Respuesta:** `200` — `message`: *Registro actualizado exitosamente*; `data`: registro con relaciones.

---

### `DELETE /registers/{id}`

- **Método:** `DELETE`
- **Respuesta:** `200` — `message`: *Registro eliminado exitosamente*; `data`: `null`

---

## PDF (descarga directa)

No usan el envelope JSON; devuelven archivo PDF.

### `GET /pdf/registers`

- **Método:** `GET`
- **Respuesta:** PDF *registros-roborage.pdf* (todos los registros, vista horizontal).

### `GET /pdf/registers/{id}`

- **Método:** `GET`
- **Respuesta:** PDF del registro indicado (`registro-{codigo}.pdf`).

---

## Ruta adicional (revisar implementación)

En `routes/api.php` existe:

- **`POST /test-email`** → `RegisterController@sendTestEmail`

En el código actual del proyecto **no** aparece el método `sendTestEmail` en `RegisterController` (sí existe lógica relacionada en `MailService`). Hasta que se implemente el método en el controlador o se elimine la ruta, esa URL puede responder **500** o error de enrutado. No se documenta body/respuesta hasta que esté definido.

---

## Formas de los recursos en `data` (referencia)

### Categoría (`CategoryResource`)

| Campo | Tipo / notas |
|--------|----------------|
| `id` | integer |
| `name` | string |
| `project` | integer |
| `subcategories` | array (solo si relación cargada) |
| `created_at`, `updated_at` | datetime ISO o null |

### Subcategoría (`SubcategoryResource`)

| Campo | Tipo / notas |
|--------|----------------|
| `id` | integer |
| `name` | string |
| `project` | integer |
| `price` | decimal (string en JSON a veces) |
| `category_id` | integer |
| `category` | objeto categoría (solo si cargada) |
| `created_at`, `updated_at` | datetime |

### Registro (`RegisterResource`)

| Campo | Tipo / notas |
|--------|----------------|
| `id` | integer |
| `team_name`, `robot_name` | string |
| `education_level` | `high_school` \| `university` |
| `institution` | string |
| `registration_code` | string |
| `personal_email`, `institutional_email` | string |
| `price` | precio de lista (subcategoría al crear / actualizar subcategoría) |
| `payable_price` | número: total a pagar (50% si `has_discount === true`) |
| `has_discount` | boolean o null |
| `payment_status` | `pending` \| `confirmed` \| `rejected` |
| `payment_date` | fecha límite de pago o null |
| `confirmed_at` | fecha de confirmación o null |
| `comments` | string o null |
| `category_id`, `subcategory_id` | integer |
| `category`, `subcategory` | objetos (si cargados) |
| `team_members` | array de miembros (si cargados) |

### Integrante (`TeamMemberResource`)

| Campo | Tipo |
|--------|------|
| `id` | integer |
| `name` | string |
| `personal_email`, `institutional_email` | string |
| `register_id` | integer |

---

## Resumen rápido por método

| Método | Ruta | Respuesta principal |
|--------|------|---------------------|
| GET | `/categories` | JSON colección categorías |
| POST | `/categories` | JSON categoría creada (`201`) |
| GET | `/categories/{id}` | JSON categoría + subcategorías |
| PUT/PATCH | `/categories/{id}` | JSON categoría |
| DELETE | `/categories/{id}` | JSON `data: null` |
| GET | `/subcategories` | JSON colección |
| POST | `/subcategories` | JSON subcategoría (`201`) |
| GET | `/subcategories/{id}` | JSON subcategoría + categoría |
| PUT/PATCH | `/subcategories/{id}` | JSON subcategoría |
| DELETE | `/subcategories/{id}` | JSON `data: null` |
| GET | `/registers` | JSON colección (filtros query) |
| POST | `/registers` | **PDF** (no JSON) |
| GET | `/registers/{id}` | JSON registro |
| PUT | `/registers/{id}` | JSON registro |
| PATCH | `/registers/{id}` | JSON registro (parcial) |
| DELETE | `/registers/{id}` | JSON `data: null` |
| GET | `/pdf/registers` | PDF |
| GET | `/pdf/registers/{id}` | PDF |
