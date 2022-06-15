<template>
    <div>
        <div v-if="$root.user.id" class="mb-4">
            <form @submit.prevent="createBlog">
                <h3>Create new blog</h3>
                <div class="d-flex justify-content-between">
                    <input class="form-control w-75 d-inline-block mr-2" name="name" v-model="formData.blog.name" placeholder="name">
                    <button class="btn btn-info" type="submit">Create new blog</button>
                </div>
            </form>
            <div v-if="formErrors.blog" class="mt-4">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in formErrors.blog">{{ error }}</li>
                </ul>
            </div>
        </div>
        <div v-else class="mb-4">
            <h3>Logged users could create new blogs...</h3>
            <hr>
        </div>
    </div>
</template>

<script>
export default {
    name: "BlogCreate",
    props: ['blogs'],
    data(){
        return{
            formData: {
                blog: {
                    name: null
                },
            },
            formErrors: {
                blog: null
            },
        }
    },
    methods: {
        async createBlog() {
            this.formErrors.blog = null;
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
                    console.log(11111, data);
                    this.formData.blog.name = '';
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
