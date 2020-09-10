<!-- Aca vamos a crear nuestro componente -->
<template>
    <!-- Aca vamos a hacer nuestro formulario -->
    <div>
        <!-- Formulario de editar nota -->

        <!-- 

            Cosas a tener en cuenta dentro de la etiqueta <form>
            vamos a agregar dos atributos que son de vue.js

            @submit.prevent="editarNota(nota)" -> este se ejecuta cuando das click en el boton editar
            ejecuta la funcion editarNota y pasa como parametro el arreglo de la nota.

            el segundo atributo es el de v-if="modoeditar" -> es una condicion de vue, se ejecutara 
            cuando la variable modoEditar es verdadera, esto es muy util cuando hay contenidos
            que queramos mostrar si cumple o no con la condicion.

            para los inputs al momento de almacenar informacion y poder enviar, usaremos el
            atributo v-model. ejemplo v-model="nombre" o v-model="contraseña" o v-model="email"
            cuando un usuario escribe en el el value se almacena en dicha variable creada o que vamos
            a crear mas abajo. 

            el button cancelar, tendra un onClick, pero en vue no es onClick si no @click="nombre de la funcion"

            una vez teniendo en cuentas estos parametros sigamos adelante.
            para mas informacion pueden revisar la documentacion de Vue.js
            ahi podran entender mucho mejor como se usan estos atributos basicos
            incluso hay ejemplos dinamicos.

        -->

        <form @submit.prevent="editarNota" v-if="modoEditar">
            <h3>Editar Nota</h3>
            <input 
            type="text" 
            placeholder="Nombre de la nota"
            v-model="nombre"
            >
            <input 
            type="text" 
            placeholder="Descripción de la nota"
            v-model="descripcion"
            >
            <button type="submit">Editar</button>
            <button type="submit" @click="cancelarEdicion">Cancelar</button>
        </form>

        <!-- Formulario de crear nota -->

        <!-- 

            aqui como se daran cuenta aparece un v-else en la etiqueta <form>, que significa?.

            Esto significa que cuando la condicion del formulario de arriba
            en este caso del v-if="modoEditar" no es verdadera se mostrara este formulario de registro.
            En caso contrario el formulario de editar se mostrara.

        -->
        
        <form @submit.prevent="agregar" v-else>
            <h3>Agregar Nota</h3>
            <input 
            type="text" 
            placeholder="Nombre de la nota"
            v-model="nombre"
            >
            <input 
            type="text" 
            placeholder="Descripción de la nota"
            v-model="descripcion"
            >
            <button type="submit">Agregar</button>
            <button type="submit">Cancelar</button>
        </form>
        <hr>

        <!-- Lista o tabla de Notas -->

        <!-- 

            Lista de Notas.

            En esta parte crearemos una lista de las notas a mostrar.
            para eso podemos usar una etiqueta <ul> o una <table> cualquiera sirve

            en la etiqueta <li> encontramos dos atributos

            El primero atributo v-for este atributo se encargara de hacer un recorrido
            hacia un objeto o arreglo, en este caso de notas.

            v-for="(item, index) in notas" -> del recorrido traerme el item y el index del objeto notas

            El segundo atributo es la :key, es un valor unico que debe tener la lista, ya que si no se coloca
            saldra un error, por defecto siempre es colocar el index.

            Dentro de la etiqueta <span> encontraran esto {{ item.updated_at }}
            Esto es una interpolacion y dentro de ella le estamos diciendo que imprima 
            la fecha de la nota, lo mismo va para el nombre y la descripcion de las cuales
            se encuentran dentro de una etiqueta <p>

            y por ultimo dos buttons cada uno con un @click
            una para editar y la otra para eliminar

            cada una con sus parametros correspondientes      


        -->

        <h3>Lista de Notas:</h3>
        <ul>
            <li v-for="(item, index) in notas" :key="index">
            <span>
                {{item.updated_at}}
            </span>
            <p>{{item.nombre}}</p>
            <p>{{item.descripcion}}</p>
            <p>
                <button @click="editarFormulario(item)">Editar</button>
                <button @click="eliminarNota(item, index)">Eliminar</button>
            </p>
            </li>
        </ul>
    </div>
</template>

<!--  

    Bueno, ya vimos que construimos nuestros formularios y las listas, ahora es momento de hacerla
    dinamica para eso, aca abajo afuera de las etiquetas <template>
    vamos a crear una etiqueta <script>

  -->

