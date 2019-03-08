<template>
  <!-- <vue-scroll class=""> -->
    <el-row class="">
      <el-col :span="24" class="text-center">
        <vue-good-wizard
          :steps="steps"
          :onNext="nextClicked"
          :onBack="backClicked"
          previousStepLabel="Anterior"
          nextStepLabel="Siguiente"
          finalStepLabel="Finalizar"
          >
          <div slot="page1">
            <h4 class="mt-5">Seleccione la categoría de envío</h4>
            <p>Acá va una descripción acerca de lo que es una categoría de envío.</p>
            <div class="">
              <el-radio v-model="category" label="200" border>Paquetería (200 pts)</el-radio>
              <el-radio v-model="category" label="0" border>Menaje (No tiene puntos límites)</el-radio>
              <el-radio v-model="category" label="950" border>Equipaje no acompañado (950 Pts)</el-radio>
            </div>
          </div>
          <div slot="page2">
            <!-- <shipper-consignee type="shipper" @data="shipper_id = $event"></shipper-consignee> -->
            <h4 class="mt-5 mb-5">
              Ingrese número de identificación
            </h4>
            <small>Acá va una descripción acerca de lo que es el usuario que envía.</small>
            <el-row class="mb-20 pb-20 bb justify-center" :gutter="24">
              <el-col :xs="24" :sm="24" :md="24" :lg="{span: 12, offset: 6}" :xl="{span: 12, offset: 6}">
                <el-input placeholder="Cédula o Pasaporte" v-model="doc" prefix-icon="el-icon-search">
                  <template slot="append" class="">
                    <el-button @click="search(doc, type)" :disabled="doc.length == 0" :loading="loading">
                      <transition name="fade" mode="out-in">
                        <i v-show="!loading" class="fal " :class="iconStatus"></i>
                      </transition>
                    </el-button>
                  </template>
                </el-input>
              </el-col>
            </el-row>
            <transition name="fade" mode="out-in">
              <el-form v-if="showForm" :model="ruleForm" :rules="rules" ref="ruleForm" class="">
                <el-row :gutter="10">
                    <small class="text-center">No se ha encontrado un {{ type }} con el documento, puede: </small>
                    <h3 class="text-center mt-5">Crear nuevo</h3>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                    <el-form-item label="Nombre" prop="primer_nombre">
                      <el-input v-model="ruleForm.primer_nombre"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                    <el-form-item label="Apellidos" prop="primer_apellido">
                      <el-input v-model="ruleForm.primer_apellido"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="24" :lg="{span: 12, offset: 6}" :xl="12">
                    <el-form-item label="Dirección" prop="direccion">
                      <el-input v-model="ruleForm.direccion"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                    <el-form-item label="Ciudad" prop="localizacion_id">
                      <city-component @get="setCity($event.id)"/>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                    <el-form-item label="Correo" prop="correo">
                      <el-input v-model="ruleForm.correo" type="email"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                    <el-form-item label="Código postal" prop="zip">
                      <el-input v-model="ruleForm.zip"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                    <el-form-item label="Teléfono" prop="telefono">
                      <el-input v-model="ruleForm.telefono"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-form-item>
                  <el-button type="primary" @click="submitForm('ruleForm')" :loading="loadingSave">Crear</el-button>
                  <el-button @click="resetForm('ruleForm')">Resetear</el-button>
                </el-form-item>
              </el-form>
              <div v-else>
                <div class="text-center o-020 p-20" v-if="data.length <= 0">
                  <i class="fal fa-search fa-7x"></i>
                  <h4>Buscar...</h4>
                </div>
                <div class="" v-else>
                  <h3 class="h-big mb-0">
                    {{ data[0].primer_nombre }} {{ (data[0].primer_apellido) ? data[0].primer_apellido : '' }}
                  </h3>
                  <ul class="data-shipper">
                    <li>{{ data[0].documento }}</li>
                    <li>{{ data[0].nombre }}</li>
                    <li>{{ data[0].direccion }}</li>
                    <li>{{ data[0].telefono }}</li>
                    <li>{{ data[0].correo }}</li>
                    <li>{{ data[0].zip }}</li>
                  </ul>
                </div>
              </div>
            </transition>
          </div>
          <div slot="page3">
            <h4 class="mt-5 mb-5">
              Seleccione el usuario que recibe
            </h4>
            <small>Acá va una descripción acerca de lo que es el usuario que recibe.</small>
            <el-row class="mb-20 pb-20 bb justify-center" :gutter="24">
              <el-col :xs="24" :sm="24" :md="24" :lg="{span: 12, offset: 6}" :xl="{span: 12, offset: 6}">
                <el-form :inline="true" class="demo-form-inline">
                  <el-form-item>
                    <el-select v-model="value" placeholder="Seleccione consignee" style="width: 100%" filterable @change="setDataC">
                      <el-option
                      v-for="item in options"
                      :key="item.id"
                      :label="item.primer_nombre"
                      :value="item.id">
                      <span class="fl">{{ item.primer_nombre  + ' ' + item.primer_apellido}}</span>
                      <span class="fr">{{ item.documento }}</span>
                    </el-option>
                  </el-select>
                  </el-form-item>
                  <el-form-item>
                    <el-button @click="showFormC = true; value = null">
                      <transition name="fade" mode="out-in">
                        <i class="fal fa-plus"></i>
                      </transition>
                    </el-button>
                  </el-form-item>
                </el-form>
              </el-col>
            </el-row>
            <transition name="fade" mode="out-in">
              <el-form v-if="showFormC" :model="ruleFormC" :rules="rules" ref="ruleFormC" class="">
                <el-row :gutter="10">
                    <small class="text-center">No se ha encontrado un {{ type }} con el documento, puede: </small>
                    <h3 class="text-center mt-5">Crear nuevo</h3>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                    <el-form-item label="Nombre" prop="primer_nombre">
                      <el-input v-model="ruleFormC.primer_nombre"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                    <el-form-item label="Apellidos" prop="primer_apellido">
                      <el-input v-model="ruleFormC.primer_apellido"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="24" :lg="{span: 12, offset: 6}" :xl="12">
                    <el-form-item label="Dirección" prop="direccion">
                      <el-input v-model="ruleFormC.direccion"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                    <el-form-item label="Ciudad" prop="localizacion_id">
                      <city-component @get="setCityC($event.id)"/>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                    <el-form-item label="Correo" prop="correo">
                      <el-input v-model="ruleFormC.correo" type="email"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                    <el-form-item label="Código postal" prop="zip">
                      <el-input v-model="ruleFormC.zip"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                    <el-form-item label="Teléfono" prop="telefono">
                      <el-input v-model="ruleFormC.telefono"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-form-item>
                  <el-button type="primary" @click="submitForm('ruleFormC')" :loading="loadingSave">Crear</el-button>
                  <el-button @click="resetForm('ruleFormC')">Resetear</el-button>
                </el-form-item>
              </el-form>
              <div v-else>
                <div class="text-center o-020 p-20" v-if="dataC.length <= 0">
                  <i class="fal fa-search fa-7x"></i>
                  <h4>Selecciona o crea un consignatario</h4>
                </div>
                <div class="" v-else>
                  <h3 class="h-big mb-0">
                    {{ dataC.primer_nombre }} {{ (dataC.primer_apellido) ? dataC.primer_apellido : '' }}
                  </h3>
                  <ul class="data-shipper">
                    <li>{{ dataC.documento }}</li>
                    <li>{{ dataC.nombre }}</li>
                    <li>{{ dataC.direccion }}</li>
                    <li>{{ dataC.telefono }}</li>
                    <li>{{ dataC.correo }}</li>
                    <li>{{ dataC.zip }}</li>
                  </ul>
                </div>
              </div>
            </transition>
          </div>
          <div slot="page4">
            <div class="" v-if="showResult">
              <h1>Su número de recibo es: <strong>{{ warehouse }}</strong></h1>
              <el-button type="success"  @click="refresh">Aceptar</el-button>
            </div>
            <div v-else>
              <h4 class="mt-5 mb-5">Seleccione los prductos</h4>
              <p>Acá va una descripción acerca de lo que son los productos.</p>
              <el-row :gutter="14">
                <el-form :inline="true" class="demo-form-inline">
                  <el-form-item label="" style="width: 50%">
                    <el-col :xs="24" :sm="24" :md="24" :lg="{span: 24, offset: 0}" :xl="{span: 12, offset: 6}">
                      <el-select
                        style="width: 100%"
                        v-model="value10"
                        filterable
                        allow-create
                        placeholder="Choose tags for your article"
                        value-key="id">
                        <el-option
                        v-for="item in productPoint"
                        :key="item.id"
                        :label="item.articulo"
                        :value="item">
                      </el-option>
                    </el-select>
                  </el-col>
                </el-form-item>
                <el-form-item label="">
                  <el-input-number v-model="cantPoint" :min="1"></el-input-number>
                </el-form-item>
                <el-form-item label="">
                  <el-button type="success" @click="addPoints"><i class="fal fa-plus"></i></el-button>
                </el-form-item>
              </el-form>
            </el-row>
          <el-table
                      :data="tableData"
                      border
                      style="width: 70%; margin: 0 auto">
                      <el-table-column
                      prop="product"
                      label="Articulo">
                    </el-table-column>
                    <el-table-column
                    prop="cant"
                    label="Cantidad"
                    width="100">
                  </el-table-column>
                  <el-table-column
                  prop="address"
                  label="Acciones"
                  width="100">
                  <template slot-scope="scope">
                    <i class="fal fa-trash-alt danger-text pointer" @click="tableData.splice(scope.$index, 1);"></i>
                  </template>
                </el-table-column>
              </el-table>

            </div>
          </div>
        </vue-good-wizard>
      </el-col>
    </el-row>
  <!-- </vue-scroll> -->
