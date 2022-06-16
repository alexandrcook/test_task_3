<template>
    <div class="mb-5">
        <div v-if="this.$root.user.is_admin">
            <!-- Blogs -->
            <div v-if="this.trashedBlogs.initialized">
                <div v-if="this.trashedBlogs.items.length">
                    <h5>Trashed blogs list [{{this.trashedBlogs.items.length}}]:</h5>
                    <br>
                    <div v-for="blog in this.trashedBlogs.items">
                        <Blog :blog="blog" :restore="restore" :forceRemove="forceRemove"></Blog>
                    </div>
                </div>
                <h5 v-else>Trashed blogs not found...</h5>
                <button v-if="!this.trashedBlogs.loaded && this.trashedBlogs.initialized" class="btn btn-info" @click="getTrashedItems('blogs')">Load more trashed blogs...</button>
            </div>
            <!-- Blogs END -->

            <!-- Posts -->
            <div v-if="this.trashedPosts.initialized">
                <hr>
                <div v-if="this.trashedPosts.items.length">
                    <h5>Trashed posts list [{{this.trashedPosts.items.length}}]:</h5>
                    <br>
                    <div v-for="post in this.trashedPosts.items">
                        <Post :post="post" :restore="restore" :forceRemove="forceRemove"></Post>
                    </div>
                </div>
                <h5 v-else>Trashed posts not found...</h5>
                <button v-if="!this.trashedPosts.loaded && this.trashedPosts.initialized" class="btn btn-info" @click="getTrashedItems('posts')">Load more trashed posts...</button>
            </div>
            <!-- Posts End -->

            <!-- Comments -->
            <div v-if="this.trashedComments.initialized">
                <hr>
                <div v-if="this.trashedComments.items.length">
                    <h5>Trashed commentaries list [{{this.trashedComments.items.length}}]:</h5>
                    <br>
                    <div v-for="comment in this.trashedComments.items">
                        <Commentary :comment="comment" :restore="restore" :forceRemove="forceRemove"></Commentary>
                    </div>
                </div>
                <h5 v-else>Trashed comments not found...</h5>
                <button v-if="!this.trashedComments.loaded && this.trashedComments.initialized" class="btn btn-info" @click="getTrashedItems('comments')">Load more trashed commentaries...</button>
            </div>
            <!-- Comments End -->

            <div v-if="!this.trashedBlogs.initialized
            || !this.trashedPosts.initialized
            || !this.trashedComments.initialized"
            >Loading...</div>
        </div>
        <div v-else>Only admin can see trashed items...</div>
    </div>
</template>

