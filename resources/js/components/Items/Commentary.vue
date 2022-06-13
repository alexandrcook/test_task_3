<template>
    <div class="card mb-2">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    {{comment.message}}
                </div>

                <div v-if="this.$root.user.is_admin">
                    <form @submit.prevent="removeCommentary(comment.id)">
                        <button class="btn btn-danger" type="submit">Remove</button>
                    </form>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <div class="d-inline mr-2">id:{{comment.id}}</div>
                <div class="text-right">commented by {{comment.user.name}}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Commentary",
    props: ['comment', 'removeCommentary'],
}
</script>

<style scoped>

</style>
