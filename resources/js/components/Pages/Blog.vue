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

        <h3 v-if="posts.items.length">All post list ({{posts.items.length}})</h3>
        <h3 v-else>Posts not found...</h3>
        <InfiniteScroll :loadItemsFn="getBlogWithPosts" :allItemsLoaded="!posts.loaded">
            <div v-for="post in posts.items" class="">
                <Post v-bind:post="post" :removePost="removePost"/>
            </div>
        </InfiniteScroll>
    </div>
</template>

<script>
import Post from "../Items/Post";
import InfiniteScroll from "../Items/InfiniteScroll";

export default {
    name: "Blog",
    components: {
        Post,
        InfiniteScroll
    },
    data() {
        return {
            blog: null,
            posts: {
                items: [],
                loaded: false
            },
        };
    },
    methods: {
        async getBlogWithPosts(url = "/api/blogs/"+this.$route.params.id+'?skip_posts_count='+this.posts.items.length) {
            if(this.posts.loaded){
                return;
            }
            this.errors = {};
            const { email, password } = this;
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

                if(data.postsData){
                    for(const val of Object.values(data.postsData.data)){
                        this.posts.items.push(val);
                    }
                }

                if(data.allPostsLoaded){
                    this.posts.loaded = true;
                }

                this.posts.page++;

                return {success: true};
            } catch (err) {
                console.log('err', err);
            }
        },

        async removePost(id) {
            this.errors = {};
            try {
                const res = await fetch(
                    "/api/posts/"+id+"/delete",
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if (data.removed) {
                    this.posts.items = this.posts.items.filter(post => post.id !== id);
                    console.log(!!this.$el.lastChild);
                    console.log(this.$root.isElementScrolledToBottomLine(this.$el.lastChild));
                    if(this.$root.isElementScrolledToBottomLine(this.$el.lastChild)){
                        this.getBlogWithPosts();
                    }
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        updatePosts(posts) {
            this.posts.items = posts;
        },
    }
}
</script>
