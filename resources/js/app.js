require('./bootstrap');

window.Vue = require('vue'); //De esta forma estamos importando la dependencia de Vue.js

//De esta forma estamos haciendo la importacion de nuestro componente
Vue.component('example-component', require('./components/example-component.vue').default);

//Creamos una constante llamada app y le decimos que se igual a una 
//nueva instancia de Vue
const app = new Vue({
    el:"#app"
})


