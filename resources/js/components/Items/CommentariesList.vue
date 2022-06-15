<template>
    <div>
        <CommentCreate :comments="comments" :post_id="$props.post_id"></CommentCreate>
        <br>
        <h3 v-if="comments.items.length">All comments list ({{comments.items.length}})</h3>
        <h3 v-else>Comments not found...</h3>

        <InfiniteScroll :loadItemsFn="getCommentries" :allItemsLoaded="!comments.loaded">
            <div v-for="comment in comments.items" class="">
                <Commentary v-bind:comment="comment" :removeCommentary="removeCommentary"></Commentary>
            </div>
        </InfiniteScroll>
    </div>
</template>

<script>
import InfiniteScroll from "./InfiniteScroll";
import Commentary from "./Commentary";
import CommentCreate from "./Forms/CommentCreate";

export default {
    name: "CommentariesList",
    components: {
        Commentary, InfiniteScroll, CommentCreate
    },
    props: ['post_id'],
    data(){
        return {
            comments: {
                items: [],
                cursor: null,
                loaded: false
            }
        }
    },
    methods:{

        async getCommentries(url = `/api/posts/${this.$props.post_id}/comments`+ (this.comments.cursor ? `?cursor=${this.comments.cursor}` : '')) {
            if(this.comments.loaded){
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
                    for(const val of Object.values(data.data)){
                        this.comments.items.push(val);
                    }

                    this.comments.cursor = data.meta.next_cursor;
                    if(!data.meta.next_cursor){
                        this.comments.loaded = true;
                    }
                }

            } catch (err) {
                console.log('err', err)
            }
        },
        async removeCommentary(id) {
            this.errors = {};
            try {
                const res = await fetch(
                    "/api/comments/"+id+"/delete",
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
                    this.comments.items = this.comments.items.filter(item => item.id !== id);
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
