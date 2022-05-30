<template>
    <div>
        <div v-if="formsVisibility.blog" class="mb-5">
            <h3>Create new blog</h3>
            <form @submit.prevent="createBlog">
                <div>
                    <label for="name">Name</label>
                    <input class="form-control" name="name" v-model="formData.blog.name" placeholder="name">
                </div>
                <br>
                <button class="btn btn-info" type="submit">Create new blog</button>
            </form>
            <p v-if="formErrors.blog" class="mt-4">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in formErrors.blog">{{ error }}</li>
                </ul>
            </p>
        </div>

        <div v-if="userBlogs.length">
            <h3>Create new Post</h3>
            <form @submit.prevent="createPost">
                <div>
                    <label for="blog">Select blog</label>
                    <select v-model="formData.post.blog_id" class="form-control">
                        <option v-for="userBlog in userBlogs" :value="userBlog.id">{{userBlog.name}}</option>
                    </select>
                </div>
                <br>
                <div>
                    <label for="subject">Subject</label>
                    <input class="form-control" name="subject" v-model="formData.post.subject" placeholder="subject">
                </div>
                <br>
                <div>
                    <label for="body">Body</label>
                    <textarea class="form-control" name="body" v-model="formData.post.body" placeholder="body"></textarea>
                </div>
                <br>
                <button class="btn btn-info" type="submit">Create new post</button>
            </form>
            <p v-if="formErrors.post" class="mt-4">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in formErrors.post">{{ error }}</li>
                </ul>
            </p>
        </div>
        <div v-if="!userBlogs.length">
            <hr>
            Please create at least one "Blog" to create first 'Post'
            <hr>
        </div>

        <br>
        <div v-if="this.$root.user.is_admin">

            <div v-if="this.trashedItems.blogs.length">
                <hr>
                <h5>Trashed Blogs List [{{this.trashedItems.blogs.length}}]:</h5>
                <br>
                <div v-for="blog in this.trashedItems.blogs">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            id: {{blog.id}} - {{blog.name.slice(0,20)}}...
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 mr-2">(deleted at {{blog.deleted_at}})</div>
                            <form @submit.prevent="restore('blog', blog.id)">
                                <button type="submit" class="btn btn-success mr-2">Restore</button>
                            </form>

                            <form @submit.prevent="forceRemove('blog', blog.id)">
                                <button type="submit" class="btn btn-danger">Force DELETE!!!</button>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
            </div>

            <div v-if="this.trashedItems.posts.length">
                <hr>
                <h5>Trashed Posts List [{{this.trashedItems.posts.length}}]:</h5>
                <br>
                <div v-for="post in this.trashedItems.posts">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            id: {{post.id}} - {{post.body.slice(0,20)}}...
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 mr-2">(deleted at {{post.deleted_at}})</div>
                            <form @submit.prevent="restore('post', post.id)">
                                <button type="submit" class="btn btn-success mr-2">Restore</button>
                            </form>

                            <form @submit.prevent="forceRemove('post', post.id)">
                                <button type="submit" class="btn btn-danger">Force DELETE!!!</button>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
            </div>

            <div v-if="this.trashedItems.comments.length">
                <hr>
                <h5>Trashed Commentaries List [{{this.trashedItems.comments.length}}]:</h5>
                <br>
                <div v-for="comment in this.trashedItems.comments">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            id: {{comment.id}} - {{comment.message.slice(0,20)}}...
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 mr-2">(deleted at {{comment.deleted_at}})</div>
                            <form @submit.prevent="restore('comment', comment.id)">
                                <button type="submit" class="btn btn-success mr-2">Restore</button>
                            </form>

                            <form @submit.prevent="forceRemove('comment', comment.id)">
                                <button type="submit" class="btn btn-danger">Force DELETE!!!</button>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "Account",
    data() {
        return {
            userBlogs: [],
            formData: {
                blog: {
                    name: null
                },
                post: {
                    blog_id: null,
                    subject: null,
                    body: null
                }
            },
            formsVisibility: {
                blog: true,
                post: false
            },
            formErrors: {
                blog: null,
                post: null
            },
            trashedItems: {
                blogs: [],
                posts: [],
                comments: []
            }
        };
    },
    methods: {
        async getUsersBlogs() {

            console.log(this.$root.fetch_headers_config);

            try {
                const res = await fetch(
                    '/api/user/blogs/',
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                if(res.status === 401){
                    console.log(this.$root.user);
                    this.$root.logoutUser();
                }

                if(data.data){
                    this.userBlogs = data.data;
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        async createBlog() {
            this.cleanErrors();
            const { name } = this.formData.blog;
            try {
                const res = await fetch(
                        '/api/blogs/',
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config,
                        body: JSON.stringify({
                            name
                        })
                    }
                )
                const data = await res.json();

                if(data.errors){
                    this.formErrors.blog = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
                }

                if(data.data){
                    this.formData.blog.name = '';
                    this.getUsersBlogs();
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        async createPost() {
            this.cleanErrors();
            const { blog_id, subject, body } = this.formData.post;
            try {
                const res = await fetch(
                    '/api/posts/',
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config,
                        body: JSON.stringify({
                            blog_id, subject, body
                        })
                    }
                )
                const data = await res.json();

                if(data.errors){
                    this.formErrors.post = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
                }

                if(data.data){
                    this.$router.push({name: 'blog', params: {id: blog_id}});
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        async getTrashedItems() {
            try {
                const res = await fetch(
                    '/api/trashed/',
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config
                    }
                )
                const data = await res.json();

                console.log(data);

                if(data.errors){
                    this.formErrors.post = Object.entries(data.errors).map(error => {
                        const [key, value] = error;
                        return {[key]: value[0]};
                    });
                }

                if(data){
                    console.log('updated removed data');
                    this.trashedItems.blogs = data.trashedBlogs;
                    this.trashedItems.posts = data.trashedPosts;
                    this.trashedItems.comments = data.trashedComments;
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        async forceRemove(type, id) {
            this.errors = {};
            try {
                const res = await fetch(
                    "/api/"+type+"s/"+id,
                    {
                        method: "DELETE",
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
                    this.getTrashedItems();

                    // switch (type) {
                    //     case 'blog': {
                    //         this.removeBlog('blogs', id);
                    //         break;
                    //     }
                    //     case 'post': {
                    //         this.removePost('posts', id);
                    //         break;
                    //     }
                    //     default: {
                    //         this.removeItem('comments', id);
                    //     }
                    // }
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        async restore(type, id) {
            this.errors = {};
            try {
                const res = await fetch(
                    "/api/"+type+"s/"+id+"/restore/",
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

                if (data.restored) {
                    console.log(this.$el);
                    this.getTrashedItems();
                }

            } catch (err) {
                console.log('err', err)
            }
        },

        cleanErrors(){
            this.formErrors.blog = null;
            this.formErrors.post = null;
        },
        // removeItem(type, id) {
        //     console.log(111, type, id)
        //     this.trashedItems[type] = this.trashedItems[type].filter(item => item.id !== id);
        // },
        //
        // removePost(type, id) {
        //     this.removeItem(type, id);
        //     this.trashedItems.comments = this.trashedItems.comments.filter(
        //         comment => comment.post_id !== id
        //     );
        // },
        //
        // removeBlog(type, id) {
        //     const removedPosts = [];
        //     this.removeItem(type, id);
        //     this.trashedItems.posts = this.trashedItems.posts.filter(post => {
        //         if (post.blog_id !== id) {
        //             removedPosts.push(post.id);
        //         }
        //         return post.blog_id !== id
        //     });
        //     this.trashedItems.comments = this.trashedItems.comments.filter(
        //         comment => !removedPosts.includes(comment.post_id)
        //     );
        // },
    },
    mounted() {

        if(!this.$root.user.id){
            this.$router.push({name: 'login'})
        }
        if(this.$root.user.is_admin){
            this.getTrashedItems();
        }
        this.getUsersBlogs();
    }
}
</script>
