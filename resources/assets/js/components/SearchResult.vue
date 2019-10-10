<template lang="html">
  <div>
    <div class="flex">
      <div class="info box grow flex">
        <i class="fal fa-user fa-2x m-a"></i>
        <div class="box grow flex  column justify-center p-10 ml-15">
          <div class="fs-12 o-080 mt-5">Procedencia</div>
          <div class="fs-17">{{ item.procedencia }}</div>
        </div>
      </div>
    </div>
    <el-divider></el-divider>
    <div class="flex">
      <div class="info box grow flex">
        <i class="fal fa-user fa-2x m-a"></i>
        <div class="box grow flex  column justify-center p-10 ml-15">
          <div class="fs-12 o-080 mt-5">Consignee</div>
          <div class="fs-17">{{ item.consignee }}</div>
        </div>
      </div>
    </div>
    <el-divider></el-divider>
    <div slot="title" class="p-2 flex">
      <div class="info box grow flex">
        <i class="fal fa-box-open fa-2x m-a"></i>
        <div class="box grow flex  column justify-center p-10 ml-15">
          <div class="fs-20">{{ item.name }}</div>
          <div class="fs-14 o-080 mt-5">{{ item.peso }} Lb.</div>
        </div>
      </div>
    </div>
    <br/>
    <div class="pt-10 mt-0">
      <el-collapse v-model="activeCollapse" accordion>
        <el-collapse-item v-for="tracking in trackings" :key="tracking.id" :name="tracking.id">
          <template slot="title">
            <div class="font-weight-800 fs-15">
              <i class="fal fa-truck icon-menu"></i>
              {{ tracking.tracking }}
            </div>
          </template>
          <el-row :gutter="20">
            <el-col :span="12" class="br">
              LLEGADA A LA BODEGA:
              <h3 class="m-0">{{ tracking.create }}</h3>
            </el-col>
            <el-col :span="12">
              CONTENIDO:
              <h3 class="m-0">{{ tracking.content }}</h3>
            </el-col>
          </el-row>
        </el-collapse-item>
      </el-collapse>
    </div>
  </div>
  <!-- <div>
      <h3><i class="fal fa-user icon"></i> {{ item.consignee }}</h3>

      <div class="content-text-search">
        <i class="fal fa-user-tag icon"></i> {{ item.procedencia }}
      </div>
      <div>
        <i class="fal fa-box-open icon fa-2x" style="margin-right: 5px"></i> 
        <span>
        <span class="fs-20">
          {{ item.name }}
        </span>
        <p>
          {{ item.peso }} Lbs
        </p>
        </span>
      </div>
      <div class="content-text-search">
      </div>
      <div class="content-text-search" v-for="i in trackings">
        <i class="fal fa-truck icon"></i> {{ i.tracking }} <i class="fal fa-calendar-alt icon"></i> {{ i.content }} 
      </div>
  </div> -->
</template>

<script>
export default {
  props: ["payload"],
  data() {
    return {
      activeCollapse: "1",
      item: null,
      tracking: [],
      tracking_create: [],
      tracking_content: [],
      trackings: []
    };
  },
  created() {
    this.item = this.payload.datos;

    var trac = this.item.tracking;
    if (trac != "") {
      this.tracking = trac.split(",");
    }
    var trac_create = this.item.tracking_create;
    if (trac_create != "") {
      this.tracking_create = trac_create.split(",");
    }

    var trac_content = this.item.contenido;
    if (trac_content != "") {
      this.tracking_content = trac_content.split(",");
    }

    for (let i = 0; i < this.tracking.length; i++) {
      this.trackings.push({
        tracking: this.tracking[i],
        content: this.tracking_content[i],
        create: this.tracking_create[i]
      });
    }
  },
  methods: {
    trackingInWH(tracking) {
      if (tracking) {
        return tracking.length;
      }
      return 0;
    }
  }
};
</script>

<style lang="css" scoped>
.content-text-search {
  margin: 5px;
}
.fs-20 {
  font-size: 20px;
}
.fs-17 {
  font-size: 17px;
}
.fs-12 {
  font-size: 12px;
}
.flex {
  display: flex;
}
.flex.column {
  flex-direction: column;
}
.ml-15 {
  margin-left: 15px;
}
.mr-15 {
  margin-right: 15px;
}
.m-a {
  margin: auto;
}
</style>
