<template>
    <div>
        <BlogCreate :blogs="blogs"></BlogCreate>

        <h3 v-if="blogs.items.length">All blogs list</h3>
        <h3 v-else>Posts not found...</h3>
        <InfiniteScroll :loadItemsFn="getBlogs" :allItemsLoaded="blogs.loaded">
            <div v-for="blog in blogs.items" class="">
                <Blog v-bind:blog="blog" :onBlogDelete="handleBlogDelete"/>
            </div>
        </InfiniteScroll>
    </div>
</template>

<script>

import Blog from "../Items/Blog";
import InfiniteScroll from "../Items/InfiniteScroll";
import BlogCreate from "../Items/Forms/BlogCreate";

export default {
    name: "BlogList",
    components: {
        InfiniteScroll,
        Blog,
        BlogCreate
    },
    data() {
        return {
            blogs: {
                items: [],
                cursor: null,
                loaded: false
            }
        };
    },
    methods: {
        async getBlogs() {
            if(this.blogs.loaded) return;

            try {
                let url = `/api/blogs`+ (this.blogs.cursor ? `?cursor=${this.blogs.cursor}` : '');
                const res = await fetch(
                    url, {
                        method: "GET",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if(data.data){
                    for(const val of Object.values(data.data)){
                        this.blogs.items.push(val);
                    }
                    this.blogs.cursor = data.meta.next_cursor;
                    if(!data.meta.next_cursor){
                        this.blogs.loaded = true;
                    }
                }

                if(data.allBlogsLoaded){
                    this.blogs.loaded = true;
                }

                return {success: true};
            } catch (err) {
                console.log('err', err)
            }
        },

        handleBlogDelete(){
            if(this.$root.isElementScrolledToBottomLine(this.$el.lastChild.lastChild)){
                this.getBlogs();
            }
        },
    },

}
</script>
