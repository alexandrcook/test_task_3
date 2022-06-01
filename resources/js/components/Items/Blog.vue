<template>
    <div class="card mb-4" :data-id="blog.id">
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between">
                <div>#{{blog.id}}</div>
                <div>{{blog.created_at}}</div>
            </h5>
            <h5 class="card-title">Blog: {{blog.name}}</h5>
            <h5 class="card-title">Author: {{blog.user.name}}</h5>
            <div class="d-flex justify-content-between">
                <a :href="'/blogs/'+blog.id" class="btn btn-primary">See all blog posts ({{blog.posts_count}})</a>
                <div v-if="this.$root.user.is_admin">
                    <form @submit.prevent="removeBlog(blog.id)">
                        <button class="btn btn-danger" type="submit">Remove blog</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

export default {
    name: "Blog",
    props: ['blog'],
    methods: {
        async removeBlog(id) {
            this.errors = {};
            try {
                const res = await fetch(
                    "/api/blogs/"+id+"/delete",
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
                    this.$el.remove();
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
