# Sistema de Gestión de Reservas

## Tecnologías Utilizadas

### Interfaz (Frontend)
El desarrollo del frontend se realizó utilizando **Vue.js 2**. Para el diseño de la interfaz se utilizó **Vuetify**, lo que permitió construir un entorno visual moderno, adaptable y amigable para el usuario. La navegación entre vistas fue gestionada mediante **Vue Router**, facilitando una experiencia fluida en la transición entre componentes del sistema.

### Backend
Se realizaron las siguientes tareas:
- Creación de migraciones para definir la estructura de la base de datos.
- Definición de modelos **Eloquent** para representar las entidades del sistema (Comensales, Mesas y Reservas), gestionar la lógica de negocio y establecer relaciones entre ellas.
- Desarrollo de controladores para manejar las operaciones **CRUD** requeridas en cada módulo.
- Configuración del archivo `.env` especificando el nombre de la base de datos utilizada.

---

## Mantenedor de Comensales

**Objetivo:**  
Gestión eficiente de la información de los Comensales permitiendo al usuario realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar).

### Requerimientos Funcionales

#### Registrar
- Permite crear un nuevo registro de Comensal.
- Muestra mensajes de confirmación al guardar los cambios.
- **Campos requeridos:**
  - Nombre completo
  - Correo electrónico (con validación de formato)
- **Campos opcionales:**
  - Teléfono (Numérico)
  - Dirección (Texto libre)

#### Editar
- Permite editar los datos de un comensal previamente registrado.
- Valida nuevamente el formato del correo electrónico.
- Permite actualizar todos los campos.
- Muestra mensajes de confirmación al guardar los cambios.

#### Eliminar
- El sistema no elimina físicamente el registro, solo cambia el estado a "eliminado" para preservar la información relacionada como reservas.
- Solicita confirmación previa al usuario.
- Muestra mensajes de éxito o error según el resultado.

---

## Mantenedor de Mesas

**Objetivo:**  
Gestión eficiente de la información de las Mesas permitiendo al usuario realizar operaciones CRUD.

### Requerimientos Funcionales

#### Registrar
- Permite crear un nuevo registro de Mesa.
- Verifica que el número de mesa no esté duplicado.
- Muestra mensajes de confirmación al guardar.
- **Campos requeridos:**
  - Número de Mesa (Numérico)
  - Capacidad (Numérico)
- **Campos opcionales:**
  - Ubicación (Texto libre)

#### Editar
- Permite editar los datos de una mesa registrada.
- Verifica nuevamente la unicidad del número de mesa.
- Muestra mensajes de confirmación al guardar.

#### Eliminar
- El sistema no elimina físicamente el registro, solo cambia su estado a "eliminado".
- Solicita confirmación previa al usuario.
- Muestra mensajes de éxito o error según el resultado.

---

## Mantenedor de Reservas

**Objetivo:**  
Gestión eficiente de la información de las Reservas permitiendo al usuario realizar operaciones CRUD.

### Requerimientos Funcionales

#### Registrar
- Permite crear un nuevo registro de reserva.
- Muestra mensajes de confirmación al guardar.
- Al seleccionar una mesa, se muestra su capacidad.
- Si el número de personas excede la capacidad, se muestra una advertencia y se bloquea el registro.
- **Campos requeridos:**
  - Fecha
  - Hora
  - Número de Personas (Numérico)
  - Comensal Asignado (Seleccionado desde lista desplegable)
  - Mesa Asignada (Seleccionada desde lista desplegable)

#### Editar
- Permite editar los datos de una reserva registrada.
- Valida nuevamente la capacidad de la mesa seleccionada.
- Muestra mensajes de confirmación al guardar.

#### Eliminar
- Solicita confirmación previa al usuario.
- Muestra mensajes de éxito o error según el resultado.

---

## Autor

**Jose Reynaldo Zafra Vargas**
