# EventHub API

API RESTful en Laravel 13 para la gestión de eventos, con autenticación por tokens (Laravel Sanctum), autorización por roles (Gate) y autorización por propiedad del recurso (Policy).

## Requisitos

- Docker y Docker Compose
- Ubuntu en WSL2 (o cualquier Linux/macOS con Docker)

## Instalación

```bash
git clone https://github.com/Sj-kronton/Taller_10_Laravel_Backend
cd Taller_10_Laravel_Backend
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
sudo chmod -R 777 storage bootstrap/cache
```

Espera unos 15 segundos a que MySQL termine de arrancar y ejecuta las migraciones y el seeder:

```bash
docker compose exec app php artisan migrate:fresh --seed
```

La API queda disponible en `http://localhost:8080/api`.

> `migrate:fresh` elimina todas las tablas antes de recrearlas.

## Usuarios de prueba

Todos usan la contraseña `password123`.

| Rol | Email |
|---|---|
| admin | admin@eventhub.test |
| organizador (A) | organizador.a@eventhub.test |
| organizador (B) | organizador.b@eventhub.test |
| asistente | asistente@eventhub.test |

El seeder también crea 2 eventos a nombre del Organizador A.

## Endpoints

| Método | Ruta | Autenticación | Descripción | Códigos |
|---|---|---|---|---|
| POST | `/api/login` | No | Recibe `email` y `password`, devuelve token Bearer | 200, 401, 422 |
| GET | `/api/eventos` | No | Lista eventos con el nombre del organizador | 200 |
| GET | `/api/perfil` | Bearer | Datos del usuario autenticado | 200, 401 |
| PUT | `/api/eventos/{id}` | Bearer | Actualiza un evento (solo su creador) | 200, 401, 403, 404, 422 |
| GET | `/api/admin/metricas` | Bearer + admin | Total de eventos y monto recaudado | 200, 401, 403 |

## Ejemplos con curl

Iniciar sesión:

```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"organizador.a@eventhub.test","password":"password123"}'
```

Usar el token recibido:

```bash
curl http://localhost:8080/api/perfil \
  -H "Authorization: Bearer <TOKEN>"
```

Editar un evento propio:

```bash
curl -X PUT http://localhost:8080/api/eventos/1 \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{"titulo":"Nuevo título","descripcion":"Nueva sinopsis","cupo_maximo":250}'
```

Consultar métricas (requiere token del admin):

```bash
curl http://localhost:8080/api/admin/metricas \
  -H "Authorization: Bearer <TOKEN_ADMIN>"
```
## Probador visual (opcional)

El archivo `public/probador.html` es una herramienta de apoyo para probar la API desde el navegador en `http://localhost:8080/probador.html`. No forma parte de la API: es un cliente independiente que consume los endpoints con tokens Bearer, sin sesiones ni cookies.

## Decisiones de diseño

- **Sanctum:** autenticación sin estado mediante tokens Bearer; no se usan sesiones ni cookies.
- **Contraseñas:** se almacenan con bcrypt mediante el cast `hashed` del modelo `User`.
- **Gate `ver-metricas`:** definido en `AppServiceProvider`; el controlador no conoce la regla. Se aplica a la ruta con el middleware `can:ver-metricas`.
- **Policy `EventoPolicy`:** solo el usuario cuyo `id` coincide con `eventos.user_id` puede modificar un evento. Ni siquiera el admin puede editar eventos ajenos. La autorización se evalúa antes que la validación, por lo que un usuario sin permiso recibe 403 aunque envíe datos inválidos.
- **`user_id` protegido:** no está en `$fillable` de `Evento`, así que no puede reasignarse por la API.
- **Respuestas JSON:** `bootstrap/app.php` fuerza respuestas JSON en `api/*` y desactiva la redirección al login, de modo que las peticiones sin token devuelven 401 aunque el cliente no envíe `Accept: application/json`.
- **Monto recaudado:** el modelo de datos del taller no incluye campos monetarios, por lo que `monto_total_recaudado` se reporta como `0`.

## Códigos HTTP

| Código | Significado |
|---|---|
| 200 | Consulta o actualización exitosa |
| 401 | Sin token, token inválido o credenciales erróneas |
| 403 | Autenticado, pero sin permiso (Gate o Policy) |
| 404 | Evento inexistente |
| 422 | Errores de validación |
