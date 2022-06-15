<template>
    <div>
        <div v-if="blog">
            <div class="card card-header mb-4">
                <div class="card-title d-flex justify-content-between">
                    <div>#{{blog.id}}</div>
                    <div>Created at {{blog.created_at}}</div>
                </div>
                <h5 class="card-title">Blog: {{blog.name}}</h5>
                <div class="card-title">
                    Author <span v-if="blog && blog.user.username">[{{blog.user.username}}]</span> info:
                </div>
                <div v-if="blog && blog.user.name" class="card-title">name: {{blog.user.name}}</div>
                <div v-if="blog && blog.user.surname" class="card-title">surname: {{blog.user.surname}}</div>
                <div v-if="blog && blog.user.nickname" class="card-title">nickname: *{{blog.user.nickname}}*</div>
                <div v-if="blog && blog.user.email" class="card-title">email: {{blog.user.email}}</div>
            </div>
        </div>

        <PostsList v-if="blog" :blog_id="blog.id"></PostsList>
    </div>
</template>

<script>

import PostsList from "../Items/PostsList";

export default {
    name: "Blog",
    components: {
        PostsList
    },
    data() {
        return {
            blog: null
        };
    },
    methods: {
        async getBlog(url = "/api/blogs/"+this.$route.params.id) {
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
                    this.blog = data.data;
                }
            } catch (err) {
                console.log('err', err);
            }
        },
    },
    mounted() {
        this.getBlog();
    }
}
</script>
