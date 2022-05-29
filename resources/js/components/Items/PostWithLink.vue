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
                <a class="btn btn-info" :href="'/posts/'+post.id">See full post</a>
                <div>({{post.comments.length}} commentaries)</div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Post",
    props: ['post']
}


</script>

<style scoped>

</style>
