<template>
    <div class="mb-5">
        <h3>Post </h3>
        <div>
            <div v-if="post" class="card card-header mb-4">
                <h5 class="card-title">#{{post.id}} {{post.subject}} /writed by [{{post.blog.user.username}}]/</h5>
                <div class="card-title">
                    Author <span v-if="post && post.blog.user.username">[{{post.blog.user.username}}]</span> info:
                </div>
                <div v-if="post && post.blog.user.name" class="card-title">name: {{post.blog.user.name}}</div>
                <div v-if="post && post.blog.user.surname" class="card-title">surname: {{post.blog.user.surname}}</div>
                <div v-if="post && post.blog.user.nickname" class="card-title">nickname: *{{post.blog.user.nickname}}*</div>
                <div v-if="post && post.blog.user.email" class="card-title">email: {{post.blog.user.email}}</div>
                <hr>
                <p class="card-title">Body: {{post.body}}</p>
                <hr>
                <div>See all post in <router-link class="text-decoration-underline" :to="{name: 'blog', params:{id: post.blog.id}}"> *{{post.blog.name}}* </router-link> blog</div>
            </div>

            <CommentariesList v-if="post" :post_id="post.id"></CommentariesList>

        </div>

    </div>
</template>

<script>

import CommentariesList from "../Items/CommentariesList";

export default {
    name: "Post",
    components: {
        CommentariesList
    },
    data() {
        return {
            post: null
        };
    },
    methods: {
        async getPost(url = `/api/posts/${this.$route.params.id}`) {
            try {
                const res = await fetch(
                    url,
                    {
                        method: "GET",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if(data.data){
                    this.post = data.data;
                }

            } catch (err) {
                console.log('err', err)
            }
        }
    },
    mounted() {
        this.getPost();
    }
}
</script>
