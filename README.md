# WEB SERVICE
- Un web service es una **vía de intercomunicación e interoperabilidad entre máquinas conectadas en Red.**
- Con los servicios web, puede conectar aplicaciones entre sí independientemente de cómo se implementa cada aplicación y de dónde está ubicada. 
- Los servicios web aportan interoperabilidad, que es la capacidad para comunicarse o intercambiar datos entre plataformas y sistemas distintos. 
- Actualmentela mayoria de los sistemas utilizan servicios web. 
- Los sistemas se empiezan a comunicar entre ellos
- Comparten información
- Existen diferentes protocolos y arquitecturas de servicios, los principales son:
    + SOAP
    + GraphQL
    + REST 
# SERVICIOS REST 
- REST viene de, REpresentational State Transfer.
- Es un **tipo de arquitectura de desarrollo web que se apoya totalmente en el estándar HTTP.**
- REST se compone de una lista de reglas que se deben cumplir en el diseño de la arquitectura de una API.
- Los servicios REST te permiten acceder y/o modificar la información mediante los métodos HTTP, por lo cual puedes acceder a ellos mediante URLs. Por lo general regresan la información en formato JSON, aunque también pueden regresar archivos XML o csv.

# API REST 
-   Una API REST es una **interfaz de comunicación entre sistemas de información que usa el protocolo de transferencia de hipertexto** (hypertext transfer protocol o HTTP, por su siglas en inglés) para obtener datos o ejecutar operaciones sobre dichos datos en diversos formatos, como pueden ser XML o JSON.
- La API Rest brinda integración con otros sistemas por lo cuál podrá acceder y/o modificar la información mediante los métodos HTTP, lo que le permitirá acceder a ellos mediante URLs (endpoints). La información devuelta puede ser en formato JSON, XML, entre otros. 

# PASOS PARA CONSUMIR LA API REST
Para poder consumir (llamar/invocar) la API REST recomendamos el uso de **POSTMAN**, una herramienta que premite construir y gestionar peticiones a servicios REST (POST, GET, etc). Postman captura las respuestas y muestra el resultado de una forma clara y ordenada. 
Para utilizarla debe:
1. Definir la petición que desea realizar. Ver documentación.
2. Clicker en enviar o "send" y la petición será lanzada al servidor el cual devolverá una respuesta.
3. Podra visualizar la respuesta en formato (XML/JSON/TEXTO) acompañado por el código de estado HTTP.
4. Formato de la URLs para realizar cualquiera de las acciones de CRUD.  
    - <http://localhost/tu_carpeta_local/api/jugadores>

    - <http://localhost/tu_carpeta_local/api/paises>

    - <http://localhost/tu_carpeta_local/api/usuario>

*La url que debe utilizar dependerá del recurso y la acción que desee realizar.
# USUARIO

Para poder **agregar/eliminar/actualizar** un jugador ó un país se debe proporcionar un **TOKEN** el cuál se puede obtener luego de verificar usuario y contraseña.

Para realizar con éxito las tareas de alta/baja/modificación debe realizar los siguientes pasos: 

## 1. INGRESAR AL ENDPOINT DE USUARIO

```javascript
POST usuario
```
+ En el body de la __request__ se deben ingresar los datos en formato **JSON** como se indica a continuación:
```javascript
    {
       "usuario": "usuario",
       "password": "pasword"
    }
```
Por ejemplo: 
```javascript
    {
       "usuario": "Martin",
       "password": 12345
    }
```
## Los usuarios y contraseñas con permisos habilitados para realizar modificaciones son:

Usuario     | Contraseña  
----------- | -----------
Martin      |    12345
Mariano     |    12345
Creadores   |    12345

En el body de la **response** se le proporcionará el **TOKEN** el cuál deberá copiar (solo lo que figura dentro de las comillas sin incluirlas).

Por ejemplo: 

