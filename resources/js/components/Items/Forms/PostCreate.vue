<template>
    <div>
        <div v-if="$root.user.id">
            <h3>Create new Post</h3>
            <form @submit.prevent="createPost">
                <div class="d-flex justify-content-between">
                    <input class="form-control w-75 mr-2" name="subject" v-model="formData.subject" placeholder="subject">
                    <button class="btn btn-info" type="submit">Create new post</button>
                </div>
                <br>
                <div>
                    <textarea class="form-control" name="body" v-model="formData.body" placeholder="body"></textarea>
                </div>
            </form>
            <div v-if="formErrors" class="mt-4">
                <FormErrors :errors="formErrors" />
            </div>
        </div>
        <div v-else class="mb-4">
            <h3>Logged users could create new posts...</h3>
            <hr>
        </div>
    </div>
</template>

<script>
import FormErrors from "./FormErrors";
import {createNewPost, getDataErrors} from "./create-item.service";

export default {
    name: "PostCreate",
    props: ['posts', 'blog_id'],
    components: {
        FormErrors,
    },
    data(){
        return {
            formData: {
                subject: null,
                body: null
            },
            formsVisibility: {
                post: true
            },
            formErrors: null
        }
    },
    methods: {
        async createPost() {
            this.formErrors = null;
            const { subject, body } = this.formData;
            try {
                const data = await createNewPost(subject, body, this.$props.blog_id, this.$root.fetch_headers_config);

                if(data.errors){
                    this.formErrors = getDataErrors(data);
                }

                if(data.data){
                    this.formData.subject = '';
                    this.formData.body = '';
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
