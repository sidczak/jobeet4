<template>
    <div>
        <div class="row col">
            <h1>Post</h1>
        </div>
        <div class="row col">
            <ol>
                <li v-for="post in posts">{{ post.message }}</li>
            </ol>
        </div>
        <!-- <button class="btn btn-primary">Add Post</button> -->
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'post',
        data() {
            return {
                posts: {}
            }
        },
        created() {
            axios.get("http://jobeet4.test/posts")
                .then(res => this.posts = res.data);
        }
    }    
</script>