```javascript
    {
       "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.       eyJzdWIiOjEsImlhdCI6MTY4ODQxMDk4NCwiZXhwIjoxNjg4NDE0NTg0LCJkYXRhIjoiTWFydGluIn0=.VIUiGOxDahr1p31Z3ki989dp5vVViqUa1YWvJUI4z7g="
    }
```
Es decir, solo debería copiar:

    eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsImlhdCI6MTY4ODQxMDk4NCwiZXhwIjoxNjg4NDE0NTg0LCJkYXRhIjoiTWFydGluIn0=.VIUiGOxDahr1p31Z3ki989dp5vVViqUa1YWvJUI4z7g=
### Si los campos de _usuario_ y/o _password_ se encuentran vacíos recibirá la siguiente respuesta:

> + Código de estado HTPP
>   - 400 (Bad request)
> + Cuerpo de la respuesta
>   - "Debe indicar el nombre de usuario y/o password."

## 2. AGREGAR EL ENDPOINT DESEADO
Por ejemplo, para agregar un país, debe ser:
```javascript
    POST paises
```
En el body de la **request** deberá ingresar los datos del país que se quiere agregar como se muestra en el punto 4 de la sección PAISES.
        
## 3. AUTORIZACIÓN:
En el apartado Autorization se deberá elegir el tipo **Bearer Token** y pegar el **TOKEN** previamente copiado.

Si el token es correcto y los datos son ingresados de manera adecuada se podrán realizar las acciones mencionadas: editar/agregar/eliminar tanto para jugadores como para paises. 


# JUGADORES

> ### **¡IMPORTANTE!**
>
> A tener en cuenta al momento de ingresar datos en el cuerpo de la _request_ o en el _endpoint_:
>
>  #### **EN EL ENDPOINT:**
> + El valor de __:ID__ debe ser un entero > 0, por lo que no se aceptarán letras, ni caractéres especiales.
>
> #### **EN EL CUERPO DE LA _REQUEST_**
> + Los valores ingresados en __id_pais__ debe ser un entero > 0, por lo que no se aceptarán letras, ni caractéres especiales.
> + Los valores permitidos en  __posicion__ pueden variar unicamente entre:
>   - Arquero
>   - Defensor
>   - Delantero
>   - Medio Campista


## 1. LISTAR TODOS LOS JUGADORES.
Para obtener todos los jugadores, en el endpoint, el verbo y el recurso deben ser:
```javascript
    GET jugadores
```
### La respuesta mostrará lo siguiente

> + Código de estado HTPP
>   - 200 (Ok)
> + Cuerpo de la respuesta
>   - Listará la totalidad de los jugadores en formato JSON, ordenados por id ascendentemente a menos que se indique mediante query params que desea mostrarlos filtrados, ordenados (con otro criterio u orden) o paginados.

## 1.1 FILTRADO:
Para filtrar la obtención de los jugadores debe agregar los query params **filtrar** que debe indicar el atributo/criterio a filtrar y **valor** en el cuál se debe indicar el valor del filtro especificado. 

+ El endoint debe tener la sigiente forma:

```javascript
GET jugadores?filtrar=filtrar&valor=valor
```
+ los parámetros correctos para "filtrar" pueden ser:
    - id
    - nombre
    - apellido
    - posicion
    - id_pais

+ El "valor" puede variar según la columna o atributo de la tabla que desee filtrar.

Ejemplo 1:

```javascript
GET jugadores?filtrar=posicion&valor=defensor
```
Devuelve todos los jugadores que se desempeñan como defensores.

Ejemplo 2:

```javascript
GET jugadores?filtrar=id_pais&valor=2
```
Devuelve todos los jugadores que pertenecen a la seleccion de id_pais 2, en este caso, Francia.

### Si los datos son ingresados correctamente la respuesta mostrará lo siguiente

> + Código de estado HTPP
>   - 200 (Ok)
> + Cuerpo de la respuesta
>   - Listará los jugadores en formato JSON, ordenados por id ascendentemente que cumplan con el filtro/valor indicados.

### Si el criterio o el valor no son correctos la respuesta será

> + Código de estado HTPP
>   - 400 (Bad request)
> + Cuerpo de la respuesta
>   - "Verificar el filtro elegido como criterio y el valor ingresado"

### Si no existen jugadores con el valor ingresado la respuesta será

> + Código de estado HTPP
>   - 404 (Not found)
> + Cuerpo de la respuesta
>   - "No hay ningun jugador con ese valor"


