<template>
  <b-modal id="modal-detalle-elementos" ref="modal" :title="`Detalles Elementos`" size="xl">
    <template #modal-header="{ close }">
      <b-row class="d-flex justify-content-around">
        <div class="pl-3">Detalles del elemento</div>
      </b-row>

      <button type="button" class="close" aria-label="Close" @click="close()">
        <span aria-hidden="true" style="color:white">&times;</span>
      </button>
    </template>


    <b-list-group>
      
      <b-list-group-item style="border-radius: 20px;">
        <strong>Matriz: </strong> {{ " " + nombre_matriz }}
      </b-list-group-item>
      <br />
      <b-list-group-item v-if="cargandoParametros" class="d-flex align-items-center justify-content-center lsa-orange-text" style="height:250px">
                    <div>
                        <b-spinner class="align-middle"></b-spinner>
                        <strong> Cargando...</strong>
                    </div>
                </b-list-group-item>
      <b-list-group-item class="p-0" v-for="parametro in parametros" :key="parametro.id_parametro">
        <b-list-group>
          <b-list-group-item>
            <b-col>
              <b-row class="justify-content-between align-items-center">
                <div>
                  <strong>Parámetro:</strong> {{ " " + parametro.nombre_parametro }}
                </div>
                <b-button pill class="lsa-blue reactive-button" v-b-toggle="(parametro.id_parametro).toString()">Detalles

                  <b-icon icon="journals" aria-hidden="true"></b-icon></b-button>
              </b-row>
            </b-col>
          </b-list-group-item>

          <b-collapse :id="(parametro.id_parametro).toString()" style="padding:0px; margin:0px">

            <b-list-group-item class="p-0">

              <b-list-group>
              
                <b-list-group-item v-for="metodo in parametro.metodologias"
                  :key="metodo.id_metodologia + parametro.id_parametro" class="p-0 d-flex justify-content-between">
                  <b-list-group horizontal style="width:100%">
                    <b-list-group-item style="width:35%;border:none" class="p-0">
                      <b-list-group style="height: 100%;border:none">
                        <b-list-group-item>
                          <strong>Metodología</strong>
                        </b-list-group-item>
                        <b-list-group-item class="d-flex align-items-center justify-content-between"
                          style="height: 100%; border:none">

                          <span> {{ metodo.nombre_metodologia }}</span>

                          <b-popover placement="lefttop"
                            :target="'button-' + metodo.id_metodologia + '-' + parametro.id_parametro"
                            title="Descripción metodología" triggers="focus">

                            <template v-if="metodo.detalle_metodologia != null">{{ metodo.detalle_metodologia
                            }}</template>
                            <template v-else>
                              <div>La metodología no cuenta con una descripción actualmente.</div>
                            </template>
                          </b-popover>
                          <b-button class="boton-ojo-metodo"
                            :id="'button-' + metodo.id_metodologia + '-' + parametro.id_parametro">
                            <b-icon scale="0.9" icon="eye-fill" style="color:gray"></b-icon>
                          </b-button>

                        </b-list-group-item>
                      </b-list-group>
                    </b-list-group-item>



                    <b-list-group-item class="p-0" style="width:65%; border:none">
                      <b-list-group>
                        <b-list-group-item>
                          <strong>Analistas: </strong>
                        </b-list-group-item>
                        <b-list-group-item v-for="analista in metodo.analistas" :key="analista.rut_empleado" class="p-1">
                          {{ analista.rut_empleado + " - " + analista.nombre + " " + analista.apellido }}
                        </b-list-group-item>
                      </b-list-group>

                    </b-list-group-item>

                  </b-list-group>


                </b-list-group-item>
              </b-list-group>



            </b-list-group-item>
            <br />
          </b-collapse>
        </b-list-group>
      
      </b-list-group-item>
    </b-list-group>


    <template #modal-footer="{ close }">
      <b-button @click="close()" variant="primary" size="xl" class="float-right reactive-button" style="font-weight:bold">
        Cerrar
      </b-button>
    </template>
  </b-modal>
</template>

  
<script>
import ElementosService from '@/helpers/api-services/Elementos.service';

export default {
  props: {
    detallesData: Object
  },
  data() {
    return {
      Nombre: '',
      nombre_matriz: '',
      matriz: {
        nombre_matriz: ""
      },
      id: '',
      parametros: [],
      cargandoParametros: false,


    }
  },

  methods: {
    getColor(empleado) {
      const repetidas = this.listaEmpleados.filter(e => e.metodologia === empleado.metodologia);
      if (repetidas.length > 1) {
        return 'red'; // Cambiar el color a rojo si la metodología está repetida
      } else {
        return 'green'; // Mantener el color verde si la metodología no está repetida
      }
    },
    obtenerDetallesElementos() {
      this.parametros = [];
      this.cargandoParametros = true;
      const data = {
        id_matriz: this.id
      };

      ElementosService.obtenerDetallesElementos(data).then((response) => {
        
        if (response.status === 200) {

          const detalles = response.data;

          for (var i = 0; i < detalles.length; i++) {
            const existeParametro = this.parametros.find(param => param.id_parametro == detalles[i].id_parametro);
            if (existeParametro == null) {
              const parametroNuevo = {
                id_parametro: detalles[i].id_parametro,
                nombre_parametro: detalles[i].nombre_parametro,
                metodologias: []
              }

              detalles[i].metodologias.forEach(metodo => {
                parametroNuevo.metodologias.push({
                  id_metodologia: metodo.id_metodologia,
                  nombre_metodologia: metodo.nombre_metodologia,
                  detalle_metodologia: metodo.detalle_metodologia,
                  analistas: metodo.empleados
                })


              });
              this.parametros.push(parametroNuevo)

            }

          }



          this.cargandoParametros = false;
        }
      });
    }
  },

  watch: {
    detallesData: {
      handler() {
        this.nombre_matriz = this.detallesData.nombre_matriz;
        this.id = this.detallesData.id_matriz;
        console.log("id es: ", this.id)

        this.obtenerDetallesElementos();
      },
      deep: true
    }
  }
}
</script>
  