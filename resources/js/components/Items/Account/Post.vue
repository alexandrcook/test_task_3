<template>
    <div>
        <div class="d-flex justify-content-between mb-3">
            <div>
                Post (id: {{post.id}}) - {{post.body.slice(0,20)}}...
                <div class="mr-2">(deleted at {{post.deleted_at}})</div>
            </div>
            <div>
                <button @click="$props.restore('post', post.id)" class="btn btn-success mr-2">Restore</button>
                <button @click="$props.forceRemove('post', post.id)" class="btn btn-danger">Force DELETE!!!</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Post",
    props: ['post', 'restore', 'forceRemove']
}
</script>

<style scoped>

</style>