<script>
    
    /** Empezamos con esta clase que no tiene nombre por defecto.  */
    export default {

        /**
         *  Creamos un metodo llamado data() 
         * 
         *  En data() vamos a definir nuestras variables globales
         * 
         */

        data() {
            return {
                //Declaramos un arreglo vacio, llamado notas. Si recuerdan bien
                //este arreglo se en encuentra en el v-for.
                notas:[],
                //declaramos otra variable y esta se encargar de validar si el 
                //usuario va a editar una nota o no. por ahora lo definimos en falso.
                //esto es para que muestre el formulario de registro y no el de editar
                //Cuando el usuario clicke la opcion editar este valor pasara a true.
                modoEditar:false,
                //creamos la variable nombre del input y lo inicializamos en vacio
                //Lo mismo con la descripcion
                //Tambien crearemos una variable global para el id de la nota a modificar o 
                //a eliminar
                id:'',
                nombre:'',
                descripcion:''   
            }
        },
        /**
         * Creamos el metodo created()
         * 
         * Este metodo se le conoce como ciclo de vida
         * El metodo se encargara de ejecutarse una vez el componente se renderice
         * Esto es muy util cuando queremos obtener informacion antes de que cargue
         * nuestro componente.
         * 
         */
        created() {
            //Vamos a crear un metodo llamado getNotas()
            //y dicho mtedodo lo colocamos abajo en el arreglo methods
            this.getNotas();
        },
        /**
         * Creamos los metodos
         * methods:
         * Aca son las funciones que se ejecutan a traves de una accion.
         * Por ejemplo en el formulario @submit.prevent="agregar()"
         */
        methods:{

            getNotas() {
                axios.get('/notas').then(respuesta => {
                    this.notas = respuesta.data;
                })
            },

            /**
             * Empecemos con el metodo crearnota o agregar
             */

            agregar() {
                //Validamos si los campos estan vacios
                if (this.nombre.trim() === '' || this.descripcion.trim() === '') {
                    //Imprimos un mensaje de que los campos no pueden estar vacios
                    alert('Debes completar todos los campos antes de guardar');
                    //retornamos null
                    return; 
                }

                //Si los campos no estan vacios, procedemos a enviar la informacion
                //Llamos axios y ejecutamos la funcion .post 
                //le pasamos la ruta y sus parametros.

                axios.post('/notas', {nombre:this.nombre, descripcion:this.descripcion})
                .then((res) => {
                    //Una vez hecho el envio 
                    //Ejecutamos el metodo getNotas()
                    //Para asi obtener los ultimos registros
                    this.getNotas();
                })
            },


            /**
             * Ahora vamos a crear la funcion editarFormulario
             * 
             * En esta funcion vamos a recibir nuestro parametro
             * item correspondiente a editar.
             * 
             */

            editarFormulario(item) {
                //mostramos el nombre en el formulario editar
                this.nombre = item.nombre;
                //Tambien para descripcion
                this.descripcion = item.descripcion;
                //Y por ultimo el id
                this.id = item.id;
                //Y mostramos el formulario de editar
                //recordemos que la variable por defecto estaba en falso
                //cuando el usuario hace click en editar una nota.
                //se almacenan los datos en la respectivas variables
                //y le decimos que el formulario de registro se oculte
                //y se muestre el de editar.
                this.modoEditar = true; 
            },

            /**
             * Creamos el metodo o funcion editarNota
             */

            editarNota() {
                //Ejecutamos el axios con la funcion .put 
                //Pasamos la ruta con el id y enviamos los parametros de la nota a actualizar
                axios.put('/notas/'+this.id, {nombre:this.nombre, descripcion:this.descripcion})
                .then(res => {
                    //Una vez finalizado la respuesta del servidor
                    //Ocultamos el formulario de editar
                    this.modoEditar = false;
                    //Llamamos el metodo getNotas()
                    //Para asi obtener los ultimos registros
                    this.getNotas();
                })
            },

            /**
             * Creamos la funcion eliminarNota
             */

            eliminarNota(nota, index){
                //Creamos una constante donde vamos a mostrar un alerta de confirmacion
                const confirmacion = confirm('Eliminar nota ' + nota.nombre);
                //Si es verdadero
                if(confirmacion){
                    //Llamamos axios y la funcion .delete
                    //Pasamos la ruta y enviamos el parametro id correspondiente a la nota
                    axios.delete('/notas/'+nota.id)
                    .then(()=>{
                        //Llamamos el metodo getNotas()
                        //Para asi obtener los ultimos registros
                        this.getNotas();
                    })
                }
            },

            /**
             * Creamos la funcion de cancelarEdicion
             */

            cancelarEdicion(){
                //Ocultamos el formulario de editar
                this.modoEditar = false;
                //E inicializamos los valores en null 
                this.nombre = '';
                this.descripcion = '';
            }

        }

    }

</script> 