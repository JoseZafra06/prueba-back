# 📌 DESARROLLO DE SISTEMA

## Descripción general del proyecto

Este proyecto tiene como propósito el desarrollo de una aplicación web orientada a mejorar la gestión de un restaurante. Se enfoca principalmente 
en tres pilares fundamentales de la operación: **Comensales**, **Mesas** y **Reservas**.

La plataforma ha sido diseñada para ofrecer una experiencia de usuario ágil, intuitiva y eficiente. Permite llevar a cabo tareas de **registro**, **actualización** y **eliminación** de información de manera práctica, con una interfaz amigable que facilita la administración diaria y 
contribuye a una mejor organización interna del negocio.

#### ✅ Validaciones Implementadas

1. **Formato de Correo Electrónico**  
   - Se valida que el correo ingresado tenga un formato correcto.

2. **Módulo de Mesas**  
   - Se verifica que el número de mesa no esté ya en uso.  
   - Si está en uso, se bloquea la operación y se muestra un mensaje de advertencia.

3. **Módulo de Reservas**  
   - Se verifica que la cantidad de personas no supere la capacidad de la mesa.  
   - En caso de excederla, se bloquea la operación y se muestra un mensaje de advertencia.

Además, tanto los registros de **Comensales** como de **Mesas** no se eliminan físicamente. En su lugar, su estado se cambia a "eliminado", 
lo que impide que sean visibles en la interfaz. Esta medida permite salvaguardar la información relacionada, como las reservas asociadas.

---

## Requisitos previos

- 🚀 **Laravel**: Framework 10.x (versión específica: 10.48.29)  
- 🐘 **PHP**: 8.2.18  
- 🛢️ **MySQL**: 8.0.30  

- 🌐 **Frontend (Vue.js)**:
  - Vue.js 2
  - Vuetify 2
  - vue-router@3
  - Node.js v20.11.0

---

## Pasos para levantar el entorno de desarrollo

### 1. Clonar el repositorio

**Backend**  
```bash
git clone https://github.com/JoseZafra06/prueba-back.git
```

**Frontend**  
```bash
git clone https://github.com/JoseZafra06/prueba-front.git
```

---

### 2. Configurar las variables de entorno necesarias

1. Ve al directorio del backend:
   ```bash
   cd prueba-back/
   ```

2. Crea el archivo `.env` a partir del archivo de ejemplo:
   ```bash
   cp .env.example .env
   ```

3. Edita el archivo `.env` con la configuración de tu base de datos:
   > Asegúrese de que la base de datos `prueba-mesa` exista. Si no existe, Laravel la creará automáticamente al ejecutar migraciones.

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prueba-mesa
   DB_USERNAME=root
   DB_PASSWORD=
   ```

---

### 3. Ejecutar el comando para construir y levantar los contenedores

#### **Backend**

1. Configurar el archivo `.env` con los datos correspondientes.

2. Instalar dependencias:  
   ```bash
   composer install
   ```

   > Si hay conflictos:
   ```bash
   composer update
   ```

3. Ejecutar migraciones (acepta con "YES" si pregunta por la creación de la base de datos): 
   ```bash
   php artisan migrate
   ```

4. Generar la clave de la aplicación:  
   ```bash
   php artisan key:generate
   ```

5. Levantar el servidor:  
   ```bash
   php artisan serve
   ```

#### **Frontend**

1. Ir al directorio del frontend:  
   ```bash
   cd prueba-front/
   ```

2. Instalar dependencias:  
   ```bash
   npm install
   ```
3. Verifique primero que el servidor backend esté levantado (con php artisan serve).

4. Levantar el servidor:  
   ```bash
   npm run serve
   ```

---

### 4. Acceder a la aplicación

> Verifique que no haya otros procesos usando los puertos requeridos. Si es necesario, cierre terminales abiertas y reinicia Laragon.

#### 🌐 URLs

- 🔙 **Backend (API Laravel)**:  
  [http://127.0.0.1:8000](http://127.0.0.1:8000)

- 💻 **Frontend (Vue.js)**:  
  [http://localhost:8080](http://localhost:8080)

- 📄 **Documentación de la API (Swagger UI)**:  
  [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)

#### 🔢 Puertos

- 🔙 **Backend (Laravel)**: `8000`  
- 💻 **Frontend (Vue.js)**: `8080`  
- 🛢️ **Base de Datos (MySQL)**: `3306`

---

# 👨‍💻 Autor:
**José Reynaldo Zafra Vargas**
