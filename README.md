Para obtener los datos las urls deben comenzar de la siguiente manera:
    http://localhost/tucarpeta/api/jugadores

1- Para obtener todos los jugadores el verbo y el recuerso deben ser:

        GET jugadores


1.1- Si desea filtrarlos puede agregar los query params:

        GET jugadores?filtrar=filtar&valor=valor

        los parámetros correctos para "filtrar" pueden ser:
            -id
            -nombre
            -apellido
            -posicion
            -id_pais
        El "valor" puede variar según lo que desee filtrar.


1.2- Si desea ordenarlos puede agregar los query params:

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
            -DESC los listará en orden descendente
            -ASC los ordenará ascendentemente
        En la segunda opción donde no se ingresa el parámetro orden, por defecto se mostrarán 
        de manera ascendente. 


1.3- Si desea paginar la lista de jugadores debe indicar el verbo, el recuerso y los        parámetros get deben ser:

        GET jugadores?pagina=pagina&filas=filas

        Los valores de "página" y "filas" deben ser enteros > 0, por lo cuál tampoco pueden ser strings ni caractéres especiales.
        En caso de que la cantidad de filas o páginas queden "fuera de rango" se mostrará el mensaje "No se encontraron jugadores".


2- Para obtener los datos de un jugador el verbo, el recurso y el parámetro del recurso deben ser:

        GET jugadores/:ID

        El valor de :ID debe ser un entero > 0. Tampoco se aceptarán letras, ni caractéres especiales.


3- Para actualizar/editar un jugador el verbo, el recurso y el parámetro del recurso deben ser:

        PUT jugadores/:ID
    
        El valor de :ID debe ser un entero > 0. No se aceptarán letras, ni caractéres especiales.

        En el body de la request se deben ingresar los datos con el siguiente formato:
            {
                "nombre": "nombre",
                "apellido": "apellido",
                "descripcion": "Breve descripción del jugador",
                "posicion": "posición",
                "foto": "url de la imágen",
                "id_pais": 2
            }

        Los valores ingresados en posición pueden variar unicamente entre:
            -Arquero
            -Defensor
            -Delantero
            -Medio Campista
            
        Los valores de id_pais deben ser un número entero > 0. No se aceptarán strings ni caractéres especiales. 


4- Para agregar un nuevo jugador el verbo y el recurso deben ser: 

        POST jugadores

        En el body de la request se deben ingresar los datos con el siguiente formato:
            {
                "nombre": "nombre",
                "apellido": "apellido",
                "descripcion": "Breve descripción del jugador",
                "posicion": "posición",
                "foto": "url de la imágen",
                "id_pais": 2
            }

        Los valores ingresados en posición pueden variar unicamente entre:
            -Arquero
            -Defensor
            -Delantero
            -Medio Campista
            
        Los valores de id_pais deben ser un número entero > 0. No se aceptarán strings ni caractéres especiales. 


5- Para eliminar un jugador el verbo, el recurso y el parámetro del recurso deben ser: 

        DELETE jugadores/:ID

        El valor de :ID debe ser un entero > 0. No se aceptarán letras, ni caractéres especiales.