<script>
import Blog from "../../Items/Account/Blog";
import Post from "../../Items/Account/Post";
import Commentary from "../../Items/Account/Commentary";
export default {
    name: "Account",
    components: {
        Blog,Post,Commentary
    },
    data() {
        return {
            trashedBlogs: {
                items: [],
                cursor: null,
                loaded: false,
                initialized: false
            },
            trashedPosts: {
                items: [],
                cursor: null,
                loaded: false,
                initialized: false
            },
            trashedComments: {
                items: [],
                cursor: null,
                loaded: false,
                initialized: false
            }
        };
    },
    methods: {
        getAllTrashedItems(){
          this.getTrashedItems('blogs');
          this.getTrashedItems('posts');
          this.getTrashedItems('comments');
        },
        async getTrashedItems(type) {
            let url;
            switch (type){
                case 'blogs':
                    url = `/api/${type}/trashed${this.trashedBlogs.cursor ? `?cursor=${this.trashedBlogs.cursor}` : ''}`;
                    break;
                case 'posts':
                    url = `/api/${type}/trashed${this.trashedPosts.cursor ? `?cursor=${this.trashedPosts.cursor}` : ''}`;
                    break;
                case 'comments':
                    url = `/api/${type}/trashed${this.trashedComments.cursor ? `?cursor=${this.trashedComments.cursor}` : ''}`;
                    break;
            }

            try {
                const res = await fetch(
                    url,
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config
                    }
                )

                if(res.status === 401){
                    this.$root.logoutUser();
                }

                const data = await res.json();

                if(data){
                    switch (type){
                        case 'blogs':
                            for(const blogItem of data.data){
                                this.trashedBlogs.items.push(blogItem);
                            }
                            this.trashedBlogs.initialized = true;
                            this.trashedBlogs.cursor = data.meta.next_cursor;
                            if(!data.meta.next_cursor){
                                this.trashedBlogs.loaded = true;
                            }
                            break;
                        case 'posts':
                            for(const postItem of data.data){
                                this.trashedPosts.items.push(postItem);
                            }
                            this.trashedPosts.initialized = true;
                            this.trashedPosts.cursor = data.meta.next_cursor;
                            if(!data.meta.next_cursor){
                                this.trashedPosts.loaded = true;
                            }
                            break;
                        case 'comments':
                            for(const commentItem of data.data){
                                this.trashedComments.items.push(commentItem);
                            }
                            this.trashedComments.initialized = true;
                            this.trashedComments.cursor = data.meta.next_cursor;
                            if(!data.meta.next_cursor){
                                this.trashedComments.loaded = true;
                            }
                            break;
                    }
                }
            } catch (err) {
                console.log('err', err)
            }
        },

        async forceRemove(type, id) {
            try {
                const res = await fetch(
                    `/api/${type}s/${id}`,
                    {
                        method: "DELETE",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if (data.removed) {
                    this.removeElement(type, id);
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        async restore(type, id) {
            try {
                const res = await fetch(
                    `/api/${type}s/${id}/restore/`,
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if (data.restored) {
                    this.removeElement(type, id);
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        removeElementFromArray(array, id) {
            const itemIndex = array.findIndex(item => item.id === id);
            if (itemIndex !== -1) {
                array.splice(itemIndex, 1);
                return true;
            }
        },

        removeElement(type, id){
            switch (type){
                case 'blog':
                    this.trashedBlogs.items = this.trashedBlogs.items.filter((blog) => blog.id !== id);
                    let postsToRemoveIndexes = [];
                    let postsToRemove = this.trashedPosts.items.filter((post) => post.blog_id === id);
                    for(const [index, postToRemove] of Object.entries(postsToRemove)){
                        postsToRemoveIndexes.push(index);
                        this.trashedComments.items = this.trashedComments.items.filter((comment) => comment.post_id !== postToRemove.id);
                    }
                    this.trashedPosts.items = this.trashedPosts.items.filter((post, index) => postsToRemoveIndexes.includes(index));
                    break;
                case 'post':
                    this.trashedPosts.items = this.trashedPosts.items.filter((post) => post.id !== id);
                    this.trashedComments.items = this.trashedComments.items.filter((comment) => comment.post_id !== id);
                    break;
                case 'comment':
                    this.trashedComments.items = this.trashedComments.items.filter((comment) => comment.id !== id);
                    break;
            }
            if(!this.trashedBlogs.items.length){
                this.trashedBlogs.cursor = null;
                if(!this.trashedBlogs.loaded){
                    this.trashedBlogs.initialized = false;
                    this.getTrashedItems('blogs');
                }
            }
            if(!this.trashedPosts.items.length){
                this.trashedPosts.cursor = null;
                if(!this.trashedPosts.loaded){
                    this.trashedPosts.initialized = false;
                    this.getTrashedItems('posts');
                }
            }
            if(!this.trashedComments.items.length){
                this.trashedComments.cursor = null;
                if(!this.trashedComments.loaded){
                    this.trashedComments.initialized = false;
                    this.getTrashedItems('comments');
                }
            }
        }
    },
    mounted() {
        if(!this.$root.user.id){
            this.$router.push({name: 'login'})
        }
        if(this.$root.user.is_admin){
            this.getAllTrashedItems();
        }
    }
}
</script>
