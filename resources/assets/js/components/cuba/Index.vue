<template>
  <div>
    <el-row class="">
      <el-col :span="24" class="text-center">
        <vue-good-wizard
          :steps="steps"
          :onNext="nextClicked"
          :onBack="backClicked"
          previousStepLabel="Anterior"
          nextStepLabel="Siguiente"
          finalStepLabel="Finalizar"
          ref="my-wizard"
          >
          <div slot="page1">
            <h4 class="mt-5">Elija un servicio</h4>
            <div class="">
              <el-row :gutter="24">
                  <el-col :xs="24" :sm="24" :md="12" :lg="{span: 4, offset: 6}" :xl="6">
                    <div class="category" :class="[category == 1 ? 'activeCategory' : '' ]" @click="setCategory(1)">
                      <div class="category-header">
                        <h3 class="mb-0">PAQUETERÍA</h3>
                        <small>Envíe paquetes a sus familiares en CUBA</small>
                      </div>
                      <i class="fal fa-boxes fa-7x o-020"></i>
                    </div>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="4" :xl="6">
                    <div class="category" :class="[category == 2 ? 'activeCategory' : '' ]" @click="setCategory(2)">
                      <div class="category-header">
                        <h3 class="mb-0">MENAJE</h3>
                        <small>Envíe articulos para el hogar a CUBA</small>
                      </div>
                      <i class="fal fa-couch fa-7x o-020"></i>
                    </div>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="12" :lg="4" :xl="6">
                    <div class="category" :class="[category == 3 ? 'activeCategory' : '' ]" @click="setCategory(3)">
                      <div class="category-header">
                        <h3 class="mb-0">EQUIPAJE NO</h3>
                        <small>acompañado</small>
                      </div>
                      <i class="fal fa-dolly-flatbed-alt fa-7x o-020"></i>
                    </div>
                  </el-col>
              </el-row>
            </div>
          </div>
          <div slot="page2">
            <el-row class="mb-20 pb-20 bb justify-center" :gutter="24">
              <el-col :xs="24" :sm="24" :md="24" :lg="{span: 6, offset: 0}" :xl="{span: 6, offset: 0}">
                <h4 class="mt-5 mb-5">
                  Ingrese su número de identificación
                </h4>
                <el-input placeholder="Cédula o Pasaporte" v-on:keyup.enter="search(doc, type)" v-model="doc" prefix-icon="el-icon-search">
                  <template slot="append" class="">
                    <el-button @click="search(doc, type)" :disabled="doc.length == 0" :loading="loading">
                      <transition name="fade" mode="out-in">
                        <i v-show="!loading" class="fal " :class="iconStatus"></i>
                      </transition>
                    </el-button>
                  </template>
                </el-input>
                <br>
                <br>
                <i class="fal fa-address-card fa-10x o-010 hidden-md-and-down"></i>
              </el-col>
              <el-col :xs="24" :sm="24" :md="24" :lg="{span: 17, offset: 1}" :xl="{span: 17, offset: 1}">
                <transition name="fade" mode="out-in">
                  <el-form v-if="showForm" :model="ruleForm" :rules="rules" ref="ruleForm" class="">
                    <el-row :gutter="10">
                      <small class="text-center">No se ha encontrado un {{ type }} con el documento, puede: </small>
                      <h3 class="text-center mt-5">Crear nuevo</h3>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                        <el-form-item label="" prop="primer_nombre">
                          <el-input v-model="ruleForm.primer_nombre" placeholder="Nombre"></el-input>
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                        <el-form-item label="" prop="primer_apellido">
                          <el-input v-model="ruleForm.primer_apellido" placeholder="Apellidos"></el-input>
                        </el-form-item>
                      </el-col>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="24" :lg="{span: 12, offset: 6}" :xl="12">
                        <el-form-item label="" prop="direccion">
                          <el-input v-model="ruleForm.direccion" placeholder="Dirección"></el-input>
                        </el-form-item>
                      </el-col>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                        <el-form-item label="" prop="localizacion_id">
                          <city-component @get="setCity($event.id)"/>
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                        <el-form-item label="" prop="correo">
                          <el-input v-model="ruleForm.correo" placeholder="Correo" type="email"></el-input>
                        </el-form-item>
                      </el-col>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                        <el-form-item label="" prop="zip">
                          <el-input v-model="ruleForm.zip" placeholder="Código postal"></el-input>
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                        <el-form-item label="" prop="telefono">
                          <el-input v-model="ruleForm.telefono" placeholder="Teléfono"></el-input>
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
                      <h2 class="mb-0" style="text-align: left">
                        {{ data[0].nombre_full }}
                      </h2>
                      <ul class="data-shipper">
                        <li class="mb-10 p-7">
                          <span>
                            Documento:
                          </span>
                          <span class="ml-50">
                            <strong>
                              {{ data[0].documento }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Dirección:
                          </span>
                          <span class="ml-67">
                            <strong>
                              {{ data[0].direccion }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Teléfono:
                          </span>
                          <span class="ml-70">
                            <strong>
                              {{ data[0].telefono }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Correo:
                          </span>
                          <span class="ml-85">
                            <strong>
                              {{ data[0].correo }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Código postal:
                          </span>
                          <span class="ml-35">
                            <strong>
                              {{ data[0].zip }}
                            </strong>
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </transition>
              </el-col>
            </el-row>
          </div>
          <div slot="page3">
            <el-row class="mb-20 pb-20 bb justify-center" :gutter="24">
              <el-col :xs="24" :sm="24" :md="24" :lg="{span: 6, offset: 0}" :xl="{span: 6, offset: 0}">
                <h4 class="mt-5 mb-5">
                  Seleccione el usuario que recibe
                </h4>
                <el-form :inline="true" class="demo-form-inline">
                  <el-form-item>
                    <el-select v-model="value" placeholder="Seleccione consignee" style="width: 100%" filterable @change="setDataC">
                      <el-option
                      v-for="item in options"
                      :key="item.id"
                      :label="item.primer_nombre"
                      :value="item.id">
                      <span class="fl">{{ item.primer_nombre}}  {{ (item.primer_apellido) ? item.primer_apellido : '' }}</span>
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
                <br>
                <br>
                <i class="fal fa-address-card fa-10x o-010 hidden-md-and-down"></i>
              </el-col>
              <el-col :xs="24" :sm="24" :md="24" :lg="{span: 17, offset: 1}" :xl="{span: 17, offset: 1}">
                <transition name="fade" mode="out-in">
                  <el-form v-if="showFormC" :model="ruleFormC" :rules="rules" ref="ruleFormC" class="">
                    <el-row :gutter="10">
                      <small class="text-center">No se ha encontrado un {{ type }} con el documento, puede: </small>
                      <h3 class="text-center mt-5">Crear nuevo</h3>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                        <el-form-item label="" prop="primer_nombre">
                          <el-input v-model="ruleFormC.primer_nombre" placeholder="Nombre"></el-input>
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                        <el-form-item label="" prop="primer_apellido">
                          <el-input v-model="ruleFormC.primer_apellido" placeholder="Apellidos"></el-input>
                        </el-form-item>
                      </el-col>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="24" :lg="{span: 12, offset: 6}" :xl="12">
                        <el-form-item label="" prop="direccion">
                          <el-input v-model="ruleFormC.direccion" placeholder="Dirección"></el-input>
                        </el-form-item>
                      </el-col>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                        <el-form-item label="" prop="localizacion_id">
                          <city-component @get="setCity($event.id)"/>
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                        <el-form-item label="" prop="correo">
                          <el-input v-model="ruleFormC.correo" placeholder="Correo" type="email"></el-input>
                        </el-form-item>
                      </el-col>
                    </el-row>
                    <el-row :gutter="24">
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 6}" :xl="6">
                        <el-form-item label="" prop="zip">
                          <el-input v-model="ruleFormC.zip" placeholder="Código postal"></el-input>
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="24" :md="12" :lg="{span: 6, offset: 0}" :xl="6">
                        <el-form-item label="" prop="telefono">
                          <el-input v-model="ruleFormC.telefono" placeholder="Teléfono"></el-input>
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
                      <h2 class="mb-0" style="text-align: left">
                        {{ data[0].nombre_full }}
                      </h2>
                      <ul class="data-shipper">
                        <li class="mb-10 p-7">
                          <span>
                            Documento:
                          </span>
                          <span class="ml-50">
                            <strong>
                              {{ data[0].documento }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Dirección:
                          </span>
                          <span class="ml-67">
                            <strong>
                              {{ data[0].direccion }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Teléfono:
                          </span>
                          <span class="ml-70">
                            <strong>
                              {{ data[0].telefono }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Correo:
                          </span>
                          <span class="ml-85">
                            <strong>
                              {{ data[0].correo }}
                            </strong>
                          </span>
                        </li>
                        <li class="p-7">
                          <span>
                            Código postal:
                          </span>
                          <span class="ml-35">
                            <strong>
                              {{ data[0].zip }}
                            </strong>
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </transition>
              </el-col>
            </el-row>
          </div>
          <div slot="page4">
            <div class="" v-if="showResult">
              <h1 class="o-010"><strong>{{ warehouse }}</strong></h1>
            </div>
            <div v-else>
              <h4 class="mt-5 mb-5">Seleccione los prductos</h4>
              <el-row :gutter="0">
                <el-form :inline="true" class="demo-form-inline">
                  <el-form-item label="">
                    <el-col :xs="24" :sm="24" :md="24" :lg="{span: 24, offset: 0}" :xl="{span: 12, offset: 6}">
                      <!-- style="width: 100%" -->
                      <el-select
                        v-model="value10"
                        filterable
                        allow-create
                        placeholder="Seleccione producto"
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
                show-summary
                sum-text="Total"
                style="width: 100%; margin: 0 auto">
                <el-table-column
                  prop="product"
                  sortable
                  label="Articulo">
                </el-table-column>
                <el-table-column
                  prop="unm"
                  label="UM"
                  width="100">
                </el-table-column>
                <el-table-column
                  prop="cant"
                  label="Cantidad"
                  width="100">
                </el-table-column>
                <el-table-column
                  prop="points"
                  sortable
                  label="Puntos"
                  width="100">
                </el-table-column>
                <el-table-column
                  prop="address"
                  label=""
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
    <el-dialog
      title="Número de recibo"
      :visible.sync="showResult"
      width="70%"
      :before-close="handleClose"
      center>
      <div class="text-center"  >
        <p>Su número de recibo es:</p>
        <h1>{{ warehouse }}</h1>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="success"  @click="refresh">Aceptar</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>

export default {
  data(){
    return {
      agency_id: null,
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
      category: null,
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
      warehouse: 'Generando su recibo...',
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
  mounted(){
    var pathname = window.location.pathname;
    var data = pathname.split('/');
    this.agency_id = data.pop();
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
        if (this.category == null) {
          this.$message.warning('Elija un servicio')
          return false
        }
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
            axios.post('/shipperSave/' + me.type, me.ruleForm).then(({data}) => {
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
            axios.post('/shipperSave/' + me.type + '/'+ me.data[0].id, me.ruleFormC).then(({data}) => {
              this.$message({
                message: 'Registrado con éxito.',
                type: 'success'
              });
              me.getConsignees()
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
      axios.get('/getConsigneesByShipper/'+ this.data[0].id).then(({data}) => {
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
      axios.get('/getConsigneesById/' + val).then(({data}) => {
        me.showFormC = false
        me.dataC = data
      }).catch(error => {console.log(error);})
    },
    getProductsPoint(){
      let me = this
      axios.get('/getProductsPoint').then(({data}) => {
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
          element.points = (me.value10.valor_aduan) ? (element.cant * parseFloat(me.value10.valor_aduan)) : 0
          return true
        }
        return false
      });

      var data = {
        id: this.value10.id, product: product, cant: this.cantPoint,
        points: (this.value10.valor_aduan) ? (this.cantPoint * parseFloat(this.value10.valor_aduan)) : 0,
        unm: this.value10.descripcion
      }
      if (!found) {
        console.log(found);
        this.tableData.push(data)
      }
      this.cantPoint = 1
      this.value10 = null
    },
    createDocument(){
      this.showResult = true
      let me = this
      axios.post('/documento/ajaxCreatePublic/1', {
        'tipo_documento_id': 1,
        'type_id': 1,
        'agencia_id': me.agency_id,
        'tipo_embarque_id': 8,
        'usuario_id': 1,
        'shipper_id': me.data[0].id,
        'consignee_id': me.dataC.id,
        'created_at': me.getTime(),
        'self_service': true
      }).then(({data}) => {
        let datos = data.datos
        if (data['code'] == 200) {
          axios.post('/saveProductDetail', {
            'documento_id' : datos['id'],
            'dataTable' : me.tableData
          }).then(({data}) => {
            me.warehouse = datos['num_warehouse'];
          }).catch(erro => { console.log(error) })
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
    },
    handleClose(done) {
      this.$confirm('Si cierra esta ventana no podrá ver de nuevo su número de recibo', 'Warning', {
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        type: 'warning'
      }).then(_ => {
        this.refresh()
      }).catch(_ => {});
    },
    setCategory(id){
      this.type = 'shipper'
      this.category = id
      this.$refs['my-wizard'].goNext(true)
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
  text-align: left;
  padding-left: 0px;
  margin-top: 4px;
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
.o-010 { opacity: 0.10; }
.o-020 { opacity: 0.20; }
.p-7 { padding: 7px; padding-left: 0px;}
.p-10 { padding: 10px}
.p-20 { padding: 20px}
.pb-0 { padding-bottom: 0px}
.mb-0 { margin-bottom: 0px}
.ml-0 { margin-left: 0px}
.ml-10 { margin-left: 10px}
.ml-20 { margin-left: 20px}
.ml-40 { margin-left: 40px}
.ml-50 { margin-left: 50px}
.ml-67 { margin-left: 67px}
.ml-70 { margin-left: 70px}
.ml-85 { margin-left: 85px}
.ml-35 { margin-left: 35px}
.activeCategory { background-color: #a9a9a952 }
.category{
  cursor: pointer;
  padding: 10px;
}
.category-header{
  height: 70px;
  margin-bottom: 20px;
}
.category:hover{
  background-color: #a9a9a924;
}

.el-select-dropdown{
  max-width: 600px !important;
}
@media only screen and (max-width: 768px){
  .el-select-dropdown{
    margin-left: 5px !important;
    width: 90% !important;
  }
}
.hidden-md-and-down{
  display: block;
}
@media only screen and (max-width: 768px){
  .hidden-md-and-down{
    display: none;
  }
}
</style>
