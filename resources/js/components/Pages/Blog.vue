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

        <PostList :outsidePosts="posts" :updatePosts="updatePosts" :loadPosts="getBlog"/>
    </div>
</template>

<script>
import PostList from "./PostList";
export default {
    name: "Blog",
    components: {
        PostList
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
        async getBlog(url = "/api/blogs/"+this.$route.params.id+'?skip_posts_count='+this.posts.items.length) {
            console.log(1111)
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

                if(data.status === 'error'){
                    this.errors = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
                }

                if(data.data){
                    console.log(data.data);
                    this.blog = data.data;
                }

                if(data.postsData){
                    for(const [key, val] of Object.entries(data.postsData.data)){
                        this.posts.items.push(val);
                    }
                }

                if(data.allPostsLoaded){
                    this.posts.loaded = true;
                }

                this.posts.page++;

            } catch (err) {
                console.log('err', err)
            }
        },

        updatePosts(posts) {
            this.posts.items = posts;
        }
    },
    mounted() {
        // this.getBlog();
    }
}
</script>
