@extends('layouts.app')
@section('title', 'Control Acceso')
@section('breadcrumb')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>
      @lang('general.access_control')
    </h2>
    <ol class="breadcrumb">
      <li>
        <a href="#">
          @lang('general.home')
        </a>
      </li>
      <li class="active">
        <strong>
          @lang('general.access_control')
        </strong>
      </li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<style type="text/css">
  .checkbox {
    margin-top: 0px;
    margin-bottom: 0px;
  }
</style>
<div class="row" id="access_control">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>
          @lang('general.user_access_control')
        </h5>
        <div class="ibox-tools">
        </div>
      </div>
      <div class="ibox-content">
        <!--***** contenido ******-->
        <div class="row">
          <div class="col-lg-7">
            <div class="col-lg-12">
              <div class="row">
                <label for="role_id">
                  Roles
                </label>
                <v-select :filterable="false" :options="roles" label="name" name="role_id" v-model="role_id">
                </v-select>
              </div>
              <div class="row" style="margin-top: 20px;">
                <div class="form-group">
                  <label class="control-label gcore-label-top">
                    @lang('general.special_permission'):
                  </label>
                  <div class="radio radio-info radio-inline">
                    <input checked="" id="inlineRadio1" name="special" type="radio" v-bind:value="null"
                      v-model="special">
                    <label for="inlineRadio1">
                      @lang('general.any')
                    </label>
                    </input>
                  </div>
                  <div class="radio radio-info radio-inline">
                    <input id="inlineRadio2" name="special" type="radio" v-model="special" value="all-access">
                    <label for="inlineRadio2">
                      @lang('general.total_access')
                    </label>
                    </input>
                  </div>
                  <div class="radio radio-info radio-inline">
                    <input id="inlineRadio3" name="special" type="radio" v-model="special" value="no-access">
                    <label for="inlineRadio3">
                      @lang('general.no_access')
                    </label>
                    </input>
                  </div>
                </div>
              </div>
              <div class="row">
                <form v-bind:id="'form_permissions'">
                  <table class="table table-striped table-hover" id="permissions" style="width: 100%;margin-top: 50px;">
                    <thead>
                      <tr>
                        <th>
                          <span>
                            @lang('general.system_modules')
                          </span>
                        </th>
                        <th>
                          <div class="checkbox checkbox-success checkbox-inline chk_all"
                            v-if="role_id != null && special == null">
                            <input @click='checkAll("c")' aria-label="all_c" id="all_c" name="all_c" type="checkbox"
                              v-model="checkAll_c">
                            <label>
                            </label>
                            </input>
                          </div>
                          C
                        </th>
                        <th>
                          <div class="checkbox checkbox-success checkbox-inline chk_all"
                            v-if="role_id != null && special == null">
                            <input @click='checkAll("r")' aria-label="all_r" id="all_r" name="all_r" type="checkbox"
                              v-model="checkAll_r">
                            <label>
                            </label>
                            </input>
                          </div>
                          R
                        </th>
                        <th>
                          <div class="checkbox checkbox-success checkbox-inline chk_all"
                            v-if="role_id != null && special == null">
                            <input @click='checkAll("u")' aria-label="all_u" id="all_u" name="all_u" type="checkbox"
                              v-model="checkAll_u">
                            <label>
                            </label>
                            </input>
                          </div>
                          U
                        </th>
                        <th>
                          <div class="checkbox checkbox-success checkbox-inline chk_all"
                            v-if="role_id != null && special == null">
                            <input @click='checkAll("d")' aria-label="all_d" id="all_d" name="all_d" type="checkbox"
                              v-model="checkAll_d">
                            <label>
                            </label>
                            </input>
                          </div>
                          D
                        </th>
                        <th class="text-center">
                          <i class="fal fa-flash">
                          </i>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="permisionsRole in permisionsRoles">
                        <td>
                          @{{ permisionsRole.module }}
                        </td>
                        <td>
                          <div class="checkbox checkbox-primary">
                            <input @change='updateCheckall("c")' aria-label="c" class="chk_c" name="chk_c"
                              type="checkbox" v-bind:id="'c_' + permisionsRole.c" v-bind:value="permisionsRole.c"
                              v-model="chk_c">
                            <label>
                            </label>
                            </input>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox checkbox-primary">
                            <input @change='updateCheckall("r")' aria-label="r" class="chk_r" name="chk_r"
                              type="checkbox" v-bind:id="'r_' + permisionsRole.r" v-bind:value="permisionsRole.r"
                              v-model="chk_r">
                            <label>
                            </label>
                            </input>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox checkbox-primary">
                            <input @change='updateCheckall("u")' aria-label="u" class="chk_u" name="chk_u"
                              type="checkbox" v-bind:id="'u_' + permisionsRole.u" v-bind:value="permisionsRole.u"
                              v-model="chk_u">
                            <label>
                            </label>
                            </input>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox checkbox-primary">
                            <input @change='updateCheckall("d")' aria-label="d" class="chk_d" name="chk_d"
                              type="checkbox" v-bind:id="'d_' + permisionsRole.d" v-bind:value="permisionsRole.d"
                              v-model="chk_d">
                            <label>
                            </label>
                            </input>
                          </div>
                        </td>
                        <td>
                          <a @click="specialAction(permisionsRole.module)" v-bind:id="'btn_' + permisionsRole.module"
                            class="btn btn-outline btn-default btn-xs" data-toggle="tooltip"
                            title="Permisos adicionales">
                            <i class="fal fa-indent">
                            </i>
                            @{{ permisionsRole.special }}
                          </a>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="6">
                          <button @click="savePermissions()" class="ladda-button btn btn-success pull-right"
                            data-style="expand-right" type="button" v-if="role_id != null">
                            <i class="fal fa-save">
                            </i>
                            @lang('general.save')
                          </button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </form>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <form v-bind:id="'form_permissions_special'">
              <table class="table table-hover table-striped" v-if="permisionsRoles.length > 0">
                <thead>
                  <tr>
                    <td>
                      @lang('general.special_action_for_the_module') <strong>(@{{ name_module }})</strong>
                    </td>
                    <td>
                    </td>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="special in specialPermisions">
                    <td>
                      @{{ special.name }}
                    </td>
                    <td>
                      <div class="checkbox checkbox-info">
                        <input class="chk_special" name="chk_special" type="checkbox" v-bind:id="'sp_' + special.id"
                          v-bind:value="special.id" v-model="chk_special">
                        <label>
                        </label>
                        </input>
                      </div>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="2">
                      <button @click="saveSpecialPermissions()" class="ladda-button btn btn-primary pull-right"
                        data-style="expand-right" type="button" v-if="role_id != null">
                        <i class="fal fa-save">
                        </i>
                        @lang('general.save')
                      </button>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/templates/permissions/accessControl.js') }}">
</script>
@endsection