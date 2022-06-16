<template>
    <div>
        <div v-if="$root.user.id" class="mb-4">
            <form @submit.prevent="createBlog">
                <h3>Create new blog</h3>
                <div class="d-flex justify-content-between">
                    <input class="form-control w-75 d-inline-block mr-2" name="name" v-model="formData.name" placeholder="name">
                    <button class="btn btn-info" type="submit">Create new blog</button>
                </div>
            </form>
            <div v-if="formErrors" class="mt-4">
                <FormErrors :errors="formErrors" />
            </div>
        </div>
        <div v-else class="mb-4">
            <h3>Only logged users can create new blogs...</h3>
            <hr>
        </div>
    </div>
</template>

<script>
import FormErrors from "./FormErrors";
import {createNewBlog, getDataErrors} from "./create-item.service";

export default {
    name: "BlogCreate",
    components: {
        FormErrors
    },
    props: ['blogs'],
    data(){
        return{
            formData: {
                name: null
            },
            formErrors: null,
        }
    },
    methods: {
        async createBlog() {
            this.formErrors = null;
            const { name } = this.formData;
            try {
                const data = await createNewBlog(name, this.$root.fetch_headers_config);

                if(data.errors){
                    this.formErrors = getDataErrors(data);
                }

                if(data.data){
                    console.log(11111, data);
                    this.formData.name = '';
                    this.blogs.items.unshift(data.data);
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
