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
                    in <router-link class="text-decoration-underline" :to="{name: 'blog', params: {id: post.blog.id}}">"{{post.blog.name}}"</router-link> blog
                </div>
            </div>
            <hr>
            <p class="">
                {{post.body.slice(0, 50)}}...
            </p>
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <router-link class="btn btn-info mr-5" :to="{name: 'post', params: {blog_id: $route.params.id, id: post.id}}">See full post</router-link>
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
    props: ['post', 'removePost']
}
</script>
