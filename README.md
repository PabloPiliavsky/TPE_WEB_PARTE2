Para obtener los datos las urls deben comenzar de la siguiente manera:
    http://localhost/tucarpeta/api/jugadores

1- Para obtener todos los jugadores el verbo y el recuerso deben ser:

        GET jugadores


1.1- Si desea filtrarlos debe agregar los query params "filtrar" que debe indicar el atributo/columna de la tabla y "valor" que debe indicar el valor de la columna por el que se quiere filtrar. La url debe tener la sigiente forma:

        GET jugadores?filtrar=filtrar&valor=valor

        los parámetros correctos para "filtrar" pueden ser:
            -id
            -nombre
            -apellido
            _descripcion(aunque no le encuentro uso a este)
            -posicion
            -id_pais
        El "valor" puede variar según la columna o atributo de la tabla que desee filtrar.

        Por ejemplo:
        GET jugadores?filtrar=posicion&valor=defensor => devuelve todos los jugadores que se desempeñan como defensores.
        GET jugadores?filtrar=id_pais&valor=2 => devuelve todos los jugadores que pertenecen a la seleccion de id_pais 2, en este caso, Francia

1.2- Si desea ordenarlos debe agregar los query params "criterio", que toma el atributo/columna de la tabla, y "orden" que toma valores para ordenarlo ascendentemente o descendentemente:

        GET jugadores?criterio=criterio&orden=orden
        GET jugadores?criterio=criterio

        En ambas opciones el criterio para poder ordenar puede ser:
            -id
            -nombre
            -apellido
            -descripcion
            -posicion
            -foto
            -id_pais
        El parámetro orden puede tomar los valores ASC/DESC
            -ASC los ordenará ascendentemente (por defecto)
            -DESC los listará en orden descendente

        En la segunda opción donde no se ingresa el parámetro orden, por defecto se mostrarán 
        de manera ascendente. 
        
        Por ejemplo:

            GET jugadores?criterio=nombre&orden=asc => devuelve la lista de jugadores por orden alfabetico ascendente de sus nombres.
            GET jugadores?criterio=id_pais&orden=descendente => devuelve la lista de jugadores ordenados por id_pais de forma descendente, o sea, empezando del que tenga el id mayor hasta el menor.


1.3- Si desea paginar la lista de jugadores debe agregar los query params "pagina", que indicará el numero de pagina que quiere mostrar, y "filas" que indicará la cantidad de filas por pagina:

        GET jugadores?pagina=pagina&filas=filas

        Los valores de "página" y "filas" deben ser enteros > 0, por lo cuál tampoco pueden ser strings ni caractéres especiales.
        En caso de que la cantidad de filas o páginas queden "fuera de rango" se mostrará el mensaje "No se encontraron jugadores" ya que devolveria un JSON vacio.

        Por ejemplo:

            GET jugadores?pagina=2&filas=5 => Divide la lista en grupos (paginas) de a 5 y muestra el segundo de ellos.

            GET jugadores?pagina=5&filas=10 => Divide la lista en grupos (paginas) de a 10 y muestra el quinto de ellos.




2- Para obtener los datos de un jugador el verbo, el recurso y el parámetro del recurso deben ser:

        GET jugadores/:ID

        El valor de :ID debe ser un entero > 0, por lo que tampoco no se aceptarán letras, ni caractéres especiales.


3- Para actualizar/editar un jugador el verbo, el recurso y el parámetro del recurso deben ser:

        PUT jugadores/:ID
    
        El valor de :ID debe ser un entero > 0, por lo que tampoco no se aceptarán letras, ni caractéres especiales..

        En el body de la request se deben ingresar los datos con el siguiente formato:
            {
                "nombre": "nombre del jugador",
                "apellido": "apellido del jugador",
                "descripcion": "Breve descripción del jugador",
                "posicion": "posición principal en la que participa para la seleccion actualmente",
                "foto": "url de la imágen del jugador",
                "id_pais": "el id que referencia al pais al que perteneces"
            }
        por ejemplo:
            {
                "nombre": "Lionel Andres",
                "apellido": "Messi Cuccittini",
                "descripcion": "Lionel Andrés Messi Cuccittini, conocido como Leo Messi, es un futbolista argentino que juega como delantero o centrocampista. Jugador histórico del Fútbol Club Barcelona, al que estuvo ligado veinte años, desde 2021 integra el plantel del Paris Saint-Germain de la Ligue 1 de Francia.",
                "posicion": "Delantero",
                "foto": ""https://library.sportingnews.com/styles/crop_style_16_9_desktop_webp/s3/2022-12/Lionel%20Messi%20-%20World%20Cup%20Final%202022%20penalty%20celebration%20vs%20France%20-%20181222-16x9.jpg.webp?itok=VSk6gUGD"",
                "id_pais": "1"
            }

        Los valores ingresados en posición pueden variar unicamente entre:
            -Arquero
            -Defensor
            -Delantero
            -Medio Campista
            
        Los valores de id_pais deben ser un número entero > 0. No se aceptarán strings ni caractéres especiales.Los valores referenciados hasta el momento son [1->Argentina, 2->Francia, 3->Croacia, 4->Marruecos]


4- Para agregar un nuevo jugador el verbo y el recurso deben ser: 

        POST jugadores

        En el body de la request se deben ingresar los datos con el siguiente formato:
            {
                "nombre": "nombre del jugador",
                "apellido": "apellido del jugador",
                "descripcion": "Breve descripción del jugador",
                "posicion": "posición principal en la que participa para la seleccion actualmente",
                "foto": "url de la imágen del jugador",
                "id_pais": "el id que referencia al pais al que perteneces"
            }

        Por ejemplo:
        {
            "nombre": "Lionel Andres",
            "apellido": "Messi Cuccittini",
            "descripcion": "Lionel Andrés Messi Cuccittini, conocido como Leo Messi, es un futbolista argentino que juega como delantero o centrocampista. Jugador histórico del Fútbol Club Barcelona, al que estuvo ligado veinte años, desde 2021 integra el plantel del Paris Saint-Germain de la Ligue 1 de Francia.",
            "posicion": "Delantero",
            "foto": ""https://library.sportingnews.com/styles/crop_style_16_9_desktop_webp/s3/2022-12/Lionel%20Messi%20-%20World%20Cup%20Final%202022%20penalty%20celebration%20vs%20France%20-%20181222-16x9.jpg.webp?itok=VSk6gUGD"",
            "id_pais": "1"
        }

        Los valores ingresados en posición pueden variar unicamente entre:
            -Arquero
            -Defensor
            -Delantero
            -Medio Campista
            
        Los valores de id_pais deben ser un número entero > 0. No se aceptarán strings ni caractéres especiales.Los valores referenciados hasta el momento son [1->Argentina, 2->Francia, 3->Croacia, 4->Marruecos]


5- Para eliminar un jugador el verbo, el recurso y el parámetro del recurso deben ser: 

        DELETE jugadores/:ID

        El valor de :ID debe ser un entero > 0, por lo que tampoco no se aceptarán letras, ni caractéres especiales.

