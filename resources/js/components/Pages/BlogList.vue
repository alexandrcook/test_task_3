<template>
    <div>
        <h3>All blogs list</h3>
        <InfiniteScroll :loadItemsFn="getBlogs" :allItemsLoaded="!blogs.loaded">
            <div v-for="blog in blogs.items" class="">
                <Blog v-bind:blog="blog" :onBlogDelete="handleBlogDelete"/>
            </div>
        </InfiniteScroll>
    </div>
</template>

<script>

import Blog from "../Items/Blog";
import InfiniteScroll from "../Items/InfiniteScroll";

export default {
    name: "BlogList",
    components: {
        InfiniteScroll,
        Blog
    },
    data() {
        return {
            blogs: {
                items: [],
                loaded: false
            },

        };
    },
    methods: {
        async getBlogs() {
            if(this.blogs.loaded){
                return;
            }
            try {
                const res = await fetch(
                    `/api/blogs?skip_blogs_count=${this.blogs.items.length}`,
                    {
                        method: "GET",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if(data.data){
                    for(const val of Object.values(data.data)){
                        this.blogs.items.push(val);
                    }
                }

                if(data.allBlogsLoaded){
                    this.blogs.loaded = true;
                }

                this.blogs.page++;

                return {success: true};
            } catch (err) {
                console.log('err', err)
            }
        },
        handleBlogDelete(){
            if(this.$root.isElementScrolledToBottomLine(this.$el.lastChild)){
                this.getBlogs();
            }
        },
    },

}
</script>
