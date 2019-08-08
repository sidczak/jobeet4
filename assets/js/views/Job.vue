<template>
    <div>
        <div class="row col">
            <h1>Jobs</h1>
        </div>
        <div class="row col">
            <ol>
                <li v-for="job in jobs">{{ job.location }} - {{ job.position }} - {{ job.company }}</li>
            </ol>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'job',
        data() {
            return {
                jobs: {}
            }
        },
        created() {
            axios.get("http://jobeet4.test/api-jobs")
                .then(res => this.jobs = res.data);
        }
    }    
</script>