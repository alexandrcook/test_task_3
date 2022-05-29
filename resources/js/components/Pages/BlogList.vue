<template>
    <div>
        <h3>All blogs list</h3>
        <div v-for="blog in blogs.items" class="">
            <Blog v-bind:blog="blog"/>
        </div>

        <div v-if="!blogs.loaded" v-infinite-scroll="getBlogs" infinite-scroll-disabled="busy" infinite-scroll-distance="1">
            Loading...
        </div>
    </div>
</template>

<script>

import Blog from "../Items/Blog";

export default {
    name: "BlogList",
    components: {
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
        async getBlogs(url = "/api/blogs?skip_blogs_count="+this.blogs.items.length) {
            if(this.blogs.loaded){
                return;
            }
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
                    for(const [key, val] of Object.entries(data.data)){
                        this.blogs.items.push(val);
                    }
                }

                if(data.allBlogsLoaded){
                    this.blogs.loaded = true;
                }

                this.blogs.page++;

            } catch (err) {
                console.log('err', err)
            }
        },
    }
}
</script>
