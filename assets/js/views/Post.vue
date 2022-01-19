<template>
    <div>
        <div class="row col">
            <h1>Post</h1>
        </div>
        <div class="row col">
            <template v-for="post in posts">
                <div :key="post.id" class="d-flex justify-content-between w-100 p-2">
                    <div>{{ post.message }}</div>
                    <div>{{ post.count }}</div>
                    <button class="btn btn-primary" @click="increaseCount(post)">Increase Count</button>
                </div>
            </template>
        </div>
        <button class="btn btn-primary">Add Post</button>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'post',
        data() {
            return {
                posts: {},
            }
        },
        created() {
            axios.get("http://jobeet4.test/api-posts")
                .then(response => {
                    this.posts = response.data
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        methods: {
            increaseCount(post) {
                axios.post("http://jobeet4.test/api-posts/" + post.id + "/count")
                    .then(response => {
                        const index = this.posts.map(post => post.id).indexOf(post.id);
                        this.$set(this.posts, index, {...this.posts[index], count: post.count + 1});
                        // console.log(post.id)
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            }
        }
    }
</script>