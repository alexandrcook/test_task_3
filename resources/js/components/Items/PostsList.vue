<template>
    <div>
        <PostCreate :posts="posts" :blog_id="$props.blog_id"></PostCreate>
        <br>
        <h3 v-if="posts.items.length">All posts list ({{posts.items.length}})</h3>
        <h3 v-else>Posts not found...</h3>
        <InfiniteScroll :loadItemsFn="getPosts" :allItemsLoaded="!posts.loaded">
            <div v-for="post in posts.items" class="">
                <Post v-bind:post="post" :removePost="removePost"/>
            </div>
        </InfiniteScroll>
    </div>
</template>

<script>
import InfiniteScroll from "./InfiniteScroll";
import Post from "./Post";
import PostCreate from "./Forms/PostCreate";

export default {
    name: "PostsList",
    components: {
        InfiniteScroll,
        Post,
        PostCreate
    },
    props: ['blog_id'],
    data(){
        return {
            posts: {
                items: [],
                cursor: null,
                loaded: false
            },

        }
    },
    methods: {
        async getPosts() {
            if(this.posts.loaded){
                return;
            }
            let url = `/api/blogs/${this.$props.blog_id}/posts`+ (this.posts.cursor ? `?cursor=${this.posts.cursor}` : '');
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
                    for(const val of Object.values(data.data)){
                        this.posts.items.push(val);
                    }

                    this.posts.cursor = data.meta.next_cursor;
                    if(!data.meta.next_cursor){
                        this.posts.loaded = true;
                    }
                }

                return {success: true};
            } catch (err) {
                console.log('err', err);
            }
        },

        async removePost(id) {
            this.errors = {};
            try {
                const res = await fetch(
                    `/api/posts/${id}/delete`,
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if (data.removed) {
                    this.posts.items = this.posts.items.filter(post => post.id !== id);

                    console.log(this.$el.lastChild);
                    console.log(this.$el.lastChild.lastChild);

                    if(this.$root.isElementScrolledToBottomLine(this.$el.lastChild.lastChild)){
                        this.getPosts();
                    }
                }

            } catch (err) {
                console.log('err', err)
            }
        }
    }
}
</script>

<style scoped>

</style>