## 1.2 ORDENADO:
Para ordenar la obtención de todos los jugadores debe agregar los query params **criterio** (que debe indicar el atributo con el cuál desea ordenar) y **orden** (que indicará el valor para ordenarlo ascendentemente o descendentemente):

Opción 1:
```javascript
    GET jugadores?criterio=criterio&orden=orden
```
Opción 2:
```javascript
    GET jugadores?criterio=criterio
```

+ En ambas opciones el **criterio** para poder ordenar puede ser:
    - id
    - nombre
    - apellido
    - descripcion
    - posicion
    - foto
    - id_pais

+ El la opción 1 el parámetro **orden** puede tomar los valores *ASC/DESC* ó *asc/desc*.
    - ASC los ordenará ascendentemente (por defecto)
    - DESC los listará en orden descendente

+ En la opción 2 donde no se ingresa el parámetro orden, por defecto se mostrarán de manera ascendente. 
        
Ejemplo 1: 

```javascript
    GET jugadores?criterio=nombre&orden=ASC 
```
Devuelve la lista de jugadores por orden alfabético ascendente de sus nombres.

Ejemplo 2:
```javascript
    GET jugadores?criterio=id_pais&orden=DESC 
```
Devuelve la lista de jugadores ordenados por id_pais de forma descendente, o sea, empezando del que tenga el id mayor hasta el menor.

### En cualquiera de los casos si los parámetros son correctos la respuesta será

> + Código de estado HTPP
>   - 200 (Ok)
> + Cuerpo de la respuesta
>   - Listará todos los jugadores en formato JSON, ordenados por el criterio y orden especificados.

### Si los datos son incorrectos o se encuentran vacíos la respuesta será

> + Código de estado HTPP
>   - 400 (Bad request)
> + Cuerpo de la respuesta
>   - "Verificar el criterio y/o valor ingresados"
### Si el orden no cumple con el formato indicado la respuesta será

> + Código de estado HTPP
>   - 400 (Bad request)
> + Cuerpo de la respuesta
>   - "Verificar el orden elegido"


### Si el criterio es correcto pero el valor no existe la respuesta será

> + Código de estado HTPP
>   - 404 (Not found)
> + Cuerpo de la respuesta
>   - "No hay ningún paises con ese valor"


## 1.3 PAGINACIÓN:
Si desea paginar la lista de jugadores debe agregar los query params **pagina**, que indicará el numero de página que quiere mostrar y **filas** que hace referencia a la cantidad de registros obtenidos por página:

```javascript
    GET jugadores?pagina=pagina&filas=filas
```
+ Los valores de _pagina_ y _filas_ deben ser enteros > 0, por lo cuál tampoco pueden ser strings ni caractéres especiales.

Ejemplo 1: 

```javascript
    GET jugadores?pagina=2&filas=5 
```
Divide la lista en grupos (páginas) de a 5 y muestra el segundo de ellos.

Ejemplo 2:
```javascript
    GET jugadores?pagina=5&filas=10 
```
Divide la lista en grupos (páginas) de a 10 y muestra el quinto de ellos.



### En caso de que la cantidad de filas o páginas queden "fuera de rango" la respuesta será:

> + Código de estado HTPP
>   - 404 (Not found).
> + Cuerpo de la Respuesta 
>   - "La página pedida con esa cantidad de filas no contiene elementos."

Debido a que devolveria un JSON vacio.

### Si los datos estan vacíos o no cumplen con el formato inicado la respuesta será:
> + Código de estado HTPP
>   - 400 (Bad request).
> + Cuerpo de la Respuesta 
>   - "Verificar que los parámetros utilizados sean correctos. Ver más información en la documentación"

### Si los parámetros son correctos y existen registros en la página indicada la respuesta será

> + Código de estado HTPP:
>   - 200 (Ok)
> + Cuerpo de la respuesta 
>   - Listará todos los registros que se encuentren dentro de ese rango de página/filas.

## 2. OBTENER DATOS DE UN JUGADOR ESPECÍFICO:

Para obtener los datos de un jugador en particular en el endpoint, el verbo, el recurso y el parámetro del recurso deben ser:

