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
                <router-link :to="{name: 'blog', params: {id: blog.id}}" class="btn btn-primary">See all blog posts ({{blog.posts_count}})</router-link>
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
    props: {
        blog: {type: Object},
        onBlogDelete: {type: Function}
    },
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
                    console.log(123123123, 'test');
                    this.onBlogDelete();
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
