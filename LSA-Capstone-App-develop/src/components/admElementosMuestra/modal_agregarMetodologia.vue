<template>
  <validation-observer ref="form">
  <b-modal id="modal-Agregar-Metodologia" ref="modal" :title="`Agregar Metodologia`" size="lg">
    <template #modal-header="{ close }">
      <b-row class="d-flex justify-content-around">
        <div class="pl-3">Agregar metodología</div>
      </b-row>
      <button type="button" class="close" aria-label="Close" @click="close()">
        <span aria-hidden="true" style="color:white">&times;</span>
      </button>
    </template>  

    <ValidationProvider name="nombre de la metodología" rules="required" v-slot="validationContext">
      <label for="input-live">Nombre de la metodología:</label>
      <b-form-input id="input-live" v-model="Nombre" :state="getValidationState(validationContext)" placeholder="Ingrese nombre de la metodología." ></b-form-input>
      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
    </ValidationProvider>
    <br/>
    <ValidationProvider name="descripción" rules="max:512" v-slot="validationContext">
      <label for="input-live">Descripción de la metodologia:</label>
      <b-form-textarea  rows="3"
       id="input-live" v-model="Descripción" :state="getValidationState(validationContext)" placeholder="Ingrese descripción (opcional)." ></b-form-textarea>
      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
    </ValidationProvider>    
   <br/>
    <b-row>
      <b-col>
        <b-form-group label="Analista asignado">
          <b-form-select v-model="AnalistaAsignado" :options="opcionesAnalista" placeholder="Seleccione un analista (opcional)." @change="agregarAnalistaSeleccionado"></b-form-select>
        </b-form-group>
      </b-col>
    </b-row>

<b-row v-if="analistasSeleccionados.length > 0" class="mt-3">
  <b-col>
    <b-form-group label="Analista(s) seleccionado(s)">
      <div v-for="(analista, index) in analistasSeleccionados" :key="index" class="d-flex align-items-center parametro-item">
        <b-input readonly :value="analista.nombre + ' ' + analista.apellido"></b-input>
        <b-button variant="danger" @click="eliminarAnalistaSeleccionado(index)" class="ml-2">
          <b-icon-trash-fill></b-icon-trash-fill>
        </b-button>
      </div>
    </b-form-group>
  </b-col>
</b-row>

<b-alert variant="danger" :show="alertaDuplicado" dismissible @dismissed="alertaDuplicado = false">
  El analista ya fue agregado.
</b-alert>

<div class="d-flex justify-content-center">
 
</div>

<template #modal-footer>
  <b-button @click="AgregarMetodologia()" variant="primary" size="xl" class="reactive-button" style="font-weight:bold">
    Agregar metodología
  </b-button>
    </template>
  </b-modal>
</validation-observer>
</template>

<script>
import PersonalService from '@/helpers/api-services/Personal.service';
import ElementosService from '@/helpers/api-services/Elementos.service';

export default {
  data() {
    return {
      
      empleados_eliminar: '',
      Nombre: '',
      Descripción: '',
      AnalistaAsignado: '',
      opcionesAnalista: [],
      analistas: [],
      analistasSeleccionados: [],
      analistaDuplicado: false,
      alertaDuplicado: false,      
      rutEmpleadosSeleccionados: '',
      empleados_agregar: [{rut_empleado: ''}],
      
      
    };
  },

  mounted() {
    PersonalService.obtenerTodosPersonal().then((response) => {
    console.log(response.data);
    if (response.data != null) {
      this.analistas = response.data;
      this.rutEmpleados = this.analistas.map((analista) => analista.rut_empleado);

      this.opcionesAnalista = this.analistas.filter((analista) => {
        return analista.rol === 'Analista Químico' || analista.rol === 'Químico' || analista.rol == 'Supervisor(a)';}).map((analista) => ({
        value: analista,
        text: (analista.nombre + " "+ analista.apellido),
      }));
    }
  })
  .catch((error) => {
    console.error('Error al obtener los analistas:', error);
  });
  },

  methods: {

    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
  
    agregarAnalistaSeleccionado() {
  if (this.AnalistaAsignado) {
    const analistaExistente = this.analistasSeleccionados.find(
      (analista) => analista.nombre === this.AnalistaAsignado.nombre
    );
    if (analistaExistente) {
      this.alertaDuplicado = true;
    } else {
      const analistaSeleccionado = this.analistas.find((analista) => analista.nombre === this.AnalistaAsignado.nombre);
      const rutAnalista = analistaSeleccionado.rut_empleado;
      this.analistasSeleccionados.push({apellido: this.AnalistaAsignado.apellido, nombre: this.AnalistaAsignado.nombre, rut_empleado: rutAnalista });
      this.empleados_agregar.push({ rut_empleado: rutAnalista });
      this.AnalistaAsignado = '';
      this.alertaDuplicado = false;
    }
  }
},

eliminarAnalistaSeleccionado(index) {
  this.empleados_agregar = this.empleados_agregar.filter(emp => emp.rut_empleado != this.analistasSeleccionados[index].rut_empleado)
  this.analistasSeleccionados.splice(index, 1);
  console.log("analista borrado, listado actual: ",this.analistasSeleccionados)
},



    AgregarMetodologia(){

      this.$refs.form.validate().then(success => {
        if(!success) {
          return;
        } else {

          const empleadosFiltrados = this.empleados_agregar.slice(1);

      var data = {

        nombre_metodologia: this.Nombre,        
        detalle_metodologia: this.Descripción,
        empleados_agregar: empleadosFiltrados


      }
      console.log("data a enviar", data)
      ElementosService.agregarMetodología(data).then((response)=>{
        console.log(response)
        if(response != null){
          if (response.status == 200) {
            this.$bvToast.toast(`Creación de la metodología exitosa.`, {
              title: 'Éxito',
              toaster: 'b-toaster-top-center',
              solid: true,
              variant: "success",
              appendToast: true
            })    
            this.$emit('refrescar');   

            this.Nombre = '',
            this.Descripción = '',           
            this.AnalistaAsignado = '',            
            this.analistasSeleccionados = [],
            this.rutEmpleadosSeleccionados = [],
            this.empleados_agregar = [{rut_empleado: '',
                              nombre: ''}],
            

            this.$refs.modal.hide()                         
          }
        } else {
          this.$bvToast.toast(`Error al agregar la metodología.`, {
            title: 'Error',
            toaster: 'b-toaster-top-center',
            solid: true,
            variant: "warning",
            appendToast: true
          })
        }
      });


          






        }


      })
      

      
      
    },
  },
};
</script>