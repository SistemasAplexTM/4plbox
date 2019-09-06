<template>
  <ul class="nav metismenu" id="side-menu">
    <li class="nav-header">
        <div class="dropdown profile-element">
            <span>
                <!-- <img alt="image" class="" id="imgProfile" src="{{ asset('storage/') }}/{{ Session::get('logo') }}" style="width: 170px;height: 60px;background-color: #fff"/> -->
                <img alt="image" class="" id="imgProfile" src="" style="width: 170px;height: 60px;background-color: #fff"/>
            </span>
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold">
                            Auth::user()->name
                        </strong>
                        <br>
                          <strong class="font-bold" id="_agencia">
                              Session::get('agencia')
                          </strong>
                        </br>
                    </span>
                    <span class="text-muted text-xs block">
                      @lang('layouts.welcome')
                      <b class="caret"></b>
                    </span>
                </span>
            </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li>
                    <a href="#">
                    <!-- <a href="{{ route('home') }}"> -->
                      <i class="fal fa-home"></i>
                      @lang('layouts.home')
                    </a>
                </li>
                <li>
                    <a href="#">
                      <i class="fal fa-user"></i>
                      @lang('layouts.profile')
                    </a>
                </li>
            </ul>
        </div>
        <div class="logo-element">
            4plbox
        </div>
    </li>
    <li v-for="item in menu" class="active">
      <a :href="item.route" :style="'background-color: ' + formatMeta(item.meta, 'color')" style="color: white;">
        <i :class="formatMeta(item.meta, 'icon')"></i>
        <span class="nav-label">
          {{ item.name }}
        </span>
        <span class="arrow">
          <i class="fal fa-angle-down"></i>
        </span>
      </a>
      <ul class="nav nav-second-level">
        <li v-for="subItem in item.children">
          <a :href="subItem.route">
            <span :class="formatMeta(subItem.meta, 'icon')"></span>
            {{ subItem.name }}
          </a>
        </li>
      </ul>
    </li>
  </ul>
</template>

<script>
  export default {
    data () {
      return {
        menu: []
      };
    },
    mounted(){
      this.get()
    },
    methods: {
      get () {
        axios.get('getMenu/' + true).then(({data}) => {
          this.menu = data
        }).catch(error => { console.log(error) })
      },
      formatMeta (meta, param) {
        if (meta) {
          var data = JSON.parse(meta)
          return data[param]
        }
      }
    }
  };
</script>
