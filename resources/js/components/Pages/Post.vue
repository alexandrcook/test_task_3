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

            <div>
                <hr>
                <h4>Commentaries</h4>

                <div v-if="$root.user.id">
                    <hr>
                        <p v-if="formErrors.comment" class="mt-4">
                            <b>Please correct the following error(s):</b>
                            <ul>
                                <li v-for="error in formErrors.comment">{{ error }}</li>
                            </ul>
                        </p>
                        <form @submit.prevent="addComment">
                            <textarea v-model="new_comment.message" class="form-control"></textarea>
                            <div class="d-flex justify-content-between mt-3">
                                <h5>Add new commentary</h5>
                                <button type="submit" class="btn btn-info">Add</button>
                            </div>
                        </form>
                    <hr>
                </div>
                <div v-else>
                    <hr>
                        ... only registered / logged user could write commentaries
                    <hr>
                </div>
            </div>
            <InfiniteScroll :loadItemsFn="getPost" :allItemsLoaded="!comments.loaded">
                <div v-for="comment in comments.items" class="">
                    <Commentary v-bind:comment="comment" :removeCommentary="removeCommentary"></Commentary>
                </div>
            </InfiniteScroll>
        </div>

    </div>
</template>

<script>

import InfiniteScroll from "../Items/InfiniteScroll";
import Commentary from "../Items/Commentary";

export default {
    name: "Post",
    components: {
        InfiniteScroll,
        Commentary
    },
    data() {
        return {
            post: null,
            comments: {
                items: [],
                loaded: false
            },
            new_comment: {
                message: null
            },
            formErrors: {
                comment: null
            }
        };
    },
    methods: {
        async addComment() {
            this.formErrors.comment = null;
            const { message } = this.new_comment;
            try {
                const res = await fetch(
                    "/api/posts/"+this.$route.params.id+"/comments",
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config,
                        body: JSON.stringify({
                            message
                        })
                    }
                )
                const data = await res.json();

                if(data.errors){
                    this.formErrors.comment = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
                }

                if(data.data){
                    this.comments.items.unshift(data.data);
                    this.new_comment.message = null;
                }

            } catch (err) {
                console.log('err', err)
            }
        },
        async getPost(url = "/api/posts/"+this.$route.params.id+'?skip_comments='+this.comments.items.length) {
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

                if(data.status === 'error'){
                    this.errors = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
                }

                if(data.data){
                    this.post = data.data;
                }

                if(data.commentsData){
                    for(const [key, val] of Object.entries(data.commentsData.data)){
                        this.comments.items.push(val);
                    }
                }

                if(data.allCommentsLoaded){
                    this.comments.loaded = true;
                }

                this.comments.page++;

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
