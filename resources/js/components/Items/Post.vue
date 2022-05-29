<template>
    <div class="card mb-4">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between">
                <div> #{{post.id}}</div>
                <div> ({{post.created_at}})</div>
            </div>
            <div v-if="post.blog && post.blog.user && post.blog.user.email">
                <div class="card-title">Author: {{post.blog.user.email}}</div>
            </div>
            <hr>
            <div class="card-title">
                <div>{{post.subject}}</div>
                <div v-if="post.blog">
                    in <a class="text-decoration-underline" :href="'/blogs/'+post.blog.id">"{{post.blog.name}}"</a> blog
                </div>
            </div>
            <hr>
            <p class="">
                {{post.body.slice(0, 50)}}...
            </p>
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a class="btn btn-info mr-5" :href="'/posts/'+post.id">See full post</a>
                    <div v-if="this.$root.user.is_admin">
                        <form @submit.prevent="removePost(post.id)">
                            <button class="btn btn-danger" type="submit">Remove post</button>
                        </form>
                    </div>
                </div>
                <div>({{post.comments.length}} commentaries)</div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Post",
    props: ['post', 'removePost'],
    methods: {

    }
}


</script>

<style scoped>

</style>