```javascript
    GET jugadores/:ID
```

Ejemplo 1:
```javascript
    GET jugadores/15 
```
Muestra los datos del jugador con id 15.
  
Ejemplo 2:
```javascript
    GET jugadores/21
```
Muestra los datos del jugador con id 21.

### Si el ID ingresado es válido y corresponde con el de un jugador la respuesta será:

> + Código de estado HTPP: 
>   - 200 (Ok)
> + Cuerpo de la respuesta: 
>   - Mostrará en formato JSON los datos del jugador seleccionado.

### Si el ID ingresado no corresponde a ningún jugador de la lista, la respuesta será:

> + Código de estado HTPP: 
>   - 404 (Not found)
> + Cuerpo de la respuesta: 
>   - "El jugador con el id ":ID" no existe".

### Si el ID ingresado no cumple con el formato indicado o se encuentra vacío se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor verifique los datos ingresados".


## 3. ACTUALIZAR/EDITAR UN JUGADOR:
Para actualizar/editar un jugador en el endpoint, el verbo, el recurso y el parámetro del recurso deben ser:

```javascript
    PUT jugadores/:ID
```  

En el body de la request se deben ingresar los datos con el siguiente formato:
```javascript
    {
        "nombre": "nombre del jugador",
        "apellido": "apellido del jugador",
        "descripcion": "Breve descripción del jugador",
        "posicion": "posición principal en la que participa para la seleccion actualmente",
        "foto": "url de la imágen del jugador",
        "id_pais": "el id que referencia al pais al que perteneces"
    }
```   
Por ejemplo:
```javascript
    {
        "nombre": "Lionel Andres",
        "apellido": "Messi Cuccittini",
        "descripcion": "Lionel Andrés Messi Cuccittini, conocido como Leo Messi, es un futbolista argentino que juega como delantero o centrocampista. Jugador histórico del Fútbol Club Barcelona, al que estuvo ligado veinte años, desde 2021 integra el plantel del Paris Saint-Germain de la Ligue 1 de Francia.",
        "posicion": "Delantero",
        "foto": "https://library.sportingnews.com/styles/crop_style_16_9_desktop_webp/s3/2022-12/Lionel%20Messi%20-%20World%20Cup%20Final%202022%20penalty%20celebration%20vs%20France%20-%20181222-16x9.jpg.webp?itok=VSk6gUGD",
        "id_pais": 1
    }
```  
### Si existe un jugador con el ID especificado y los datos ingresados son correctos la respuesta será:

> + Código de estado HTPP: 
>   - 200 (Ok)
> + Cuerpo de la respuesta: 
>   - Mostrará en formato JSON los datos del jugador actualizado.

### Si el ID ingresado no corresponde a ningún jugador de la lista, la respuesta será:

> + Código de estado HTPP: 
>   - 404 (Not found)
> + Cuerpo de la respuesta: 
>   - "No existe ningún jugador con el id ingresado".

### Si el ID ingresado no cumple con el formato indicado o se encuentra vacío se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor verifique que el id se ingresó correctamente".

## 4. AGREGAR UN JUGADOR
 Para agregar un nuevo jugador en el endpoint, el verbo y el recurso deben ser: 
```javascript
    POST jugadores
``` 
En el body de la request se deben ingresar los datos con el siguiente formato:
```javascript
    {
        "nombre": "nombre del jugador",
        "apellido": "apellido del jugador",
        "descripcion": "Breve descripción del jugador",
        "posicion": "posición principal en la que participa para la seleccion actualmente",
        "foto": "url de la imágen del jugador",
        "id_pais": "el id que referencia al pais al que perteneces"
    }
``` 
Por ejemplo:
```javascript
    {
        "nombre": "Lionel Andres",
        "apellido": "Messi Cuccittini",
        "descripcion": "Lionel Andrés Messi Cuccittini, conocido como Leo Messi, es un futbolista argentino que juega como delantero o centrocampista. Jugador histórico del Fútbol Club Barcelona, al que estuvo ligado veinte años, desde 2021 integra el plantel del Paris Saint-Germain de la Ligue 1 de Francia.",
        "posicion": "Delantero",
        "foto": "https://library.sportingnews.com/styles/crop_style_16_9_desktop_webp/s3/2022-12/Lionel%20Messi%20-%20World%20Cup%20Final%202022%20penalty%20celebration%20vs%20France%20-%20181222-16x9.jpg.webp?itok=VSk6gUGD",
        "id_pais": 1
    }
``` 
### Si los datos ingresados son correctos la respuesta será:

