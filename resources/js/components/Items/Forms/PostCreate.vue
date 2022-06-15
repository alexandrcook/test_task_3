<template>
    <div>
        <div v-if="$root.user.id">
            <h3>Create new Post</h3>
            <form @submit.prevent="createPost">
                <div class="d-flex justify-content-between">
                    <input class="form-control w-75 mr-2" name="subject" v-model="formData.post.subject" placeholder="subject">
                    <button class="btn btn-info" type="submit">Create new post</button>
                </div>
                <br>
                <div>
                    <textarea class="form-control" name="body" v-model="formData.post.body" placeholder="body"></textarea>
                </div>
            </form>
            <div v-if="formErrors.post" class="mt-4">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in formErrors.post">{{ error }}</li>
                </ul>
            </div>
        </div>
        <div v-else class="mb-4">
            <h3>Logged users could create new posts...</h3>
            <hr>
        </div>
    </div>
</template>

<script>
export default {
    name: "PostCreate",
    props: ['posts', 'blog_id'],
    data(){
        return {
            formData: {
                post: {
                    subject: null,
                    body: null
                },
            },
            formsVisibility: {
                post: true
            },
            formErrors: {
                post: null
            }
        }
    },
    methods: {
        async createPost() {
            this.formErrors.post = null;
            const { subject, body } = this.formData.post;
            try {
                const res = await fetch(
                    `/api/blogs/${this.$props.blog_id}/posts/`,
                    {
                        method: "POST",
                        headers: this.$root.fetch_headers_config,
                        body: JSON.stringify({
                            subject, body
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
                    this.formData.post.subject = '';
                    this.formData.post.body = '';
                    this.$props.posts.items.unshift(data.data);
                }

            } catch (err) {
                console.log('err', err)
            }
        },
    }
}
</script>

<style scoped>

</style>
