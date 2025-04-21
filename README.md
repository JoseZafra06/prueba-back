# 📌 DESARROLLO DE SISTEMA

## Descripción general del proyecto

Este proyecto tiene como finalidad el desarrollo de una aplicación web, para optimizar la gestión de un restaurante a través de una administración eficiente de tres aspectos clave: *Comensales*, *Mesas* y *Reservas*. La aplicación ofrece un sistema intuitivo que permite a los usuarios realizar operaciones de 
*registro*, *actualización* y *eliminación* de datos de manera sencilla y rápida, manteniendo una interfaz amigable y fácil de usar.

✅ Validaciones implementadas: Para optimizar el flujo de trabajo y mejorar la experiencia del usuario, se han implementado las siguientes validaciones.

1. **Formato de correo electrónico**: Se valida que el formato del correo electrónico sea correcto.
2. **Módulo Mesas**:
   - Verifica que el número de mesa no esté ya en uso. Si está en uso, se bloquea la operación y se muestra un mensaje de advertencia.
3. **Módulo Reservas**:
   - Verifica que la capacidad de la mesa no sea superada por el número de personas. Si la capacidad es excedida, se bloquea la operación 
   y se muestra un mensaje de advertencia.

Además, tanto los registros de **Comensales** como de **Mesas** no se eliminan físicamente. En su lugar, su estado se cambia a "eliminado", 
lo que impide que sean visibles en la interfaz. Esta medida permite salvaguardar la información relacionada, como las reservas asociadas.

---

## Requisitos previos

- **Laragon**
- **Laravel**: Framework 10.x (específicamente 10.48.29)
- **PHP**: 8.2.18
- **MySQL**: mysql-8.0.30
- **Vue.js**:
  - Vue.js versión 2
  - Node: node-v20.11.0
  - Vuetify v2
  - vue-router@3

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

> Asegúrese de que la base de datos `prueba-mesa` exista. Si no existe, Laravel la creará automáticamente al ejecutar las migraciones.

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

1. Ir al directorio del backend:  
   ```bash
   cd prueba-back/
   ```

2. Crear el archivo `.env`:  
   ```bash
   cp .env.example .env
   ```

3. Configurar el archivo `.env` con los datos correspondientes.

4. Instalar dependencias:  
   ```bash
   composer install
   ```

   > Si hay conflictos:
   ```bash
   composer update
   ```

5. Ejecutar migraciones (acepta con "YES" si pregunta por la creación de la base de datos): 
   ```bash
   php artisan migrate
   ```

6. Generar la clave de la aplicación:  
   ```bash
   php artisan key:generate
   ```

7. Levantar el servidor:  
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

- **Backend (Laravel API)**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Frontend (Vue.js)**: [http://localhost:8080](http://localhost:8080)

#### 🔢 Puertos

- Backend: `8000`  
- Frontend: `8080`  
- MySQL: `3306`

---

# 👨‍💻 Autor
**Jose Reynaldo Zafra Vargas**