> + Código de estado HTPP: 
>   - 201 (Created)
> + Cuerpo de la respuesta: 
>   - Mostrará en formato JSON los datos del jugador agregado.

### Si hay datos vacíos en el cuerpo de la request la respuesta será:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor complete todos los datos".

### Si el id_pais ingresado no cumple con el formato indicado o se encuentra vacío se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "El id del pais no es correcto".

### Si ocurre algun error al agregar el jugador en la base de datos la respuesta será:
> + Código de estado HTPP: 
>   - 500 (Internal Server Error)
> + Cuerpo de la respuesta: 
>   - "El jugador no se pudo agrear con éxito".


## 5. ELIMINAR UN JUGADOR
Para eliminar un jugador en el endpoint el verbo, el recurso y el parámetro del recurso deben ser: 
```javascript
    DELETE jugadores/:ID
``` 

Ejemplo 1: 
```javascript
    DELETE jugadores/3 
``` 
Elimina de la tabla el jugador con id igual a 3.

Ejemplo 2:
```javascript           
    DELETE jugadores/17 
``` 
Elimina de la tabla el jugador con id igual a 17.

### Si el ID corresponde a un jugador de la lista y se elimina con éxito la respuesta será::

> + Código de estado HTPP: 
>   - 200 (Ok)
> + Cuerpo de la respuesta: 
>   - "El jugador con el id ":ID" se eliminó con éxito".


### Si el ID ingresado no cumple con el formato indicado o se encuentra vacío se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor verifique el id ingresado".

### Si el ID ingresado no se condice con el de algún jugador la srespuesta será:
> + Código de estado HTPP: 
>   - 404 (Not found)
> + Cuerpo de la respuesta: 
>   - "El jugador no se pudo eliminar, porque no existe el id :ID"


# PAISES 

>### **¡IMPORTANTE!**
>
> A tener en cuenta al momento de ingresar datos en el cuerpo de la _request_ o en el _endpoint_:
>
> #### EN EL ENDPOINT:
> + El valor de __:ID__ debe ser un entero > 0, por lo que no se aceptarán letras, ni caractéres especiales.
>
> #### EN EL CUERPO DE LA _REQUEST_
> + Los valores ingresados en __nombre__ y __clasificacion__ son únicos es decir, no pueden repetirse.
> + El valor ingresados en  __clasificacion__ debe ser un entero > 0.



## 1. LISTAR TODOS LOS PAISES
Para obtener todos los paises en el endpoint, el verbo y el recuerso deben ser:
```javascript  
    GET paises
```  
### La respuesta mostrará lo siguiente

> + Código de estado HTPP
>   - 200 (Ok)
> + Cuerpo de la respuesta
>   - Listará la totalidad de los paises en formato JSON, ordenados por id ascendentemente a menos que se indique mediante query params que desea mostrarlos filtrados u ordenados (con otro criterio u orden).

## 1.1 FILTRADO
+ Si desea filtrarlos debe agregar los query params **filtrar** (que indica el criterio/atributo con el cual quiere filtrar) y **valor** (donde debe indicar el valor por el cual debe filtrarse). 
+ El endpoint debe tener la siguiente forma:

```javascript
    GET paises?filtrar=filtrar&valor=valor
```
+ Los parámetros correctos para **filtrar** pueden ser:
    - id
    - nombre
    - continente
    - clasificacion
+ El **valor** puede variar según el atributo de la tabla que desee filtrar.

Por ejemplo:
```javascript
    GET paises?filtrar=continente&valor=Europa 
```
Devuelve todos los paises Europeos que hayan clasificado.

### Si los datos son ingresados correctamente la respuesta mostrará lo siguiente

