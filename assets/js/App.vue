<template>
    <div>
        <h1>Hello World</h1>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" exact active-class="active">Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/post" class="nav-link" active-class="active">Post</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/job" class="nav-link" active-class="active">Job</router-link>
                    </li>
                </ul>
            </div>
        </div>

        <router-view></router-view>
    </div>
</template>

<script>
    import router from './router/router';
    export default {
        name: 'App',
        router
    }
</script>