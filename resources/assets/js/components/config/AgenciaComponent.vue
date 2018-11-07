<template>
    <div class="ibox">
        <div class="view_content">
            <span class="text-muted small pull-right">
                Last modification:
                <i class="fa fa-clock-o">
                </i>
                2:10 pm - 12.06.2014
            </span>
            <h2>
                Clients
            </h2>
            <p>
                All clients need to be verified before you can send email and set a project.
            </p>
            <div class="input-group">
                <input class="input form-control" placeholder="Search client " type="text">
                    <span class="input-group-btn">
                        <button class="btn btn btn-primary" type="button">
                            <i class="fa fa-search">
                            </i>
                            Search
                        </button>
                    </span>
                </input>
            </div>
            <div class="clients-list">
                <ul class="nav nav-tabs">
                    <span class="pull-right small text-muted">
                        1406 Elements
                    </span>
                    <li class="active">
                        <a data-toggle="tab" href="#tab-1">
                            <i class="fa fa-user">
                            </i>
                            Contacts
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#tab-2">
                            <i class="fa fa-briefcase">
                            </i>
                            Companies
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-1">
                    </div>
                    <div class="tab-pane" id="tab-2">
                        <div class="full-height-scroll">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