> + Código de estado HTPP
>   - 200 (Ok)
> + Cuerpo de la respuesta
>   - Listará los paises en formato JSON, ordenados por id ascendentemente que cumplan con el filtro/valor indicados.

### Si los datos son incorrectos o se encuentran vacíos la respuesta será

> + Código de estado HTPP
>   - 400 (Bad request)
> + Cuerpo de la respuesta
>   - "Verificar el filtro elegido como criterio y/o el valor ingresado"

### Si el criterio es correcto pero el valor no existe la respuesta será

> + Código de estado HTPP
>   - 404 (Not found)
> + Cuerpo de la respuesta
>   - "No hay ningún paises con ese valor"

## 1.2. ORDENADO
Si desea ordenarlos debe agregar los query params **criterio** (que indica el atributo/columna de la tabla) y **orden** (donde debe indicar los valores para ordenarlo ascendente o descendentemente):

+ La url debe puede variar entre las siguientes opciones:

Opción 1: 
```javascript
    GET paises?criterio=criterio&orden=orden
```

Opción 2:
```javascript
    GET paises?criterio=criterio
```
+ En ambas opciones el "query params" _criterio_ para poder ordenar puede ser:
    - id
    - nombre
    - continente
    - clasificacion
    - bandera

+ En la opción 1 el parámetro _orden_ puede tomar los valores ASC/DESC o asc/desc unicamente.
    - ASC ó asc los ordenará ascendentemente
    - DESC ó desc los listará en orden descendente

+ En la opción 2 donde no se ingresa el parámetro _orden_, por defecto se mostrarán de manera ascendente. 
        
Ejemplo 1:
```javascript
    GET paises?criterio=nombre&orden=ASC 
```
Devuelve la lista de paises por orden alfabético ascendente de sus nombres.

Ejemplo 2:
```javascript
    GET paises?criterio=id&orden=DESC 
```
Devuelve la lista de paises ordenados por id de forma descendente, o sea, empezando del que tenga el id mayor hasta el menor.


### En cualquiera de los casos si los parámetros son correctos la respuesta será

> + Código de estado HTPP
>   - 200 (Ok)
> + Cuerpo de la respuesta
>   - Listará todos los paises en formato JSON, ordenados por el criterio y orden especificados.

### Si los datos son incorrectos o se encuentran vacíos la respuesta será

> + Código de estado HTPP
>   - 400 (Bad request)
> + Cuerpo de la respuesta
>   - "Verificar el criterio elegido y/o el valor ingresado"

### Si el orden no cumple con el formato indicado la respuesta será

> + Código de estado HTPP
>   - 400 (Bad request)
> + Cuerpo de la respuesta
>   - "Verificar el orden elegido"


### Si el criterio es correcto pero el valor no existe la respuesta será

> + Código de estado HTPP
>   - 404 (Not found)
> + Cuerpo de la respuesta
>   - "No hay ningún paises con ese valor"

## 2. OBTENER LOS DATOS DE UN PAÍS ESPECÍFICO
Para obtener los datos de un país en el endpoint, el verbo, el recurso y el parámetro del recurso deben ser:

```javascript
    GET paises/:ID
```

Ejemplo 1:
```javascript
    GET paises/15 
```
Muestra los datos del país con id 15.

Ejemplo 2:
```javascript
    GET paises/21 
```
Muestra los datos del país con id 21.

### Si el ID ingresado es válido y corresponde con el de un país la respuesta será:

> + Código de estado HTPP: 
>   - 200 (Ok)
> + Cuerpo de la respuesta: 
>   - Mostrará en formato JSON los datos del país seleccionado.

### Si el ID ingresado no corresponde a ningún país de la lista, la respuesta será:

> + Código de estado HTPP: 
>   - 404 (Not found)
> + Cuerpo de la respuesta: 
>   - "El pais con el id :ID no existe".

### Si el ID ingresado no cumple con el formato indicado o se encuentra vacío se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor verifique los datos ingresados".

## 3. ACTUALIZAR/EDITAR UN PAÍS
Para actualizar/editar un país en el endpoint el verbo, el recurso y el parámetro del recurso deben ser:

```javascript
    PUT paises/:ID
```    

