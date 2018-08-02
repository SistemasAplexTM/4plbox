<template>
  <form-wizard @on-complete="onComplete" @on-validate="handleValidation" color="#1ab394" error-color="#ff4949" title="" subtitle="" back-button-text="Anterior" next-button-text="Suguiente" finish-button-text="Terminar">
    <tab-content title="Datos de envío" icon="fa fa-user" :before-change="validateFirstTabS">
      <div class="row">                          
        <div class="col-lg-5 col-lg-offset-1">
          <div class="panel panel-default">
            <div class="panel-heading">
                <div class="checkbox checkbox-primary checkbox-inline pull-right">
                    <input type="checkbox" id="crearS" value="true" v-model="model.crearS">
                    <label for="crearS"> Crear </label>
                </div>
                Shipper's Name and Address
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12">
                  <vue-form-generator :model="model" :schema="firstTabSchemaS" :options="formOptions" ref="firstTabFormS" >
                  </vue-form-generator>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="checkbox checkbox-primary checkbox-inline pull-right">
                    <input type="checkbox" id="crearC" value="true" v-model="model.crearC">
                    <label for="crearC"> Crear </label>
                </div>
                Consignee's Name and Address
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12">
                  <vue-form-generator :model="model" :schema="firstTabSchemaC" :options="formOptions" ref="firstTabFormC" >
                  </vue-form-generator>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </tab-content>
    <tab-content title="Aerolinea" icon="fa fa-plane" :before-change="validateSecondTab">

      <vue-form-generator :model="model" :schema="secondTabSchema" :options="formOptions" ref="secondTabForm">
      </vue-form-generator>
      
    </tab-content>
    <tab-content title="Detalle" icon="fa fa-check">
      <h4>Your json is ready!</h4>
      <div class="panel-body">
        <pre v-if="model" v-html="prettyJSON(model)"></pre>
      </div>
    </tab-content>
  </form-wizard>
</template>
<script>
  import VueFormWizard from 'vue-form-wizard';
  import 'vue-form-wizard/dist/vue-form-wizard.min.css';
  import { validators, component as VueFormGenerator} from "vue-form-generator";
  Vue.use(VueFormWizard);
  Vue.use(VueFormGenerator);
  export default {
    components: { VueFormGenerator },
     data() {
       return {
        model:{
          nombreS:'',
          direccionS:'',
          telefonoS:'',
          ciudadS:'',
          contactoS:'',
          nombreC:'',
          direccionC:'',
          telefonoC:'',
          ciudadC:'',
          contactoC:'',
          city:'',
          country:'',
          crearS: false,
          crearC: false
       },
       formOptions: {
        validationErrorClass: "has-error",
        validationSuccessClass: "",
        validateAfterChanged: true
       },
       firstTabSchemaS:{
         fields:[
         {
            type: "input",
            inputType: "text",
            label: "Nombre",
            model: "nombreS",
            required:true,
            placeholder: "Buscar shipper...",
            validator: validators.string,
            styleClasses:'col-lg-7',
            buttons: [
              {
                classes: "btn btn-xs btn-primary pull-right",
                label: 'Buscar',
                onclick: function(model) {
                  alert("Geolocation is not supported by this browser.");
                }
              }
            ]
         },
          {
            type: "input",
            inputType: "text",
            label: "Teléfono",
            model: "telefonoS",
            required: true,
            disabled: function(model){
              return model.crearS == true ? false : true;
            },
            validator: validators.string,
            styleClasses:'col-lg-5'
         },
         {
            type: "input",
            inputType: "text",
            label: "Dirección",
            model: "direccionS",
            required:true,
            disabled: function(model){
              return model.crearS == true ? false : true;
            },
            validator: validators.string,
            styleClasses:'col-lg-12'
         },
          {
            type: "input",
            inputType: "text",
            label: "Ciudad, Estado, País -zip",
            model: "ciudadS",
            required:true,
            disabled: function(model){
              return model.crearS == true ? false : true;
            },
            validator: validators.string,
            styleClasses:'col-lg-12'
         },
          {
            type: "input",
            inputType: "text",
            label: "Contacto",
            model: "contactoS",
            required:true,
            disabled: function(model){
              return model.crearS == true ? false : true;
            },
            validator: validators.string,
            styleClasses:'col-lg-12'
         }
         ]
       },
       firstTabSchemaC:{
         fields:[
         {
            type: "input",
            inputType: "text",
            label: "Nombre",
            model: "nombreC",
            required:true,
            validator: validators.string,
            styleClasses:'col-lg-7'
          },
          {
            type: "input",
            inputType: "text",
            label: "Teléfono",
            model: "telefonoC",
            required:true,
            disabled: function(model){
              return model.crearC == true ? false : true;
            },
            validator: validators.email,
            styleClasses:'col-lg-5'
         },
         {
            type: "input",
            inputType: "text",
            label: "Dirección",
            model: "direccionC",
            required:true,
            disabled: function(model){
              return model.crearC == true ? false : true;
            },
            validator: validators.string,
            styleClasses:'col-lg-12'
         },
          {
            type: "input",
            inputType: "text",
            label: "Ciudad, Estado, País -zip",
            model: "ciudadC",
            required:true,
            disabled: function(model){
              return model.crearC == true ? false : true;
            },
            validator: validators.string,
            styleClasses:'col-lg-12'
         },
          {
            type: "input",
            inputType: "text",
            label: "Contacto",
            model: "contactoC",
            required:true,
            disabled: function(model){
              return model.crearC == true ? false : true;
            },
            validator: validators.string,
            styleClasses:'col-lg-12'
         }
         ]
       },
       secondTabSchema:{
         fields:[
         {
            type: "input",
            inputType: "text",
            label: "Street name",
            model: "streetName",
            required:true,
            validator: validators.string,
            styleClasses:'col-xs-9'
         },
          {
            type: "input",
            inputType: "text",
            label: "Street number",
            model: "streetNumber",
            required:true,
            validator: validators.string,
            styleClasses:'col-xs-3'
          },
          {
            type: "input",
            inputType: "text",
            label: "City",
            model: "city",
            required:true,
            validator: validators.string,
            styleClasses:'col-xs-6'
          },
          {
            type: "select",
            label: "Country",
            model: "country",
            required:true,
            validator: validators.string,
            values:['United Kingdom','Romania','Germany'],
            styleClasses:'col-xs-6'
          },
         ]
       }
         
        }
       },
       methods: {
         onComplete: function(){
          alert('Yay. Done!');
         },
         validateFirstTabS: function(){
          this.validateFirstTabC();
           return this.$refs.firstTabFormS.validate();
         },
         validateFirstTabC: function(){
           return this.$refs.firstTabFormC.validate();
         },
         validateSecondTab: function(){
           return this.$refs.secondTabForm.validate();
         },
         handleValidation: function(isValid, tabIndex){
             console.log('Tab: '+tabIndex+ ' valid: '+isValid)
         },
         prettyJSON: function(json) {
                  if (json) {
                      json = JSON.stringify(json, undefined, 4);
                      json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
                      return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                          var cls = 'number';
                          if (/^"/.test(match)) {
                              if (/:$/.test(match)) {
                                  cls = 'key';
                              } else {
                                  cls = 'string';
                              }
                          } else if (/true|false/.test(match)) {
                              cls = 'boolean';
                          } else if (/null/.test(match)) {
                              cls = 'null';
                          }
                          return '<span class="' + cls + '">' + match + '</span>';
                      });
                  }
              }
        }
       
  }
</script>