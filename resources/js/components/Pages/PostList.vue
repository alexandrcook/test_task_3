<template>
    <div class="">
        <h3>All post list</h3>
        <div v-for="post in posts.items" class="">
            <Post v-bind:post="post" :removePost="removePost"/>
        </div>

        <div v-if="!posts.loaded"
             v-infinite-scroll="outsidePosts ? loadPosts : getPosts"
             infinite-scroll-disabled="busy"
             infinite-scroll-distance="1"
        >
            Loading...
        </div>
    </div>
</template>

<script>
import Post from '../Items/Post'
export default {
    name: "PostList",
    components: {
      Post
    },
    props: ['outsidePosts', 'updatePosts', 'loadPosts'],
    data() {
        return {
            posts: {
                items: [],
                loaded: false
            },

        };
    },
    methods: {
        async getPosts(url = "/api/posts?skip_posts_count="+this.posts.items.length) {
            if(this.posts.loaded){
                return;
            }
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

                for(const [key, val] of Object.entries(data.data)){
                    this.posts.items.push(val);
                }

                if(data.allPostsLoaded){
                    this.posts.loaded = true;
                }

            } catch (err) {
                console.log('err', err)
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

                if (data.errors) {
                    this.errors = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
                }

                if (data.removed) {
                    const updatedItems = this.posts.items.filter(post => post.id !== id);
                    this.posts.items = updatedItems;
                    if (this.$props.updatePosts) {
                        this.$props.updatePosts(updatedItems);
                    }
                }

            } catch (err) {
                console.log('err', err)
            }
        }
    },
    mounted() {
        if(this.$props.outsidePosts){
            this.posts = this.$props.outsidePosts;
        }
    }
}
</script>