En el body de la request se deben ingresar los datos con el siguiente formato:
```javascript
    {
        "nombre": "nombre",
        "continente": "continente",
        "clasificacion": int,
        "bandera": "url de la bandera"
    }
```
Por ejemplo:
```javascript
    {
        "nombre": "Marruecos",
        "continente": "Africa",
        "clasificacion": 4,
        "bandera": "https://touringinmorocco.com/es/wp-content/uploads/2022/04/morocco-flag.jpg"
    }
```
### Si existe un país con el ID especificado y los datos ingresados son correctos la respuesta será:

> + Código de estado HTPP: 
>   - 200 (Ok)
> + Cuerpo de la respuesta: 
>   - Mostrará en formato JSON los datos del país actualizado.

### Si el ID ingresado no corresponde a ningún país de la lista, la respuesta será:

> + Código de estado HTPP: 
>   - 404 (Not found)
> + Cuerpo de la respuesta: 
>   - "No existe ningún país con el id ingresado".

### Si el ID ingresado no cumple con el formato indicado o se encuentra vacío se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor verifique que el id se ingresó correctamente".

### Si el nombre o la clasificación se modifican y ya existen en la base de datos se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "El nombre o la clasificación no se pueden repetir".


## 4. AGREGAR UN PAÍS
Para agregar un nuevo país, en el endpoint, el verbo y el recurso deben ser: 

```javascript
    POST paises
```
En el body de la _request_ se deben ingresar los datos con el siguiente formato:
```javascript
    {
        "nombre": "nombre",
        "continente": "continente",
        "clasificacion": int,
        "bandera": "url de la bandera"
    }
```
Por ejemplo:
```javascript
    {
        "nombre": "Argentina",
        "continente": "America",
        "clasificacion": 1,
        "bandera": "https://c.files.bbci.co.uk/D348/production/_95588045_178392703.jpg"
    }
```

### Si los datos ingresados son correctos la respuesta será:

> + Código de estado HTPP: 
>   - 201 (Created)
> + Cuerpo de la respuesta: 
>   - Mostrará en formato JSON los datos del país agregado.

### Si hay datos vacíos en el cuerpo de la request la respuesta será:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor complete todos los datos".

### Si el nombre o la clasificación ingresados ya existen en la base de datos se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "El pais o la clasificación ya existen".

### Si la clasificación ingresada no cumple con el formato indicado mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "La clasificación debe ser un número mayor a 0".

### Si ocurre algun error al agregar el jugador en la base de datos la respuesta será:
> + Código de estado HTPP: 
>   - 500 (Internal Server Error)
> + Cuerpo de la respuesta: 
>   - "El pais no se pudo agregar con éxito".

## 5. ELIMINAR UN PAÍS

> ### ¡IMPORTANTE! 
> Solo se podrán borrar paises que no tengan ningún jugador asociado. 

Para eliminar un país en el endpoint , el verbo, el recurso y el parámetro del recurso deben ser: 

```javascript
    DELETE paises/:ID
```

Ejemplo 1:
```javascript
    DELETE paises/3 
```
Elimina de la tabla el paises con id igual a 3.

Ejemplo 2:
```javascript           
    DELETE paises/1 
```
Elimina de la tabla el paises con id igual a 1.

### Si el ID corresponde a un país de la lista y se elimina con éxito la respuesta será:

> + Código de estado HTPP: 
>   - 200 (Ok)
> + Cuerpo de la respuesta: 
>   - "El pais con el id :ID se eliminó con éxito".

### Si el país que desea borrar tiene jugadores vinculados se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "El pais no se pudo eliminar, porque contiene registros vinculados".

### Si el ID ingresado no cumple con el formato indicado o se encuentra vacío se mostrará lo siguiente:

> + Código de estado HTPP: 
>   - 400 (Bad request)
> + Cuerpo de la respuesta: 
>   - "Por favor verifique el id ingresado".

### Si el ID ingresado no se condice con el de algún jugador la srespuesta será:
> + Código de estado HTPP: 
>   - 404 (Not found)
> + Cuerpo de la respuesta: 
>   - "El pais no se pudo eliminar, porque no existe el id :ID"
