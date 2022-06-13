<template>
    <div class="container d-flex justify-content-between mt-4 mb-4">
        <div>
            <div v-if="$route.name === 'blog' || $route.name === 'post' || $route.name === 'account'">
                <router-link :to="{name: 'blogs'}" class="d-inline">Home</router-link>

                <div class="d-inline" v-if="$route.name === 'blog' || $route.name === 'post'">
                    >
                    <router-link v-if="$route.name === 'post'" :to="{name: 'blog', params: {id: $route.params.blog_id}}">Blog</router-link>
                    <span v-else>Blog</span>
                </div>

                <div class="d-inline" v-if="$route.name === 'post'">
                    >
                    <span>Post</span>
                </div>

                <br>
            </div>
        </div>

        <div>
            <div v-if="$root.user.id">
                <div v-if="$route.name !== 'account'">
                    <router-link  to="/account">My account</router-link>
                </div>
                <div v-else>
                    <span class="page-link" @click="$root.logoutUser()">Logout</span>
                </div>
            </div>

            <div v-else>
                <router-link to="/login" class="mr-2">Login</router-link>/
                <router-link to="/register" class="ml-1">Register</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Navigation"
}
</script>

<style scoped>

</style>