</template>

<script>

export default {
  data(){
    return {
      tableData: [],
      type: '',
      steps: [
        {
          label: 'Categoría',
          slot: 'page1',
        },
        {
          label: 'Remitente',
          slot: 'page2',
        },
        {
          label: 'Consignatario',
          slot: 'page3',
        },
        {
          label: 'Productos',
          slot: 'page4',
        }
      ],
      category: '0',
      shipper_id: '',
      consignee_id: '',
      doc: '',
      loading: false,
      loadingSave: false,
      data: [],
      dataC: [],
      productPoint: [],
      cantPoint: 1,
      showForm: false,
      showFormC: false,
      showResult: false,
      warehouse: null,
      iconStatus: 'fa-search',
      ruleForm: {
        agencia_id: 1,
        primer_nombre: '',
        primer_apellido: '',
        direccion: '',
        telefono: '',
        localizacion_id: '',
        zip: '',
        correo: ''
      },
      ruleFormC: {
        agencia_id: 1,
        primer_nombre: '',
        primer_apellido: '',
        direccion: '',
        telefono: '',
        localizacion_id: '',
        zip: '',
        correo: ''
      },
      rules: {
        primer_nombre: [
          { required: true, message: 'Este campo es obligatorio', trigger: 'blur' }
        ],
        primer_apellido: [
          { required: true, message: 'Este campo es obligatorio', trigger: 'blur' }
        ],
        localizacion_id: [
          { required: true, message: 'Este campo es obligatorio', trigger: 'blur' }
        ],
        direccion: [
          { required: true, message: 'Este campo es obligatorio', trigger: 'blur' }
        ],
      },
      options: [],
      value: '',
      value10: ''
    };
  },
  methods: {
    nextClicked(currentPage) {
      if (currentPage == 3) {
        this.createDocument()
      }
      if (currentPage == 2) {
        if (this.dataC.length <= 0) {
          this.$message.warning('Seleccione un consignatario')
          return false
        }
        this.getProductsPoint()
      }
      if (currentPage == 0) {
        this.type = 'shipper'
      }
      if (currentPage == 1) {
        if (this.data.length <= 0) {
          this.$message.warning('Seleccione un remitente')
          return false
        }
        this.type = 'consignee'
        this.getConsignees()
      }
      return true; //return false if you want to prevent moving to next page
    },
    backClicked(currentPage) {
      if (currentPage == 2) {
        this.type = 'shipper'
      }
      return true; //return false if you want to prevent moving to previous page
    },
    search(doc, type){
      let me = this
      me.loading = true
      axios.get('/shipperSearch/' + doc + '/' + type).then(({data}) => {
        if (data.length > 0) {
          me.iconStatus = 'fa-check'
          me.data = data
          me.showForm = false
          me.$emit('data', data)
        }else{
          me.iconStatus = 'fa-search'
          me.data = []
          me.$emit('data', [])
          me.showForm = true
        }
        me.loading = false
      }).catch(error => {
        console.log(error);
        me.loading = false
      })
    },
    submitForm(formName) {
      let me = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          me.loadingSave = true
          me.ruleForm.documento = me.doc
          if (me.type == 'shipper') {
            save(me.ruleForm, me.type).then(({data}) => {
              this.$message({
                message: 'Registrado con éxito.',
                type: 'success'
              });
              me.search(me.doc, me.type)
              me.loadingSave = false
            }).catch(error => {
              console.log(error);
              me.loadingSave = false
            })
          }else{
            save(me.ruleFormC, me.type).then(({data}) => {
              this.$message({
                message: 'Registrado con éxito.',
                type: 'success'
              });
              me.loadingSave = false
            }).catch(error => {
              console.log(error);
              me.loadingSave = false
            })
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    setCity(city){
      this.ruleForm.localizacion_id = city
    },
    setCityC(city){
      this.ruleFormC.localizacion_id = city
    },
    getConsignees(){
      axios.get('getConsigneesByShipper/'+ this.data[0].id).then(({data}) => {
        this.options = []
        if (data.length > 0) {
          this.options = data
        }
      }).catch(error => {
        console.log(error);
      })
    },
    setDataC(val){
      let me = this
      axios.get('getConsigneesById/' + val).then(({data}) => {
        me.showFormC = false
        me.dataC = data
      }).catch(error => {console.log(error);})
    },
    getProductsPoint(){
      let me = this
      axios.get('getProductsPoint').then(({data}) => {
        me.productPoint = data
      }).catch(error => { console.log(error) })
    },
    addPoints(){
      let me = this
      if (this.value10 == null || this.value10 == '') {
        this.$message.warning('Seleccione un articulo')
        return false
      }
      var product = this.value10.articulo
      if (!product) {
          product = this.value10
      }

      var found = this.tableData.find(function(element) {
        if (element.product == product) {
          element.cant += me.cantPoint
          return true
        }
        return false
      });

      var data = {id: this.value10.id, product: product, cant: this.cantPoint }
      if (!found) {
        console.log(found);
        this.tableData.push(data)
      }
      this.cantPoint = 1
      this.value10 = null
    },
    createDocument(){
      let me = this
      axios.post('documento/ajaxCreatePublic/1', {
        'tipo_documento_id': 1,
        'type_id': 1,
        'agencia_id': 1,
        'usuario_id': 1,
        'shipper_id': me.data[0].id,
        'consignee_id': me.dataC.id,
        'created_at': me.getTime()
      }).then(({data}) => {
        if (data['code'] == 200) {
          me.warehouse = data.datos['num_warehouse'];
          this.showResult = true
        } else {
          this.$message.warning(data['error']);
        }
      }).catch(function(error) {
        console.log(error);
        toastr.error("Error.", {
          timeOut: 50000
        });
      });
    },
    getTime() {
      Number.prototype.padLeft = function(base, chr) {
          var len = (String(base || 10).length - String(this).length) + 1;
          return len > 0 ? new Array(len).join(chr || '0') + this : this;
      }
      var d = new Date,
        dformat = [d.getFullYear(), (d.getMonth() + 1).padLeft(),
            d.getDate().padLeft()
        ].join('-') + ' ' + [d.getHours().padLeft(),
            d.getMinutes().padLeft(),
            d.getSeconds().padLeft()
        ].join(':');
      return dformat;
    },
    refresh(){
      location.reload(true)
    }
  }
};
</script>

<style>
@import '../../../fonts/Exo/Exo.css';

html, body {
	font-family: 'Exo', sans-serif;
}

.wizard__step{
  height: 55px !important;
}
.wizard__body{
    border: 1px solid #dcdfe6 !important;
}
.data-shipper{
  list-style: none;
}
.el-form-item__content{
  width: 100%;
}
.pointer{
  cursor: pointer;
}
.danger-text{
  color:  #ec205f;
}
.text-center{
  text-align: center;
}
.o-0 { opacity: 0; }
.o-005 { opacity: 0.05; }
.o-010 { opacity: 0.10; }
.o-015 { opacity: 0.15; }
.o-020 { opacity: 0.20; }
.o-025 { opacity: 0.25; }
.o-030 { opacity: 0.30; }
.o-035 { opacity: 0.35; }
.o-040 { opacity: 0.40; }
.o-045 { opacity: 0.45; }
.o-050 { opacity: 0.50; }
.o-055 { opacity: 0.55; }
.o-060 { opacity: 0.60; }
.o-065 { opacity: 0.65; }
.o-070 { opacity: 0.70; }
.o-075 { opacity: 0.75; }
.o-080 { opacity: 0.80; }
.o-085 { opacity: 0.85; }
.o-090 { opacity: 0.90; }
.o-095 { opacity: 0.95; }
.o-1 { opacity: 1; }
// Padding / Margin helpers
.no-p { padding: 0 !important; }
.no-m { margin: 0 !important; }


</style>
